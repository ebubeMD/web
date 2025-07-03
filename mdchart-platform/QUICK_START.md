# 🚀 Mdchart Quick Start Guide

## Get Your Ultimate Social Media Platform Running in 5 Minutes!

**Built by:** Ebube Eze  
**Supreme God Mode Edition**

---

## ⚡ Super Quick Installation

### Prerequisites Check
Make sure you have:
- ✅ PHP 8.1+ with MySQL extension
- ✅ MySQL 8.0+
- ✅ Apache with mod_rewrite
- ✅ Node.js 16+

### 1. Download & Install

```bash
# Clone the repository
git clone https://github.com/mdchart/mdchart-platform.git
cd mdchart-platform

# Run the automated installer
php install.php
```

### 2. Follow the Installer Prompts

The installer will ask you for:
- **Database Host:** (usually `localhost`)
- **Database Name:** (e.g., `mdchart_supreme`)
- **Database Username:** (e.g., `root`)
- **Database Password:** (your MySQL password)
- **Admin Email:** (your admin email)
- **Admin Password:** (strong password, min 8 chars)

### 3. Access Your Platform

After installation:
- **Main Site:** `http://localhost` or your domain
- **Admin Panel:** `http://localhost/admin`
- **Black Console:** `http://localhost/admin/console`

---

## 🎯 What You Get Out of the Box

### 📱 **User Features**
- Complete user registration and profiles
- Post creation with media, polls, and events
- Real-time messaging and chat
- Groups and pages management
- Stories and live streaming
- Advanced privacy controls

### 👥 **Social Features**
- Friends and followers system
- Reactions and comments
- Real-time notifications
- Activity feeds
- Search and discovery

### 💼 **Business Features**
- Business pages with analytics
- Event management
- Marketplace integration
- Promotional tools
- Insights dashboard

### 🛡️ **Admin Features**
- User management dashboard
- Content moderation tools
- **Mdchart Black Console** (AI Terminal)
- Real-time analytics
- System monitoring

---

## 🔧 Essential Configuration

### Enable HTTPS (Recommended)
```bash
sudo certbot --apache -d your-domain.com
```

### Configure Email (Required for notifications)
Edit `mdchart-backend/.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-server
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
```

### Setup Redis (Recommended for performance)
```bash
sudo apt install redis-server
```

Update `.env`:
```env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

---

## 🎮 Getting Started as Admin

### 1. Access Admin Panel
Navigate to `http://your-domain.com/admin` and login with your admin credentials.

### 2. Configure Basic Settings
- **Site Settings:** Set your site name, description, logo
- **User Settings:** Configure registration, verification
- **Content Settings:** Set upload limits, content policies

### 3. Create Sample Content
- Create a few test users
- Post some sample content
- Set up example groups and pages

### 4. Try the Black Console
Navigate to `http://your-domain.com/admin/console` for the terminal-style interface:
```
mdchart> show users --limit 10
mdchart> moderate content --pending
mdchart> system status
mdchart> help
```

---

## 👥 Getting Started as User

### 1. Register Your Account
- Visit the main site
- Click "Sign Up"
- Fill in your details
- Verify your email

### 2. Complete Your Profile
- Upload profile picture
- Add cover photo
- Write your bio
- Set privacy preferences

### 3. Start Connecting
- Search for friends
- Join groups
- Follow pages
- Create your first post

### 4. Explore Features
- Try messaging
- Create a story
- Join a group
- Attend an event

---

## 🔥 Advanced Quick Setup

### Real-time Features
```bash
cd mdchart-realtime
npm install
npm start
```

### Queue Worker (for background jobs)
```bash
cd mdchart-backend
php artisan queue:work --daemon
```

### Scheduled Tasks
Add to crontab:
```bash
* * * * * cd /path/to/mdchart-backend && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🚨 Troubleshooting Quick Fixes

### Site Not Loading?
```bash
# Check Apache status
sudo systemctl status apache2

# Restart Apache
sudo systemctl restart apache2

# Check permissions
sudo chown -R www-data:www-data mdchart-backend/storage
```

### Database Connection Issues?
```bash
# Test MySQL connection
mysql -u username -p

# Check if database exists
mysql -u username -p -e "SHOW DATABASES;"
```

### Permission Errors?
```bash
# Fix Laravel permissions
cd mdchart-backend
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

---

## 📚 Next Steps

### Customize Your Platform
- **Themes:** Customize colors, fonts, layout
- **Plugins:** Add custom functionality
- **Integrations:** Connect with third-party services

### Scale Your Platform
- **CDN:** Configure CloudFlare or AWS CloudFront
- **Load Balancing:** Set up multiple servers
- **Monitoring:** Add performance monitoring

### Monetize Your Platform
- **Advertising:** Enable ad system
- **Premium Features:** Subscription model
- **Marketplace:** E-commerce integration

---

## 💡 Pro Tips

### Performance
- Enable OPcache in PHP
- Use Redis for caching
- Optimize images automatically
- Enable gzip compression

### Security
- Always use HTTPS
- Regular security updates
- Enable 2FA for admins
- Monitor suspicious activity

### User Experience
- Mobile-first design
- Fast loading times
- Intuitive navigation
- Regular feature updates

---

## 🆘 Need Help?

### Quick Solutions
- **Installation Issues:** Check [INSTALLATION.md](INSTALLATION.md)
- **Admin Questions:** Check admin panel help section
- **User Guide:** Built-in help in the platform

### Community Support
- **Discord:** [Join our community](https://discord.gg/mdchart)
- **Forum:** [Ask questions](https://forum.mdchart.com)
- **GitHub:** [Report issues](https://github.com/mdchart/issues)

### Professional Support
- **Email:** support@mdchart.com
- **Enterprise:** enterprise@mdchart.com

---

## 🎉 Congratulations!

You now have the most powerful social media platform running! 

**What's unique about Mdchart:**
- 🏆 More features than Facebook + Instagram + Twitter combined
- 🔒 Military-grade security and privacy
- ⚡ Lightning-fast performance
- 🎨 Beautiful, modern interface
- 🛠️ Powerful admin tools including Black Console
- 📈 Advanced analytics and insights
- 🌐 Mobile-optimized PWA
- 🤖 AI-powered features

---

**Built with ❤️ by Ebube Eze**

*Welcome to the future of social media!* 🚀✨