/**
 * Mdchart V2.0 Supreme Edition - Gaming Engine
 * Revolutionary social gaming platform with advanced features
 * 
 * Author: Ebube Eze
 * Features: Real-time multiplayer, tournaments, streaming, virtual economy
 */

const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const cors = require('cors');
const helmet = require('helmet');
const compression = require('compression');
const { Server } = require('colyseus');
const { monitor } = require('@colyseus/monitor');
const Redis = require('ioredis');
const mongoose = require('mongoose');
const winston = require('winston');
const rateLimit = require('express-rate-limit');
const config = require('config');

// Import game modules
const GameRoom = require('./src/rooms/GameRoom');
const TournamentManager = require('./src/tournament/TournamentManager');
const StreamingService = require('./src/streaming/StreamingService');
const AchievementSystem = require('./src/achievements/AchievementSystem');
const VirtualEconomy = require('./src/economy/VirtualEconomy');
const Leaderboards = require('./src/leaderboards/Leaderboards');
const SocialGaming = require('./src/social/SocialGaming');
const BlockchainIntegration = require('./src/blockchain/BlockchainIntegration');
const GameAnalytics = require('./src/analytics/GameAnalytics');

// Gaming engine class
class MdchartGamingEngine {
    constructor() {
        this.app = express();
        this.server = http.createServer(this.app);
        this.gameServer = new Server({ server: this.server });
        this.io = socketIo(this.server, {
            cors: {
                origin: "*",
                methods: ["GET", "POST"]
            }
        });
        
        this.redis = new Redis(process.env.REDIS_URL || 'redis://localhost:6379');
        this.logger = this.setupLogger();
        
        // Initialize services
        this.tournamentManager = new TournamentManager(this);
        this.streamingService = new StreamingService(this);
        this.achievementSystem = new AchievementSystem(this);
        this.virtualEconomy = new VirtualEconomy(this);
        this.leaderboards = new Leaderboards(this);
        this.socialGaming = new SocialGaming(this);
        this.blockchainIntegration = new BlockchainIntegration(this);
        this.gameAnalytics = new GameAnalytics(this);
        
        // Game state
        this.activeGames = new Map();
        this.activeTournaments = new Map();
        this.playerStats = new Map();
        this.gameRooms = new Map();
        
        this.initialize();
    }
    
    setupLogger() {
        return winston.createLogger({
            level: 'info',
            format: winston.format.combine(
                winston.format.timestamp(),
                winston.format.errors({ stack: true }),
                winston.format.json()
            ),
            defaultMeta: { service: 'gaming-engine' },
            transports: [
                new winston.transports.File({ filename: 'logs/error.log', level: 'error' }),
                new winston.transports.File({ filename: 'logs/combined.log' }),
                new winston.transports.Console({
                    format: winston.format.simple()
                })
            ]
        });
    }
    
    async initialize() {
        try {
            // Setup middleware
            this.setupMiddleware();
            
            // Connect to databases
            await this.connectDatabases();
            
            // Setup game rooms
            this.setupGameRooms();
            
            // Setup routes
            this.setupRoutes();
            
            // Setup socket events
            this.setupSocketEvents();
            
            // Initialize services
            await this.initializeServices();
            
            // Start server
            this.startServer();
            
            this.logger.info('Mdchart Gaming Engine initialized successfully');
            
        } catch (error) {
            this.logger.error('Failed to initialize gaming engine:', error);
            process.exit(1);
        }
    }
    
    setupMiddleware() {
        // Security middleware
        this.app.use(helmet());
        this.app.use(cors());
        this.app.use(compression());
        
        // Rate limiting
        const limiter = rateLimit({
            windowMs: 15 * 60 * 1000, // 15 minutes
            max: 1000 // limit each IP to 1000 requests per windowMs
        });
        this.app.use(limiter);
        
        // Body parsing
        this.app.use(express.json({ limit: '10mb' }));
        this.app.use(express.urlencoded({ extended: true }));
        
        // Logging
        this.app.use((req, res, next) => {
            this.logger.info(`${req.method} ${req.path}`, {
                ip: req.ip,
                userAgent: req.get('user-agent')
            });
            next();
        });
    }
    
    async connectDatabases() {
        try {
            // MongoDB connection
            await mongoose.connect(process.env.MONGODB_URL || 'mongodb://localhost:27017/mdchart_gaming', {
                useNewUrlParser: true,
                useUnifiedTopology: true
            });
            this.logger.info('Connected to MongoDB');
            
            // Redis connection test
            await this.redis.ping();
            this.logger.info('Connected to Redis');
            
        } catch (error) {
            this.logger.error('Database connection failed:', error);
            throw error;
        }
    }
    
