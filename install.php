#!/usr/bin/env php
<?php

/**
 * Mdchart - The Ultimate Social Media Platform
 * Supreme God Mode Installer
 * 
 * Author: Ebube Eze
 * Version: 1.0.0
 */

class MdchartInstaller
{
    private $config = [];
    private $colors = [
        'red' => "\033[0;31m",
        'green' => "\033[0;32m",
        'yellow' => "\033[0;33m",
        'blue' => "\033[0;34m",
        'purple' => "\033[0;35m",
        'cyan' => "\033[0;36m",
        'white' => "\033[0;37m",
        'reset' => "\033[0m",
        'bold' => "\033[1m",
    ];

    public function __construct()
    {
        $this->clearScreen();
        $this->showBanner();
    }

    public function run()
    {
        $this->welcomeMessage();
        $this->checkRequirements();
        $this->getDatabaseConfig();
        $this->getAppConfig();
        $this->setupEnvironment();
        $this->runMigrations();
        $this->createAdminUser();
        $this->setupApacheVhost();
        $this->finalizeInstallation();
    }

    private function clearScreen()
    {
        system('clear');
    }

    private function showBanner()
    {
        echo $this->colors['cyan'] . $this->colors['bold'] . "
╔══════════════════════════════════════════════════════════════════════════════╗
║                                                                              ║
║    ███╗   ███╗██████╗  ██████╗██╗  ██╗ █████╗ ██████╗ ████████╗             ║
║    ████╗ ████║██╔══██╗██╔════╝██║  ██║██╔══██╗██╔══██╗╚══██╔══╝             ║
║    ██╔████╔██║██║  ██║██║     ███████║███████║██████╔╝   ██║                ║
║    ██║╚██╔╝██║██║  ██║██║     ██╔══██║██╔══██║██╔══██╗   ██║                ║
║    ██║ ╚═╝ ██║██████╔╝╚██████╗██║  ██║██║  ██║██║  ██║   ██║                ║
║    ╚═╝     ╚═╝╚═════╝  ╚═════╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝   ╚═╝                ║
║                                                                              ║
║              The Ultimate Social Media Platform - Supreme God Mode           ║
║                           Installation Wizard v1.0                          ║
║                                                                              ║
╚══════════════════════════════════════════════════════════════════════════════╝
" . $this->colors['reset'] . "\n";
    }

    private function welcomeMessage()
    {
        echo $this->colors['green'] . "Welcome to the Mdchart Installation Wizard!\n\n" . $this->colors['reset'];
        echo $this->colors['yellow'] . "This installer will help you set up your ultimate social media platform.\n";
        echo "Please ensure you have the following ready:\n";
        echo "• MySQL database credentials\n";
        echo "• Apache web server\n";
        echo "• PHP 8.1 or higher\n\n" . $this->colors['reset'];
        
        echo "Press Enter to continue...";
        fgets(STDIN);
    }

    private function checkRequirements()
    {
        echo $this->colors['blue'] . "Checking system requirements...\n\n" . $this->colors['reset'];
        
        $requirements = [
            'PHP Version >= 8.1' => version_compare(PHP_VERSION, '8.1.0', '>='),
            'MySQL Extension' => extension_loaded('mysqli'),
            'PDO Extension' => extension_loaded('pdo'),
            'OpenSSL Extension' => extension_loaded('openssl'),
            'Mbstring Extension' => extension_loaded('mbstring'),
            'Tokenizer Extension' => extension_loaded('tokenizer'),
            'XML Extension' => extension_loaded('xml'),
            'Curl Extension' => extension_loaded('curl'),
            'GD Extension' => extension_loaded('gd'),
            'ZIP Extension' => extension_loaded('zip'),
        ];

        $allPassed = true;
        foreach ($requirements as $requirement => $passed) {
            echo sprintf(
                "%-30s %s\n",
                $requirement,
                $passed ? $this->colors['green'] . '✓ PASS' . $this->colors['reset'] 
                       : $this->colors['red'] . '✗ FAIL' . $this->colors['reset']
            );
            if (!$passed) $allPassed = false;
        }

        if (!$allPassed) {
            echo $this->colors['red'] . "\nSome requirements are not met. Please install missing extensions.\n" . $this->colors['reset'];
            exit(1);
        }

        echo $this->colors['green'] . "\n✓ All requirements met!\n\n" . $this->colors['reset'];
    }

