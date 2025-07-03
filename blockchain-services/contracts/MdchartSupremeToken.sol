// SPDX-License-Identifier: MIT
pragma solidity ^0.8.19;

import "@openzeppelin/contracts/token/ERC20/ERC20.sol";
import "@openzeppelin/contracts/token/ERC20/extensions/ERC20Burnable.sol";
import "@openzeppelin/contracts/token/ERC20/extensions/ERC20Snapshot.sol";
import "@openzeppelin/contracts/access/Ownable.sol";
import "@openzeppelin/contracts/security/Pausable.sol";
import "@openzeppelin/contracts/token/ERC20/extensions/draft-ERC20Permit.sol";
import "@openzeppelin/contracts/token/ERC20/extensions/ERC20Votes.sol";
import "@openzeppelin/contracts/security/ReentrancyGuard.sol";
import "@openzeppelin/contracts/utils/math/SafeMath.sol";

/**
 * @title MdchartSupremeToken (MDC)
 * @dev Revolutionary social media platform token with advanced features
 * @author Ebube Eze
 * 
 * Features:
 * - Social engagement rewards
 * - Staking and yield farming
 * - DAO governance voting
 * - Content monetization
 * - NFT marketplace integration
 * - Cross-chain compatibility
 * - Gaming rewards
 * - Creator economy support
 */