    setupGameRooms() {
        // Register different game room types
        this.gameServer.define('puzzle_room', GameRoom.PuzzleRoom);
        this.gameServer.define('strategy_room', GameRoom.StrategyRoom);
        this.gameServer.define('party_room', GameRoom.PartyRoom);
        this.gameServer.define('trivia_room', GameRoom.TriviaRoom);
        this.gameServer.define('tournament_room', GameRoom.TournamentRoom);
        this.gameServer.define('casual_room', GameRoom.CasualRoom);
        
        // Setup monitoring
        this.app.use('/gaming/monitor', monitor());
    }
    
    setupRoutes() {
        // Health check
        this.app.get('/health', (req, res) => {
            res.json({
                status: 'healthy',
                version: '2.0.0',
                timestamp: new Date().toISOString(),
                services: {
                    gameServer: this.gameServer ? 'running' : 'down',
                    redis: this.redis.status,
                    mongodb: mongoose.connection.readyState === 1 ? 'connected' : 'disconnected'
                }
            });
        });
        
        // Gaming API routes
        this.app.use('/api/games', require('./src/routes/games'));
        this.app.use('/api/tournaments', require('./src/routes/tournaments'));
        this.app.use('/api/leaderboards', require('./src/routes/leaderboards'));
        this.app.use('/api/achievements', require('./src/routes/achievements'));
        this.app.use('/api/streaming', require('./src/routes/streaming'));
        this.app.use('/api/economy', require('./src/routes/economy'));
        this.app.use('/api/social', require('./src/routes/social'));
        this.app.use('/api/analytics', require('./src/routes/analytics'));
        
        // Game-specific routes
        this.setupGameRoutes();
        
        // Error handling
        this.app.use((error, req, res, next) => {
            this.logger.error('API Error:', error);
            res.status(500).json({
                error: 'Internal server error',
                message: process.env.NODE_ENV === 'development' ? error.message : 'Something went wrong'
            });
        });
    }
    
    setupGameRoutes() {
        // Create game session
        this.app.post('/api/games/create', async (req, res) => {
            try {
                const { gameType, players, settings } = req.body;
                
                const gameSession = await this.createGameSession(gameType, players, settings);
                
                res.json({
                    success: true,
                    gameSession,
                    roomId: gameSession.roomId,
                    joinUrl: `/games/join/${gameSession.roomId}`
                });
                
            } catch (error) {
                this.logger.error('Failed to create game session:', error);
                res.status(500).json({ error: error.message });
            }
        });
        
        // Join game
        this.app.post('/api/games/join', async (req, res) => {
            try {
                const { roomId, playerId, playerData } = req.body;
                
                const result = await this.joinGame(roomId, playerId, playerData);
                
                res.json({
                    success: true,
                    ...result
                });
                
            } catch (error) {
                this.logger.error('Failed to join game:', error);
                res.status(500).json({ error: error.message });
            }
        });
        
        // Game results
        this.app.post('/api/games/results', async (req, res) => {
            try {
                const { roomId, results } = req.body;
                
                await this.processGameResults(roomId, results);
                
                res.json({ success: true });
                
            } catch (error) {
                this.logger.error('Failed to process game results:', error);
                res.status(500).json({ error: error.message });
            }
        });
        
        // Get game statistics
        this.app.get('/api/games/stats/:playerId', async (req, res) => {
            try {
                const { playerId } = req.params;
                
                const stats = await this.getPlayerStats(playerId);
                
                res.json({
                    success: true,
                    stats
                });
                
            } catch (error) {
                this.logger.error('Failed to get player stats:', error);
                res.status(500).json({ error: error.message });
            }
        });
    }
    
