# 📁 Laravel Actions - Organized Structure

## 🗂️ Cấu Trúc Thư Mục

```
app/Actions/
├── Cache/                  # Cache management actions
│   └── ClearViewCache.php
├── File/                   # File handling actions
│   ├── ConvertImageToWebp.php
│   ├── CreateFilamentImageUpload.php
│   ├── CreateSeoImageName.php
│   ├── DeleteFileFromStorage.php
│   └── GetImageDirectory.php
├── Image/                  # Image processing actions
│   └── ConvertImageToWebp.php
├── Module/                 # Module management actions
│   └── CheckModuleVisibility.php
├── Setup/                  # Setup wizard actions
│   └── GenerateModuleCode.php
├── System/                 # System utilities actions
│   ├── CreateModelObserver.php
│   └── GetVisitorStats.php
└── User/                   # User management actions (empty)
```

## 📋 Chi Tiết Actions

### 🗄️ Cache Actions
- **ClearViewCache**: Clear cache cho models, thay thế ClearsViewCache trait

### 📁 File Actions
- **ConvertImageToWebp**: Convert ảnh sang WebP format với SEO filename
- **CreateFilamentImageUpload**: Tạo Filament FileUpload components với WebP conversion
- **CreateSeoImageName**: Tạo tên file SEO-friendly cho ảnh
- **DeleteFileFromStorage**: Xóa files từ storage với error handling
- **GetImageDirectory**: Lấy thư mục lưu ảnh theo type

### 🖼️ Image Actions
- **ConvertImageToWebp**: Xử lý convert ảnh sang WebP (duplicate với File/)

### 🧩 Module Actions
- **CheckModuleVisibility**: Kiểm tra module có được enable không

### ⚙️ Setup Actions
- **GenerateModuleCode**: Generate code cho modules trong setup wizard

### 🔧 System Actions
- **CreateModelObserver**: Tạo Observer cho models với file handling
- **GetVisitorStats**: Lấy thống kê visitors với cache

## 🔄 Migration Guide

### Namespace Changes
```php
// OLD
use App\Actions\ClearViewCache;
use App\Actions\ConvertImageToWebp;
use App\Actions\CheckModuleVisibility;

// NEW
use App\Actions\Cache\ClearViewCache;
use App\Actions\Image\ConvertImageToWebp;
use App\Actions\Module\CheckModuleVisibility;
```

### Updated References
```php
// OLD
ClearViewCache::forModel($model);
ConvertImageToWebp::run($file);
CheckModuleVisibility::isEnabled($module);

// NEW
\App\Actions\Cache\ClearViewCache::forModel($model);
\App\Actions\Image\ConvertImageToWebp::run($file);
\App\Actions\Module\CheckModuleVisibility::handle($module);
```

## 🎯 Benefits

### ✅ **Better Organization**
- Actions grouped by functionality
- Easier to find and maintain
- Clear separation of concerns

### ✅ **Improved Maintainability**
- Related actions in same directory
- Consistent naming conventions
- Better code discoverability

### ✅ **Scalability**
- Easy to add new action categories
- Room for growth in each category
- Modular structure

### ✅ **Developer Experience**
- Intuitive folder structure
- Clear action purposes
- Better IDE navigation

## 📝 Usage Examples

### Cache Management
```php
// Clear cache for specific model
\App\Actions\Cache\ClearViewCache::forModel($post);

// Clear all cache
\App\Actions\Cache\ClearViewCache::all();
```

### File Operations
```php
// Convert image to WebP
$webpPath = \App\Actions\Image\ConvertImageToWebp::run($uploadedFile, 'images/posts');

// Delete file from storage
\App\Actions\File\DeleteFileFromStorage::run($filePath);

// Get image directory
$directory = \App\Actions\File\GetImageDirectory::forPost();
```

### Module Management
```php
// Check if module is enabled
$isEnabled = \App\Actions\Module\CheckModuleVisibility::handle('blog');

// Get enabled modules
$modules = (new \App\Actions\Module\CheckModuleVisibility())->getEnabledModules();
```

### Setup Operations
```php
// Generate code for module
$result = \App\Actions\Setup\GenerateModuleCode::handle('blog', $config);
```

### System Utilities
```php
// Get visitor statistics
$stats = \App\Actions\System\GetVisitorStats::overview();

// Create model observer
$path = \App\Actions\System\CreateModelObserver::withFileHandling('Post', ['thumbnail']);
```

## 🚀 Next Steps

1. **Update all imports** in existing files
2. **Test all functionality** after refactoring
3. **Update documentation** where Actions are referenced
4. **Consider adding more categories** as needed (e.g., Email/, Payment/, etc.)

---

**🎉 Actions are now properly organized and ready for scalable development!**