contract MdchartSupremeToken is 
    ERC20, 
    ERC20Burnable, 
    ERC20Snapshot, 
    Ownable, 
    Pausable, 
    ERC20Permit, 
    ERC20Votes,
    ReentrancyGuard 
{
    using SafeMath for uint256;

    // Token Economics
    uint256 public constant MAX_SUPPLY = 1_000_000_000 * 10**18; // 1 billion tokens
    uint256 public constant INITIAL_SUPPLY = 100_000_000 * 10**18; // 100 million initial
    
    // Reward Rates (per action)
    uint256 public constant POST_REWARD = 10 * 10**18; // 10 MDC per post
    uint256 public constant COMMENT_REWARD = 2 * 10**18; // 2 MDC per comment
    uint256 public constant LIKE_REWARD = 1 * 10**18; // 1 MDC per like
    uint256 public constant SHARE_REWARD = 5 * 10**18; // 5 MDC per share
    uint256 public constant GAMING_WIN_REWARD = 50 * 10**18; // 50 MDC per game win
    uint256 public constant NFT_CREATION_REWARD = 100 * 10**18; // 100 MDC per NFT creation
    
    // Staking Configuration
    uint256 public stakingAPY = 1200; // 12% APY (in basis points)
    uint256 public constant MIN_STAKE_AMOUNT = 100 * 10**18; // 100 MDC minimum
    uint256 public constant STAKE_LOCK_PERIOD = 30 days;
    
    // Governance Configuration
    uint256 public constant PROPOSAL_THRESHOLD = 10000 * 10**18; // 10,000 MDC to create proposal
    uint256 public constant VOTING_PERIOD = 7 days;
    uint256 public constant EXECUTION_DELAY = 2 days;

    // Social Features
    mapping(address => UserProfile) public userProfiles;
    mapping(address => StakeInfo) public stakes;
    mapping(uint256 => Proposal) public proposals;
    mapping(address => mapping(uint256 => bool)) public hasVoted;
    mapping(address => uint256) public lastRewardClaim;
    mapping(address => uint256) public creatorEarnings;
    mapping(address => bool) public verifiedCreators;
    
    // Gaming Integration
    mapping(address => GamingStats) public gamingStats;
    mapping(address => uint256) public tournamentWins;
    mapping(address => bool) public gameContracts;
    
    // NFT Marketplace Integration
    mapping(address => bool) public nftMarketplaces;
    mapping(address => uint256) public nftRoyalties;
    
    // Events
    event SocialReward(address indexed user, string action, uint256 amount);
    event StakeCreated(address indexed user, uint256 amount, uint256 unlockTime);
    event StakeWithdrawn(address indexed user, uint256 amount, uint256 reward);
    event ProposalCreated(uint256 indexed proposalId, address indexed creator, string description);
    event VoteCast(uint256 indexed proposalId, address indexed voter, bool support, uint256 weight);
    event CreatorVerified(address indexed creator);
    event GameReward(address indexed user, string game, uint256 amount);
    event NFTRoyaltyPaid(address indexed creator, uint256 amount);
    event CrossChainTransfer(address indexed from, address indexed to, uint256 amount, uint256 chainId);

    // Structs
    struct UserProfile {
        uint256 posts;
        uint256 followers;
        uint256 following;
        uint256 totalEngagement;
        uint256 influenceScore;
        bool isVerified;
        uint256 joinedAt;
    }
    
    struct StakeInfo {
        uint256 amount;
        uint256 stakedAt;
        uint256 unlockTime;
        uint256 rewardDebt;
        bool active;
    }
    
    struct Proposal {
        uint256 id;
        address creator;
        string description;
        uint256 forVotes;
        uint256 againstVotes;
        uint256 createdAt;
        uint256 endTime;
        bool executed;
        bool passed;
    }
    
    struct GamingStats {
        uint256 gamesPlayed;
        uint256 gamesWon;
        uint256 totalRewards;
        uint256 currentStreak;
        uint256 bestStreak;
    }

    // Modifiers
    modifier onlyVerifiedCreator() {
        require(verifiedCreators[msg.sender], "Not a verified creator");
        _;
    }
    
    modifier onlyGameContract() {
        require(gameContracts[msg.sender], "Not authorized game contract");
        _;
    }
    
    modifier onlyNFTMarketplace() {
        require(nftMarketplaces[msg.sender], "Not authorized NFT marketplace");
        _;
    }

    constructor() 
        ERC20("Mdchart Supreme Token", "MDC") 
        ERC20Permit("Mdchart Supreme Token")
    {
        _mint(msg.sender, INITIAL_SUPPLY);
        
        // Initialize user profile for deployer
        userProfiles[msg.sender] = UserProfile({
            posts: 0,
            followers: 0,
            following: 0,
            totalEngagement: 0,
            influenceScore: 1000,
            isVerified: true,
            joinedAt: block.timestamp
        });
        
        verifiedCreators[msg.sender] = true;
    }

    // Social Media Reward Functions
    function rewardPost(address user, uint256 engagementBonus) 
        external 
        onlyOwner 
        whenNotPaused 
    {
        require(user != address(0), "Invalid user address");
        
        uint256 baseReward = POST_REWARD;
        uint256 totalReward = baseReward.add(engagementBonus);
        
        // Apply influence multiplier
        uint256 influenceMultiplier = userProfiles[user].influenceScore.div(1000);
        totalReward = totalReward.mul(influenceMultiplier);
        
        _mintReward(user, totalReward);
        
        // Update user stats
        userProfiles[user].posts = userProfiles[user].posts.add(1);
        userProfiles[user].totalEngagement = userProfiles[user].totalEngagement.add(engagementBonus);
        
        emit SocialReward(user, "post", totalReward);
    }
    
    function rewardComment(address user) external onlyOwner whenNotPaused {
        _mintReward(user, COMMENT_REWARD);
        emit SocialReward(user, "comment", COMMENT_REWARD);
    }
    
    function rewardLike(address user) external onlyOwner whenNotPaused {
        _mintReward(user, LIKE_REWARD);
        emit SocialReward(user, "like", LIKE_REWARD);
    }
    
    function rewardShare(address user) external onlyOwner whenNotPaused {
        _mintReward(user, SHARE_REWARD);
        emit SocialReward(user, "share", SHARE_REWARD);
    }
    
    function rewardNFTCreation(address creator) external onlyNFTMarketplace whenNotPaused {
        _mintReward(creator, NFT_CREATION_REWARD);
        emit SocialReward(creator, "nft_creation", NFT_CREATION_REWARD);
    }

    // Gaming Reward Functions
    function rewardGameWin(address player, string memory gameName) 
        external 
        onlyGameContract 
        whenNotPaused 
    {
        uint256 reward = GAMING_WIN_REWARD;
        
        // Streak bonus
        GamingStats storage stats = gamingStats[player];
        stats.currentStreak = stats.currentStreak.add(1);
        
        if (stats.currentStreak > stats.bestStreak) {
            stats.bestStreak = stats.currentStreak;
        }
        
        // Apply streak multiplier (10% per win in streak, max 50%)
        uint256 streakMultiplier = stats.currentStreak.mul(10);
        if (streakMultiplier > 50) streakMultiplier = 50;
        
        uint256 streakBonus = reward.mul(streakMultiplier).div(100);
        reward = reward.add(streakBonus);
        
        _mintReward(player, reward);
        
        // Update gaming stats
        stats.gamesPlayed = stats.gamesPlayed.add(1);
        stats.gamesWon = stats.gamesWon.add(1);
        stats.totalRewards = stats.totalRewards.add(reward);
        
        emit GameReward(player, gameName, reward);
    }
    
    function rewardTournamentWin(address player, uint256 prizePool) 
        external 
        onlyGameContract 
        whenNotPaused 
    {
        tournamentWins[player] = tournamentWins[player].add(1);
        _mintReward(player, prizePool);
        
        emit GameReward(player, "tournament", prizePool);
    }

    // Staking Functions
    function stake(uint256 amount) external nonReentrant whenNotPaused {
        require(amount >= MIN_STAKE_AMOUNT, "Amount below minimum");
        require(balanceOf(msg.sender) >= amount, "Insufficient balance");
        require(!stakes[msg.sender].active, "Already staking");
        
        _transfer(msg.sender, address(this), amount);
        
        stakes[msg.sender] = StakeInfo({
            amount: amount,
            stakedAt: block.timestamp,
            unlockTime: block.timestamp.add(STAKE_LOCK_PERIOD),
            rewardDebt: 0,
            active: true
        });
        
        emit StakeCreated(msg.sender, amount, stakes[msg.sender].unlockTime);
    }
    
    function unstake() external nonReentrant whenNotPaused {
        StakeInfo storage stakeInfo = stakes[msg.sender];
        require(stakeInfo.active, "No active stake");
        require(block.timestamp >= stakeInfo.unlockTime, "Stake still locked");
        
        uint256 stakedAmount = stakeInfo.amount;
        uint256 reward = calculateStakeReward(msg.sender);
        
        stakeInfo.active = false;
        stakeInfo.amount = 0;
        
        // Transfer staked amount back
        _transfer(address(this), msg.sender, stakedAmount);
        
        // Mint reward
        if (reward > 0) {
            _mintReward(msg.sender, reward);
        }
        
        emit StakeWithdrawn(msg.sender, stakedAmount, reward);
    }
    
    function calculateStakeReward(address user) public view returns (uint256) {
        StakeInfo memory stakeInfo = stakes[user];
        if (!stakeInfo.active) return 0;
        
        uint256 stakingDuration = block.timestamp.sub(stakeInfo.stakedAt);
        uint256 annualReward = stakeInfo.amount.mul(stakingAPY).div(10000);
        uint256 reward = annualReward.mul(stakingDuration).div(365 days);
        
        return reward;
    }

    // Governance Functions
    uint256 private proposalCounter = 0;
    
    function createProposal(string memory description) external {
        require(balanceOf(msg.sender) >= PROPOSAL_THRESHOLD, "Insufficient tokens for proposal");
        
        proposalCounter = proposalCounter.add(1);
        
        proposals[proposalCounter] = Proposal({
            id: proposalCounter,
            creator: msg.sender,
            description: description,
            forVotes: 0,
            againstVotes: 0,
            createdAt: block.timestamp,
            endTime: block.timestamp.add(VOTING_PERIOD),
            executed: false,
            passed: false
        });
        
        emit ProposalCreated(proposalCounter, msg.sender, description);
    }
    
    function vote(uint256 proposalId, bool support) external {
        Proposal storage proposal = proposals[proposalId];
        require(proposal.id != 0, "Proposal does not exist");
        require(block.timestamp <= proposal.endTime, "Voting period ended");
        require(!hasVoted[msg.sender][proposalId], "Already voted");
        
        uint256 votingPower = getVotes(msg.sender);
        require(votingPower > 0, "No voting power");
        
        hasVoted[msg.sender][proposalId] = true;
        
        if (support) {
            proposal.forVotes = proposal.forVotes.add(votingPower);
        } else {
            proposal.againstVotes = proposal.againstVotes.add(votingPower);
        }
        
        emit VoteCast(proposalId, msg.sender, support, votingPower);
    }

    // Creator Economy Functions
    function verifyCreator(address creator) external onlyOwner {
        verifiedCreators[creator] = true;
        userProfiles[creator].isVerified = true;
        emit CreatorVerified(creator);
    }
    
    function payCreatorRoyalty(address creator, uint256 amount) 
        external 
        onlyNFTMarketplace 
        whenNotPaused 
    {
        require(verifiedCreators[creator], "Not a verified creator");
        
        creatorEarnings[creator] = creatorEarnings[creator].add(amount);
        _mintReward(creator, amount);
        
        emit NFTRoyaltyPaid(creator, amount);
    }

    // Administrative Functions
    function addGameContract(address gameContract) external onlyOwner {
        gameContracts[gameContract] = true;
    }
    
    function removeGameContract(address gameContract) external onlyOwner {
        gameContracts[gameContract] = false;
    }
    
    function addNFTMarketplace(address marketplace) external onlyOwner {
        nftMarketplaces[marketplace] = true;
    }
    
    function removeNFTMarketplace(address marketplace) external onlyOwner {
        nftMarketplaces[marketplace] = false;
    }
    
    function updateStakingAPY(uint256 newAPY) external onlyOwner {
        require(newAPY <= 5000, "APY too high"); // Max 50%
        stakingAPY = newAPY;
    }
    
    function pause() external onlyOwner {
        _pause();
    }
    
    function unpause() external onlyOwner {
        _unpause();
    }
    
    function snapshot() external onlyOwner {
        _snapshot();
    }

    // Internal Functions
    function _mintReward(address to, uint256 amount) internal {
        require(totalSupply().add(amount) <= MAX_SUPPLY, "Max supply exceeded");
        _mint(to, amount);
    }

    // Cross-chain Functions
    function bridgeTransfer(address to, uint256 amount, uint256 targetChainId) 
        external 
        whenNotPaused 
    {
        require(to != address(0), "Invalid recipient");
        require(amount > 0, "Invalid amount");
        require(balanceOf(msg.sender) >= amount, "Insufficient balance");
        
        // Burn tokens on source chain
        _burn(msg.sender, amount);
        
        emit CrossChainTransfer(msg.sender, to, amount, targetChainId);
    }

    // View Functions
    function getUserProfile(address user) external view returns (UserProfile memory) {
        return userProfiles[user];
    }
    
    function getStakeInfo(address user) external view returns (StakeInfo memory) {
        return stakes[user];
    }
    
    function getGamingStats(address user) external view returns (GamingStats memory) {
        return gamingStats[user];
    }
    
    function getProposal(uint256 proposalId) external view returns (Proposal memory) {
        return proposals[proposalId];
    }
    
    function isVerifiedCreator(address user) external view returns (bool) {
        return verifiedCreators[user];
    }

    // Override required functions
    function _beforeTokenTransfer(address from, address to, uint256 amount)
        internal
        whenNotPaused
        override(ERC20, ERC20Snapshot)
    {
        super._beforeTokenTransfer(from, to, amount);
    }

    function _afterTokenTransfer(address from, address to, uint256 amount)
        internal
        override(ERC20, ERC20Votes)
    {
        super._afterTokenTransfer(from, to, amount);
    }

    function _mint(address to, uint256 amount)
        internal
        override(ERC20, ERC20Votes)
    {
        super._mint(to, amount);
    }

    function _burn(address account, uint256 amount)
        internal
        override(ERC20, ERC20Votes)
    {
        super._burn(account, amount);
    }
}