    setupSocketEvents() {
        this.io.on('connection', (socket) => {
            this.logger.info(`Player connected: ${socket.id}`);
            
            // Player authentication
            socket.on('authenticate', async (data) => {
                try {
                    const player = await this.authenticatePlayer(data);
                    socket.playerId = player.id;
                    socket.playerData = player;
                    
                    socket.emit('authenticated', {
                        success: true,
                        player
                    });
                    
                    // Join player to their personal room for notifications
                    socket.join(`player_${player.id}`);
                    
                } catch (error) {
                    socket.emit('authentication_failed', { error: error.message });
                }
            });
            
            // Join game room
            socket.on('join_game', async (data) => {
                try {
                    const { roomId } = data;
                    
                    socket.join(roomId);
                    
                    // Notify room about new player
                    socket.to(roomId).emit('player_joined', {
                        playerId: socket.playerId,
                        playerData: socket.playerData
                    });
                    
                    this.logger.info(`Player ${socket.playerId} joined game ${roomId}`);
                    
                } catch (error) {
                    socket.emit('join_error', { error: error.message });
                }
            });
            
            // Game actions
            socket.on('game_action', async (data) => {
                try {
                    await this.handleGameAction(socket, data);
                } catch (error) {
                    socket.emit('action_error', { error: error.message });
                }
            });
            
            // Chat messages
            socket.on('chat_message', (data) => {
                const { roomId, message } = data;
                
                // Broadcast to room
                socket.to(roomId).emit('chat_message', {
                    playerId: socket.playerId,
                    playerName: socket.playerData?.name,
                    message,
                    timestamp: Date.now()
                });
            });
            
            // Tournament events
            socket.on('join_tournament', async (data) => {
                try {
                    await this.tournamentManager.joinTournament(socket.playerId, data.tournamentId);
                    socket.emit('tournament_joined', { success: true });
                } catch (error) {
                    socket.emit('tournament_error', { error: error.message });
                }
            });
            
            // Streaming events
            socket.on('start_stream', async (data) => {
                try {
                    await this.streamingService.startStream(socket.playerId, data);
                    socket.emit('stream_started', { success: true });
                } catch (error) {
                    socket.emit('stream_error', { error: error.message });
                }
            });
            
            // Disconnect handling
            socket.on('disconnect', () => {
                this.logger.info(`Player disconnected: ${socket.id}`);
                this.handlePlayerDisconnect(socket);
            });
        });
    }
    
    async initializeServices() {
        try {
            await this.tournamentManager.initialize();
            await this.streamingService.initialize();
            await this.achievementSystem.initialize();
            await this.virtualEconomy.initialize();
            await this.leaderboards.initialize();
            await this.socialGaming.initialize();
            await this.blockchainIntegration.initialize();
            await this.gameAnalytics.initialize();
            
            this.logger.info('All gaming services initialized');
            
        } catch (error) {
            this.logger.error('Failed to initialize services:', error);
            throw error;
        }
    }
    
    async createGameSession(gameType, players, settings = {}) {
        const roomId = this.generateRoomId();
        
        const gameSession = {
            roomId,
            gameType,
            players,
            settings,
            status: 'waiting',
            createdAt: new Date(),
            maxPlayers: this.getMaxPlayers(gameType)
        };
        
        // Store in Redis
        await this.redis.setex(`game_session:${roomId}`, 3600, JSON.stringify(gameSession));
        
        this.activeGames.set(roomId, gameSession);
        
        // Create Colyseus room
        try {
            const room = await this.gameServer.create(this.getRoomType(gameType), {
                gameSession,
                roomId
            });
            
            gameSession.colyseusRoomId = room.roomId;
            
        } catch (error) {
            this.logger.error('Failed to create Colyseus room:', error);
        }
        
        return gameSession;
    }
    
    async joinGame(roomId, playerId, playerData) {
        const gameSession = this.activeGames.get(roomId);
        
        if (!gameSession) {
            throw new Error('Game session not found');
        }
        
        if (gameSession.players.length >= gameSession.maxPlayers) {
            throw new Error('Game session is full');
        }
        
        // Add player to session
        gameSession.players.push({
            id: playerId,
            ...playerData,
            joinedAt: new Date()
        });
        
        // Update Redis
        await this.redis.setex(`game_session:${roomId}`, 3600, JSON.stringify(gameSession));
        
        // Notify other players
        this.io.to(roomId).emit('player_joined', {
            playerId,
            playerData,
            totalPlayers: gameSession.players.length
        });
        
        return {
            gameSession,
            success: true
        };
    }
    
    async processGameResults(roomId, results) {
        const gameSession = this.activeGames.get(roomId);
        
        if (!gameSession) {
            throw new Error('Game session not found');
        }
        
        // Update player stats
        for (const result of results.players) {
            await this.updatePlayerStats(result.playerId, result.stats);
            
            // Check achievements
            await this.achievementSystem.checkAchievements(result.playerId, result);
            
            // Update leaderboards
            await this.leaderboards.updateScore(result.playerId, result.score);
            
            // Process rewards
            if (result.won) {
                await this.virtualEconomy.rewardPlayer(result.playerId, result.rewards);
                
                // Blockchain rewards
                if (this.blockchainIntegration.isEnabled()) {
                    await this.blockchainIntegration.rewardGameWin(result.playerId, gameSession.gameType);
                }
            }
        }
        
        // Analytics
        await this.gameAnalytics.recordGameResult(gameSession, results);
        
        // Clean up
        this.activeGames.delete(roomId);
        await this.redis.del(`game_session:${roomId}`);
        
        this.logger.info(`Game ${roomId} completed with ${results.players.length} players`);
    }
    