    private function getDatabaseConfig()
    {
        echo $this->colors['purple'] . "Database Configuration\n";
        echo "====================\n\n" . $this->colors['reset'];

        $this->config['db_host'] = $this->prompt('Database Host', 'localhost');
        $this->config['db_port'] = $this->prompt('Database Port', '3306');
        $this->config['db_name'] = $this->prompt('Database Name (will be created if not exists)', 'mdchart_supreme');
        $this->config['db_username'] = $this->prompt('Database Username', 'root');
        $this->config['db_password'] = $this->promptPassword('Database Password');

        // Test database connection
        echo $this->colors['yellow'] . "Testing database connection...\n" . $this->colors['reset'];
        if (!$this->testDatabaseConnection()) {
            echo $this->colors['red'] . "Failed to connect to database. Please check your credentials.\n" . $this->colors['reset'];
            exit(1);
        }

        echo $this->colors['green'] . "✓ Database connection successful!\n\n" . $this->colors['reset'];
    }

    private function getAppConfig()
    {
        echo $this->colors['purple'] . "Application Configuration\n";
        echo "========================\n\n" . $this->colors['reset'];

        $this->config['app_name'] = $this->prompt('Application Name', 'Mdchart Supreme');
        $this->config['app_url'] = $this->prompt('Application URL', 'http://localhost');
        $this->config['admin_email'] = $this->prompt('Admin Email', 'admin@mdchart.com');
        $this->config['admin_password'] = $this->promptPassword('Admin Password (min 8 characters)');
        
        while (strlen($this->config['admin_password']) < 8) {
            echo $this->colors['red'] . "Password must be at least 8 characters.\n" . $this->colors['reset'];
            $this->config['admin_password'] = $this->promptPassword('Admin Password (min 8 characters)');
        }

        echo "\n";
    }

    private function setupEnvironment()
    {
        echo $this->colors['blue'] . "Setting up environment configuration...\n" . $this->colors['reset'];

        $envContent = "APP_NAME=\"{$this->config['app_name']}\"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL={$this->config['app_url']}
APP_TIMEZONE=UTC

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST={$this->config['db_host']}
DB_PORT={$this->config['db_port']}
DB_DATABASE={$this->config['db_name']}
DB_USERNAME={$this->config['db_username']}
DB_PASSWORD={$this->config['db_password']}

BROADCAST_CONNECTION=pusher
CACHE_STORE=redis
FILESYSTEM_DISK=local
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=\"hello@example.com\"
MAIL_FROM_NAME=\"\${APP_NAME}\"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME=\"\${APP_NAME}\"
VITE_PUSHER_APP_KEY=\"\${PUSHER_APP_KEY}\"
VITE_PUSHER_HOST=\"\${PUSHER_HOST}\"
VITE_PUSHER_PORT=\"\${PUSHER_PORT}\"
VITE_PUSHER_SCHEME=\"\${PUSHER_SCHEME}\"
VITE_PUSHER_APP_CLUSTER=\"\${PUSHER_APP_CLUSTER}\"

# Mdchart Specific Settings
MDCHART_MAX_UPLOAD_SIZE=50MB
MDCHART_ENABLE_REAL_TIME=true
MDCHART_ENABLE_AI_FEATURES=true
MDCHART_ENABLE_BLACK_CONSOLE=true
MDCHART_ENABLE_MARKETPLACE=true
MDCHART_ENABLE_STORIES=true
MDCHART_ENABLE_VIDEO_CALLS=true
";

        file_put_contents('mdchart-backend/.env', $envContent);
        
        // Generate application key
        chdir('mdchart-backend');
        exec('php artisan key:generate --force');
        chdir('..');

        echo $this->colors['green'] . "✓ Environment configured!\n\n" . $this->colors['reset'];
    }

    private function testDatabaseConnection()
    {
        try {
            $dsn = "mysql:host={$this->config['db_host']};port={$this->config['db_port']}";
            $pdo = new PDO($dsn, $this->config['db_username'], $this->config['db_password']);
            
            // Create database if it doesn't exist
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$this->config['db_name']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            
            return true;
        } catch (PDOException $e) {
            echo $this->colors['red'] . "Database Error: " . $e->getMessage() . "\n" . $this->colors['reset'];
            return false;
        }
    }

