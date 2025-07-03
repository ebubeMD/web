# Mdchart Installation Guide

## 🚀 The Ultimate Social Media Platform - Supreme God Mode

**Built by:** Ebube Eze  
**Version:** 1.0.0 - Supreme Edition

---

## ✨ Features Overview

Mdchart is the most powerful and comprehensive social media platform ever created, featuring:

### 🎯 Core Social Features
- **Advanced User Profiles** - Rich profiles with verification badges
- **Supreme Posts (v002)** - Text, media, polls, events, products with advanced privacy
- **Dynamic Comments (v003)** - Nested threaded comments with reactions
- **Smart Groups (v004)** - Public, private, secret groups with admin controls
- **Business Pages (v005)** - Professional pages with insights and analytics
- **Real-time Messaging (v006)** - End-to-end encrypted chat with media support

### 🔥 Advanced Features
- **Stories & Highlights** - Instagram-style disappearing content
- **Live Streaming** - Real-time video broadcasting
- **Events System** - Comprehensive event planning and management
- **Marketplace** - Built-in e-commerce with escrow payments
- **Voice & Video Calls** - WebRTC-powered calling system
- **AI-Powered Feed** - Smart content recommendation engine

### 👨‍💼 Admin Features
- **Comprehensive Dashboard** - Real-time analytics and insights
- **Mdchart Black Console** - Terminal-style admin interface
- **Advanced Moderation** - AI-assisted content moderation
- **User Management** - Complete user lifecycle management
- **Real-time Monitoring** - Live system health and performance

### 🔒 Security & Privacy
- **Military-grade Encryption** - AES-256 for all sensitive data
- **Two-Factor Authentication** - SMS and authenticator app support
- **End-to-end Messaging** - Encrypted private communications
- **Advanced Privacy Controls** - Granular privacy settings
- **IP-based Anomaly Detection** - Automated threat response

---

## 📋 System Requirements

### Minimum Requirements
- **OS:** Linux, macOS, or Windows
- **PHP:** 8.1 or higher
- **MySQL:** 8.0 or higher
- **Apache:** 2.4 or higher
- **Node.js:** 16.x or higher
- **Memory:** 2GB RAM
- **Storage:** 10GB free space

### Recommended Requirements
- **OS:** Ubuntu 20.04+ or CentOS 8+
- **PHP:** 8.2+
- **MySQL:** 8.0+
- **Apache:** 2.4+ with mod_rewrite
- **Node.js:** 18.x+
- **Memory:** 8GB RAM
- **Storage:** 50GB SSD
- **SSL Certificate** for HTTPS

### Required PHP Extensions
- mysqli, pdo, openssl, mbstring, tokenizer, xml, curl, gd, zip, bcmath

---

## 🚀 Installation Methods

### Method 1: Automated Installation (Recommended)

The easiest way to install Mdchart is using our automated installer:

```bash
# Download Mdchart
git clone https://github.com/mdchart/mdchart-platform.git
cd mdchart-platform

# Run the installer
php install.php
```

The installer will:
1. ✅ Check system requirements
2. 🗄️ Configure database settings
3. ⚙️ Setup application configuration
4. 🔧 Run database migrations
5. 👤 Create admin user
6. 🌐 Configure Apache virtual host
7. 🎉 Complete installation

### Method 2: Manual Installation

If you prefer manual installation:

#### Step 1: Download and Extract
```bash
git clone https://github.com/mdchart/mdchart-platform.git
cd mdchart-platform
```

#### Step 2: Install Backend Dependencies
```bash
cd mdchart-backend
composer install --optimize-autoloader --no-dev
```

#### Step 3: Install Frontend Dependencies
```bash
cd ../mdchart-frontend
npm install
npm run build
```

#### Step 4: Configure Environment
```bash
cd ../mdchart-backend
cp .env.example .env
php artisan key:generate
```

Edit `.env` file with your database credentials:
```env
APP_NAME="Your Mdchart Site"
APP_URL=http://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

#### Step 5: Setup Database
```bash
php artisan migrate
php artisan db:seed
```

#### Step 6: Create Admin User
```bash
php artisan mdchart:create-admin admin@example.com password123
```

#### Step 7: Configure Apache
Create virtual host configuration:
```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /path/to/mdchart-platform/mdchart-backend/public
    
    <Directory /path/to/mdchart-platform/mdchart-backend/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/mdchart_error.log
    CustomLog ${APACHE_LOG_DIR}/mdchart_access.log combined