    async updatePlayerStats(playerId, newStats) {
        const currentStats = this.playerStats.get(playerId) || {
            gamesPlayed: 0,
            gamesWon: 0,
            totalScore: 0,
            achievements: [],
            level: 1,
            xp: 0
        };
        
        // Update stats
        currentStats.gamesPlayed += 1;
        if (newStats.won) currentStats.gamesWon += 1;
        currentStats.totalScore += newStats.score || 0;
        currentStats.xp += newStats.xp || 0;
        
        // Level up check
        const newLevel = Math.floor(currentStats.xp / 1000) + 1;
        if (newLevel > currentStats.level) {
            currentStats.level = newLevel;
            
            // Level up rewards
            await this.virtualEconomy.rewardLevelUp(playerId, newLevel);
            
            // Notify player
            this.io.to(`player_${playerId}`).emit('level_up', {
                newLevel,
                rewards: { coins: 100 * newLevel, gems: 10 * newLevel }
            });
        }
        
        this.playerStats.set(playerId, currentStats);
        
        // Save to database
        await this.savePlayerStats(playerId, currentStats);
    }
    
    async getPlayerStats(playerId) {
        let stats = this.playerStats.get(playerId);
        
        if (!stats) {
            stats = await this.loadPlayerStats(playerId);
            this.playerStats.set(playerId, stats);
        }
        
        return stats;
    }
    
    async handleGameAction(socket, data) {
        const { roomId, action, payload } = data;
        
        // Validate action
        if (!this.isValidGameAction(action)) {
            throw new Error('Invalid game action');
        }
        
        // Process action based on type
        switch (action) {
            case 'move':
                await this.handleMoveAction(socket, roomId, payload);
                break;
            case 'attack':
                await this.handleAttackAction(socket, roomId, payload);
                break;
            case 'use_item':
                await this.handleItemAction(socket, roomId, payload);
                break;
            case 'chat':
                await this.handleChatAction(socket, roomId, payload);
                break;
            default:
                throw new Error(`Unknown action: ${action}`);
        }
        
        // Broadcast action to room
        socket.to(roomId).emit('game_action', {
            playerId: socket.playerId,
            action,
            payload,
            timestamp: Date.now()
        });
    }
    
    generateRoomId() {
        return `room_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;
    }
    
    getMaxPlayers(gameType) {
        const gameConfig = config.get('games');
        return gameConfig[gameType]?.max_players || 4;
    }
    
    getRoomType(gameType) {
        const typeMap = {
            'social_puzzles': 'puzzle_room',
            'strategy_games': 'strategy_room',
            'party_games': 'party_room',
            'trivia_games': 'trivia_room'
        };
        
        return typeMap[gameType] || 'casual_room';
    }
    
    isValidGameAction(action) {
        const validActions = ['move', 'attack', 'use_item', 'chat', 'ready', 'surrender'];
        return validActions.includes(action);
    }
    
    startServer() {
        const port = process.env.PORT || 3001;
        
        this.server.listen(port, () => {
            this.logger.info(`🎮 Mdchart Gaming Engine running on port ${port}`);
            this.logger.info(`🎯 Monitor available at http://localhost:${port}/gaming/monitor`);
            this.logger.info(`🚀 Ready for supreme gaming experiences!`);
        });
    }
    
    // Graceful shutdown
    async shutdown() {
        this.logger.info('Shutting down gaming engine...');
        
        // Close all active games
        for (const [roomId, gameSession] of this.activeGames) {
            this.io.to(roomId).emit('server_shutdown', {
                message: 'Server is shutting down. Game will end in 30 seconds.'
            });
        }
        
        // Wait for games to finish
        await new Promise(resolve => setTimeout(resolve, 30000));
        
        // Close connections
        this.server.close();
        await this.redis.disconnect();
        await mongoose.disconnect();
        
        this.logger.info('Gaming engine shutdown complete');
    }
}

// Start the gaming engine
const gamingEngine = new MdchartGamingEngine();

// Handle graceful shutdown
process.on('SIGINT', () => {
    gamingEngine.shutdown().then(() => process.exit(0));
});

process.on('SIGTERM', () => {
    gamingEngine.shutdown().then(() => process.exit(0));
});

module.exports = MdchartGamingEngine;