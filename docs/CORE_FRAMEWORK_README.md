# Core Framework

> A clean, simple Laravel-based framework for building modern web applications

## 🚀 Quick Start

### Installation
```bash
# Clone and setup
git clone <repository-url>
cd core-framework
composer install
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate --seed

# Start development server
php artisan serve
```

### Access Points
- **Website**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin
- **Setup Wizard**: http://localhost:8000/setup

## 📋 Core Features

### Content Management
- ✅ Posts & Categories system
- ✅ SEO optimization built-in
- ✅ Image management with WebP conversion
- ✅ Responsive design with Tailwind CSS

### Admin Interface
- ✅ Modern Filament admin panel
- ✅ Easy content management
- ✅ File uploads with optimization
- ✅ User management

### Developer Features
- ✅ Clean, maintainable code structure
- ✅ Component-based frontend
- ✅ Laravel Actions pattern
- ✅ Comprehensive error handling

## 🛠️ Configuration

### Environment Setup
```env
APP_NAME="Your App Name"
APP_URL=http://localhost

DB_DATABASE=corelaravel
DB_USERNAME=root
DB_PASSWORD=

# Optional customization
SITE_NAME="Your Site"
SITE_DESCRIPTION="Your description"
```

### Database
Uses MySQL by default. The framework includes:
- Posts and categories
- User management
- Site settings
- SEO optimization

## 📁 Project Structure

```
app/
├── Actions/           # Laravel Actions
├── Http/Controllers/  # Route controllers
├── Livewire/         # Livewire components
├── Models/           # Eloquent models
└── Filament/         # Admin resources

resources/views/
├── components/       # Reusable components
├── layouts/         # Page layouts
├── posts/           # Post templates
└── shop/            # Main pages

routes/
└── web.php          # Application routes
```

## 🎨 Customization

### Adding Content Types
1. Create model: `php artisan make:model YourModel -m`
2. Add Filament resource: `php artisan make:filament-resource YourModel`
3. Create views in `resources/views/`
4. Add routes in `routes/web.php`

### Styling
- **CSS**: `resources/css/app.css`
- **Tailwind**: `tailwind.config.js`
- **Components**: `resources/views/components/`

## 🚀 Deployment

### Production Setup
```bash
# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 755 storage bootstrap/cache
```

### Environment
- Set `APP_ENV=production`
- Configure proper database credentials
- Set up SSL certificate
- Configure web server (Apache/Nginx)

## 🔧 Development

### Code Standards
- Follow Laravel conventions
- Use meaningful names
- Keep methods focused
- Comment complex logic

### Testing
```bash
php artisan test
```

### Common Commands
```bash
# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Database
php artisan migrate:fresh --seed
php artisan tinker

# Assets
npm run dev
npm run build
```

## 📚 Documentation

- **Setup Guide**: `docs/SETUP_GUIDE.md`
- **Routes**: `docs/ROUTES_CUSTOMIZATION.md`
- **Environment**: `docs/ENV_CUSTOMIZATION.md`

## 🆘 Troubleshooting

### Common Issues
- **500 Error**: Check `storage/logs/laravel.log`
- **Database**: Verify `.env` settings
- **Permissions**: Ensure `storage/` is writable
- **Assets**: Run `npm run build`

### Getting Help
1. Check error logs
2. Review documentation
3. Verify environment configuration
4. Test with fresh installation

## 📄 License

MIT License - feel free to use for personal and commercial projects.