    private function runMigrations()
    {
        echo $this->colors['blue'] . "Running database migrations...\n" . $this->colors['reset'];
        
        chdir('mdchart-backend');
        exec('php artisan migrate --force', $output, $returnCode);
        
        if ($returnCode !== 0) {
            echo $this->colors['red'] . "Migration failed!\n" . $this->colors['reset'];
            exit(1);
        }
        
        echo $this->colors['green'] . "✓ Database migrated successfully!\n\n" . $this->colors['reset'];
        chdir('..');
    }

    private function createAdminUser()
    {
        echo $this->colors['blue'] . "Creating admin user...\n" . $this->colors['reset'];
        
        chdir('mdchart-backend');
        $command = sprintf(
            'php artisan mdchart:create-admin "%s" "%s"',
            $this->config['admin_email'],
            $this->config['admin_password']
        );
        exec($command, $output, $returnCode);
        
        echo $this->colors['green'] . "✓ Admin user created!\n\n" . $this->colors['reset'];
        chdir('..');
    }

    private function setupApacheVhost()
    {
        echo $this->colors['blue'] . "Setting up Apache virtual host...\n" . $this->colors['reset'];
        
        $vhostContent = "<VirtualHost *:80>
    ServerName " . parse_url($this->config['app_url'], PHP_URL_HOST) . "
    DocumentRoot " . realpath('.') . "/mdchart-backend/public
    
    <Directory " . realpath('.') . "/mdchart-backend/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog \${APACHE_LOG_DIR}/mdchart_error.log
    CustomLog \${APACHE_LOG_DIR}/mdchart_access.log combined
</VirtualHost>";

        file_put_contents('mdchart-apache-vhost.conf', $vhostContent);
        
        echo $this->colors['yellow'] . "Apache virtual host configuration saved to: mdchart-apache-vhost.conf\n";
        echo "Please copy this file to your Apache sites-available directory and enable it:\n";
        echo "sudo cp mdchart-apache-vhost.conf /etc/apache2/sites-available/mdchart.conf\n";
        echo "sudo a2ensite mdchart\n";
        echo "sudo systemctl reload apache2\n\n" . $this->colors['reset'];
    }

    private function finalizeInstallation()
    {
        echo $this->colors['green'] . $this->colors['bold'] . "
╔══════════════════════════════════════════════════════════════════════════════╗
║                                                                              ║
║                    🎉 MDCHART INSTALLATION COMPLETED! 🎉                    ║
║                                                                              ║
╚══════════════════════════════════════════════════════════════════════════════╝
" . $this->colors['reset'] . "\n";

        echo $this->colors['cyan'] . "Installation Summary:\n";
        echo "====================\n";
        echo "• Application: {$this->config['app_name']}\n";
        echo "• URL: {$this->config['app_url']}\n";
        echo "• Database: {$this->config['db_name']}\n";
        echo "• Admin Email: {$this->config['admin_email']}\n\n" . $this->colors['reset'];

        echo $this->colors['yellow'] . "Next Steps:\n";
        echo "1. Configure your Apache virtual host (see instructions above)\n";
        echo "2. Set up SSL certificate for production\n";
        echo "3. Configure Redis for caching and sessions\n";
        echo "4. Set up your email server\n";
        echo "5. Configure real-time features (Pusher/WebSockets)\n\n" . $this->colors['reset'];

        echo $this->colors['green'] . "Admin Panel: {$this->config['app_url']}/admin\n";
        echo "Black Console: {$this->config['app_url']}/admin/console\n\n" . $this->colors['reset'];

        echo $this->colors['purple'] . "Thank you for choosing Mdchart - The Supreme Social Media Platform!\n";
        echo "Built with ❤️ by Ebube Eze\n" . $this->colors['reset'];
    }

    private function prompt($question, $default = null)
    {
        $defaultText = $default ? " (default: $default)" : "";
        echo $this->colors['white'] . "$question$defaultText: " . $this->colors['reset'];
        $input = trim(fgets(STDIN));
        return $input ?: $default;
    }

    private function promptPassword($question)
    {
        echo $this->colors['white'] . "$question: " . $this->colors['reset'];
        system('stty -echo');
        $password = trim(fgets(STDIN));
        system('stty echo');
        echo "\n";
        return $password;
    }
}

// Run the installer
$installer = new MdchartInstaller();
$installer->run();