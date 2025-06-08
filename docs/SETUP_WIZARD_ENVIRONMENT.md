# Setup Wizard Environment Logic

## 🎯 Overview

Setup Wizard của Core Framework có logic thông minh để xử lý việc truy cập dựa trên môi trường (environment):

- **Local Environment**: Luôn có thể truy cập setup wizard
- **Production Environment**: Chỉ có thể truy cập khi chưa setup

## 🔧 Logic Implementation

### SetupController Logic

```php
public function index(Request $request)
{
    // Trong môi trường local, luôn cho phép truy cập setup wizard
    if (app()->environment('local')) {
        return view('setup.index');
    }
    
    // Trong production, chỉ cho phép khi chưa setup
    if ($this->isSetupCompleted()) {
        return redirect()->route('storeFront')->with('info', 'Hệ thống đã được cài đặt.');
    }

    return view('setup.index');
}

public function step($step)
{
    // Trong production, kiểm tra xem đã setup chưa
    if (!app()->environment('local') && $this->isSetupCompleted()) {
        return redirect()->route('storeFront');
    }
    
    // Continue with step logic...
}
```

### Setup Detection

```php
private function isSetupCompleted()
{
    try {
        // Kiểm tra xem có settings record nào với site_name không
        return Setting::whereNotNull('site_name')->exists();
    } catch (Exception $e) {
        return false;
    }
}
```

## 🌍 Environment Behaviors

### Local Environment (`APP_ENV=local`)

**Characteristics:**
- ✅ Setup wizard luôn accessible tại `/setup`
- ✅ Tất cả setup steps luôn accessible
- ✅ Không bị redirect dù đã có settings
- ✅ Hiển thị thông báo "Development Mode" trong UI
- ✅ Perfect cho development và testing

**Use Cases:**
- Development và debugging
- Testing setup wizard functionality
- Re-configuring settings
- Training và demo

### Production Environment (`APP_ENV=production`)

**Characteristics:**
- ✅ Setup wizard chỉ accessible khi chưa có settings
- ❌ Redirect về homepage nếu đã setup
- ✅ Bảo mật cao, không cho phép re-setup
- ✅ Ngăn chặn truy cập trái phép

**Use Cases:**
- Live websites
- Production deployments
- Security-focused environments

## 🚀 Usage Examples

### Development Workflow

```bash
# 1. Set local environment
APP_ENV=local

# 2. Access setup wizard anytime
http://localhost:8000/setup
# → Always accessible

# 3. Test different configurations
# → Can re-run setup multiple times
```

### Production Deployment

```bash
# 1. Set production environment
APP_ENV=production

# 2. First deployment (no settings)
http://yoursite.com/setup
# → Accessible for initial setup

# 3. After setup completion
http://yoursite.com/setup
# → Redirects to homepage (blocked)
```

## 🔄 Environment Switching

### Switch to Production Mode

```bash
# 1. Update .env
APP_ENV=production

# 2. Clear config cache
php artisan config:clear

# 3. Test setup access
curl -I http://localhost:8000/setup
# → Should redirect if settings exist
```

### Switch to Local Mode

```bash
# 1. Update .env
APP_ENV=local

# 2. Clear config cache
php artisan config:clear

# 3. Test setup access
curl -I http://localhost:8000/setup
# → Should return 200 (always accessible)
```

## 🛡️ Security Benefits

### Production Protection
- **Prevents unauthorized reconfiguration**
- **Blocks access to sensitive setup steps**
- **Maintains system integrity**
- **Follows security best practices**

### Development Flexibility
- **Easy testing and debugging**
- **Quick configuration changes**
- **No restrictions for development**
- **Streamlined workflow**

## 📋 Best Practices

### For Developers
1. **Always use local environment** for development
2. **Test production behavior** before deployment
3. **Clear config cache** when switching environments
4. **Document environment-specific behaviors**

### For Deployment
1. **Set production environment** in deployment scripts
2. **Run setup wizard** on first deployment
3. **Verify setup blocking** after completion
4. **Monitor setup access attempts**

## 🔍 Troubleshooting

### Setup Wizard Not Accessible in Production

**Problem**: Setup wizard redirects even when not setup
**Solution**: 
```bash
# Check if settings exist
php artisan tinker --execute="echo Setting::whereNotNull('site_name')->count();"

# If settings exist but shouldn't, clear them
php artisan tinker --execute="Setting::truncate();"
```

### Setup Wizard Always Redirects in Local

**Problem**: Setup wizard redirects in local environment
**Solution**:
```bash
# Check current environment
php artisan tinker --execute="echo app()->environment();"

# Should return 'local', if not, update .env
APP_ENV=local
php artisan config:clear
```

## 🎉 Summary

Environment-based setup wizard logic provides:
- **Security** in production
- **Flexibility** in development  
- **Smart detection** of setup status
- **Seamless user experience**

Perfect balance between security and usability! 🚀
