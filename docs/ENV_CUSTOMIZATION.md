# ⚙️ Core Framework - Environment Configuration Guide

## 📋 Tổng quan

File `.env.example` trong Core Framework được thiết kế để dễ dàng tùy chỉnh cho các dự án khác nhau. Đây là hướng dẫn chi tiết để cấu hình environment variables theo nhu cầu dự án.

## 🎯 Cấu hình cơ bản

### 1. App Configuration
```env
APP_NAME="My Project"          # Tên dự án của bạn
APP_ENV=local                  # local, staging, production
APP_DEBUG=true                 # true cho development, false cho production
APP_URL=http://localhost       # URL của website
```

### 2. Database Configuration
```env
DB_CONNECTION=mysql            # mysql, pgsql, sqlite, sqlsrv
DB_HOST=127.0.0.1             # Database host
DB_PORT=3306                  # Database port
DB_DATABASE=my_project_db     # Tên database
DB_USERNAME=root              # Username
DB_PASSWORD=                  # Password
```

### 3. Core Framework Settings
```env
# Setup Configuration
SETUP_COMPLETED=false         # Đánh dấu đã hoàn thành setup

# Image Processing
IMAGE_WEBP_QUALITY=95         # Chất lượng WebP (50-100)
IMAGE_MAX_WIDTH=1920          # Chiều rộng tối đa (px)
IMAGE_MAX_HEIGHT=1080         # Chiều cao tối đa (px)

# SEO Configuration
SEO_AUTO_GENERATE=true        # Tự động tạo meta tags
SEO_DEFAULT_DESCRIPTION="Powered by Core Framework"
```

## 🔧 Templates cho các loại dự án

### E-commerce Project
```env
APP_NAME="My Online Store"
APP_URL=https://mystore.com

# E-commerce specific
SHOP_CURRENCY=USD
SHOP_TAX_RATE=0.1
SHOP_SHIPPING_COST=10.00
PAYMENT_GATEWAY=stripe
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...

# Inventory
INVENTORY_TRACKING=true
LOW_STOCK_THRESHOLD=10

# SEO for E-commerce
SEO_DEFAULT_DESCRIPTION="Quality products at great prices"
```

### Blog/News Website
```env
APP_NAME="My Blog"
APP_URL=https://myblog.com

# Blog specific
BLOG_POSTS_PER_PAGE=10
BLOG_EXCERPT_LENGTH=150
BLOG_ENABLE_COMMENTS=true
BLOG_MODERATION=true

# Social Media
SOCIAL_SHARING=true
FACEBOOK_APP_ID=your_facebook_app_id
TWITTER_HANDLE=@yourblog

# SEO for Blog
SEO_DEFAULT_DESCRIPTION="Latest news and insights"
```

### Educational Platform
```env
APP_NAME="Learning Academy"
APP_URL=https://academy.com

# Education specific
COURSE_ENROLLMENT_LIMIT=100
COURSE_CERTIFICATE_ENABLED=true
COURSE_PROGRESS_TRACKING=true
COURSE_DISCUSSION_ENABLED=true

# Video Settings
VIDEO_STREAMING_PROVIDER=vimeo
VIMEO_ACCESS_TOKEN=your_vimeo_token

# SEO for Education
SEO_DEFAULT_DESCRIPTION="Learn new skills with expert instructors"
```

### Portfolio Website
```env
APP_NAME="John Doe Portfolio"
APP_URL=https://johndoe.com

# Portfolio specific
PORTFOLIO_PROJECTS_PER_PAGE=12
PORTFOLIO_ENABLE_CONTACT_FORM=true
PORTFOLIO_SHOW_CLIENT_LOGOS=true

# Contact Form
CONTACT_FORM_EMAIL=john@johndoe.com
CONTACT_FORM_SUBJECT="New Portfolio Inquiry"

# SEO for Portfolio
SEO_DEFAULT_DESCRIPTION="Creative designer and developer"
```

### Corporate Website
```env
APP_NAME="ABC Corporation"
APP_URL=https://abccorp.com

# Corporate specific
COMPANY_FOUNDED_YEAR=2020
COMPANY_EMPLOYEES_COUNT=50
COMPANY_LOCATIONS=3

# Services
SERVICES_CONSULTATION=true
SERVICES_SUPPORT_24_7=true

# SEO for Corporate
SEO_DEFAULT_DESCRIPTION="Leading solutions provider"
```

## 📧 Email Configuration Templates

### Gmail SMTP
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Mailgun
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.mailgun.org
MAILGUN_SECRET=your-mailgun-secret
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### SendGrid
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## 🚀 Production Configuration

### Security Settings
```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:your-32-character-secret-key

# Session Security
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict

# HTTPS
FORCE_HTTPS=true
```

### Performance Settings
```env
# Cache
CACHE_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Queue
QUEUE_CONNECTION=redis

# Session
SESSION_DRIVER=redis

# Filament Optimization
FILAMENT_QUERY_CACHE=true
FILAMENT_CACHE_DURATION=600
FILAMENT_EAGER_LOADING=true
FILAMENT_MEMORY_OPTIMIZATION=true
FILAMENT_ASSET_OPTIMIZATION=true
FILAMENT_CACHE_STORE=redis
```

### Database Optimization
```env
# Database Connection Pool
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=your_production_db
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password

# Connection Settings
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci
DB_STRICT=true
```

## 🔐 Security Best Practices

### 1. Environment Variables Security
```env
# Never commit these to version control
APP_KEY=base64:...
DB_PASSWORD=...
MAIL_PASSWORD=...
API_KEYS=...
```

### 2. Production Security
```env
# Always use HTTPS in production
APP_URL=https://yourdomain.com
FORCE_HTTPS=true

# Secure session settings
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
```

### 3. API Keys Management
```env
# Use separate keys for different environments
STRIPE_KEY=pk_live_...     # Production
STRIPE_KEY=pk_test_...     # Development

GOOGLE_ANALYTICS_ID=UA-... # Production
GOOGLE_ANALYTICS_ID=       # Development (empty)
```

## 📊 Monitoring & Logging

### Error Tracking
```env
# Sentry
SENTRY_LARAVEL_DSN=https://...@sentry.io/...

# Bugsnag
BUGSNAG_API_KEY=your-bugsnag-api-key
```

### Analytics
```env
# Google Analytics
GOOGLE_ANALYTICS_ID=UA-XXXXXXXX-X

# Facebook Pixel
FACEBOOK_PIXEL_ID=your-pixel-id

# Google Tag Manager
GTM_CONTAINER_ID=GTM-XXXXXXX
```

## 🛠️ Development Tools

### Local Development
```env
# Laravel Telescope
TELESCOPE_ENABLED=true

# Laravel Debugbar
DEBUGBAR_ENABLED=true

# Mail Testing
MAIL_MAILER=log  # Logs emails instead of sending
```

### Testing Environment
```env
APP_ENV=testing
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
CACHE_DRIVER=array
SESSION_DRIVER=array
QUEUE_CONNECTION=sync
```

---

**Core Framework** - Flexible configuration for any environment! ⚙️