</VirtualHost>
```

---

## 🔧 Post-Installation Configuration

### 1. SSL Certificate (Recommended)
```bash
sudo certbot --apache -d your-domain.com
```

### 2. Configure Redis (Optional but Recommended)
```bash
sudo apt install redis-server
```

Update `.env`:
```env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### 3. Configure Email
Update `.env` with your email provider:
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
```

### 4. Configure Real-time Features
For WebSocket support, install and configure:
```bash
npm install -g pm2
cd mdchart-realtime
npm install
pm2 start ecosystem.config.js
```

### 5. Configure File Storage
For production, set up cloud storage:
```env
FILESYSTEM_CLOUD=s3
AWS_ACCESS_KEY_ID=your-key
AWS_SECRET_ACCESS_KEY=your-secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket
```

---

## 🎛️ Admin Panel Access

After installation, access your admin panel:

- **Admin Dashboard:** `https://your-domain.com/admin`
- **Black Console:** `https://your-domain.com/admin/console`
- **System Monitor:** `https://your-domain.com/admin/system`

### Default Admin Credentials
- **Email:** As specified during installation
- **Password:** As specified during installation

### Admin Features
- **User Management** - View, edit, ban, promote users
- **Content Moderation** - Review reported content
- **System Analytics** - Real-time platform insights
- **Black Console** - Terminal-style system control
- **Settings** - Platform configuration

---

## 🔄 Updates and Maintenance

### Updating Mdchart
```bash
git pull origin main
cd mdchart-backend
composer install --optimize-autoloader --no-dev
php artisan migrate
php artisan config:clear
php artisan cache:clear
```

### Backup Your Data
```bash
# Database backup
mysqldump -u username -p database_name > mdchart_backup.sql

# File backup
tar -czf mdchart_files_backup.tar.gz storage/app/public
```

### Performance Optimization
```bash
# Enable OPcache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Queue workers
php artisan queue:work --daemon
```

---

## 🛠️ Troubleshooting

### Common Issues

#### Database Connection Error
- Check database credentials in `.env`
- Ensure MySQL service is running
- Verify database exists

#### Permission Issues
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

#### Apache Configuration
- Enable mod_rewrite: `sudo a2enmod rewrite`
- Restart Apache: `sudo systemctl restart apache2`

#### Memory Limit
Update `php.ini`:
```ini
memory_limit = 512M
upload_max_filesize = 50M
post_max_size = 50M
```

---

## 🌟 Advanced Configuration

### Multi-Server Setup
For high-traffic installations:
1. **Load Balancer** - Nginx or HAProxy
2. **Database Cluster** - MySQL replication
3. **File Storage** - S3 or distributed storage
4. **Caching** - Redis cluster
5. **CDN** - CloudFlare or AWS CloudFront

### Custom Themes
```bash
php artisan mdchart:make-theme CustomTheme
```

### API Integration
Mdchart provides comprehensive REST and GraphQL APIs:
- **REST API:** `https://your-domain.com/api/v1/`
- **GraphQL:** `https://your-domain.com/graphql`
- **WebSocket:** `wss://your-domain.com:6001`

---

## 📞 Support

### Documentation
- **User Guide:** `https://docs.mdchart.com`
- **API Documentation:** `https://api.mdchart.com`
- **Developer Guide:** `https://dev.mdchart.com`

### Community
- **Discord:** [Mdchart Community](https://discord.gg/mdchart)
- **GitHub Issues:** [Report Bugs](https://github.com/mdchart/mdchart-platform/issues)
- **Forum:** [Community Forum](https://forum.mdchart.com)

### Professional Support
For enterprise installations and custom development:
- **Email:** enterprise@mdchart.com
- **WhatsApp:** +1-XXX-XXX-XXXX

---

## 📄 License

Mdchart is released under the MIT License. See [LICENSE.md](LICENSE.md) for details.

---

## 🙏 Credits

**Created with ❤️ by Ebube Eze**

Special thanks to the open-source community and all contributors who made this project possible.

---

*Welcome to the future of social media! 🚀*