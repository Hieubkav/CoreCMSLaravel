# Changelog

Tất cả các thay đổi quan trọng của Core Framework sẽ được ghi lại trong file này.

Định dạng dựa trên [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
và dự án này tuân theo [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Planned
- Multi-language support
- Advanced theme customization
- Plugin system
- API authentication

---

## [2.2.0] - 2024-01-20 - Complete Core Framework Cleanup

### 🧹 Major Cleanup & Generalization
- **Complete Brand Removal**: Removed all traces of previous project branding and specific content
- **Database Cleanup**: Updated database name to `corelaravel` and cleaned all seeders
- **Content Generalization**: Replaced 307+ specific references across 47 view files
- **Storage Cleanup**: Removed all project-specific images and assets
- **Migration Updates**: Cleaned migration files from project-specific default values

### 🔄 Seeders Completely Rewritten
- **CourseSeeder**: Generic development courses instead of specific content
- **InstructorSeeder**: Generic instructor profiles
- **SettingSeeder**: Core Framework branding and generic contact info
- **All Other Seeders**: Cleaned and generalized for any project type

### 📁 Files Cleaned
- **Commands**: OptimizeProject.php, ResetWebDesignCommand.php
- **Notifications**: DashboardWelcomeNotification.php
- **Views**: 47 blade files with 307 content replacements
- **Migrations**: 2 migration files with default values updated
- **Storage**: Removed all project-specific assets
- **Composer**: Updated package description and keywords

### 🎯 Now Truly Generic
- **Zero Project-Specific Content**: No traces of previous project remain
- **Ready for Any Project**: Can be used as foundation for any Laravel application
- **Clean Database**: `corelaravel` database name, generic sample data
- **Generic Branding**: "Core Framework" throughout the application
- **Flexible Content**: All content can be easily customized via admin panel

---

## [2.1.0] - 2024-01-20 - Laravel Actions Refactor

### 🚀 Major Refactor
- **Complete Laravel Actions Integration**: Refactored entire codebase to use Laravel Actions following KISS principle
- **Removed Complex Services & Traits**: Eliminated all complex services and traits in favor of simple Actions
- **Simplified Architecture**: Each Action does one thing well, making code easier to understand and maintain

### ✨ New Actions Created
- **Image Processing**: `ConvertImageToWebp`, `CreateSeoImageName`, `GetImageDirectory`, `CreateFilamentImageUpload`
- **File Management**: `DeleteFileFromStorage`, `ClearViewCache`
- **Setup System**: `SetupDatabase`, `CreateAdminUser`, `SaveWebsiteSettings`, `SaveAdvancedConfiguration`, `ImportSampleData`
- **Statistics**: `GetVisitorStats`
- **Development Tools**: `CreateModelObserver`

### 🗑️ Removed (Replaced with Actions)
- **Services**: `SimpleWebpService`, `VisitorStatsService`, `SeoImageService`
- **Traits**: `ClearsViewCache`, `HandlesFileObserver`, `HasImageUpload`, `BroadcastsModelChanges`, `HandlesProductImages`
- **Complex Dependencies**: Removed dependency injection in favor of static method calls

### 🔧 Improvements
- **70% Code Reduction**: Simplified Observers and Filament Resources significantly
- **Better Performance**: Static method calls instead of dependency injection
- **Easier Testing**: Each Action can be tested independently
- **Better Developer Experience**: Improved autocomplete and IDE support

### 🐛 Bug Fixes
- **CSS Conflict**: Fixed Tailwind CSS conflict between 'flex' and 'hidden' classes in setup layout
- **Trait References**: Removed all references to deleted traits from models
- **Setup Wizard**: Fixed loading overlay display issues

---

## [2.0.0] - 2024-01-20 - Core Framework Release

### 🚀 Major Changes
- **Converted to Core Framework**: Transformed VBA Vũ Phúc project into reusable core framework
- **Setup Wizard**: Added comprehensive 5-step setup wizard for new projects
- **Modular Architecture**: Restructured codebase for better reusability and extensibility

### ✨ Added
- **Setup System**: Complete setup wizard with database, admin, website, configuration, and sample data steps
- **Configuration Management**: Advanced configuration page for performance and feature settings
- **Environment Template**: Structured .env.example with clear sections and comments
- **Documentation**: Comprehensive guides for setup, development, and usage
- **Core Framework Guide**: Detailed documentation for using framework in new projects
- **Setup Guide**: Step-by-step installation and configuration instructions

### 🔧 Changed
- **Project Structure**: Reorganized for framework reusability
- **Database Seeder**: Updated to work with core framework approach
- **README**: Updated to reflect core framework purpose and installation
- **Routes**: Added setup routes and cleaned up project-specific routes
- **Sitemap**: Updated to use Course and Instructor instead of Product and Employee

### 🗑️ Removed
- **Product System**: Removed Product, CatProduct models and related functionality
- **Employee System**: Removed Employee model and QR code features
- **VBA-specific Features**: Removed project-specific customizations
- **Unused Livewire Components**: Removed ProductsFilter, CartIcon, UserAccount
- **Unused Commands**: Removed CreateSampleData, CreateTestOrders, OptimizeStorefront
- **Unused Resources**: Removed EmployeeResource, ProductResource, ProductCategoryResource

### 🛠️ Technical Improvements
- **Clean Architecture**: Removed dependencies on removed models
- **Cache Management**: Updated ViewServiceProvider to work with new model structure
- **Error Handling**: Improved error handling in setup process
- **Performance**: Optimized for better performance and scalability

## [1.0.0] - 2024-01-15

### Added
- Khởi tạo dự án Laravel 10 với Filament Admin Panel
- Tích hợp Livewire cho real-time updates
- Hệ thống quản lý bài viết với SEO tự động
- Hệ thống quản lý nhân viên với QR code
- Tối ưu hóa hình ảnh tự động (WebP conversion)
- Responsive design với Tailwind CSS
- Dashboard với thống kê real-time
- Hệ thống tìm kiếm nâng cao
- Observer pattern cho file management
- Multi-language support (Tiếng Việt)

### Features
- **Admin Panel**: Quản trị toàn diện với Filament
- **SEO Optimization**: Tự động tạo meta tags và sitemap
- **Image Processing**: Chuyển đổi ảnh sang WebP
- **QR Code Integration**: Tạo QR code cho nhân viên
- **Real-time Dashboard**: Cập nhật thống kê thời gian thực
- **Responsive Design**: Tối ưu cho mọi thiết bị
- **Search System**: Tìm kiếm thông minh với suggestions

### Technical
- Laravel 10.x
- Filament 3.x
- Livewire 3.x
- Tailwind CSS
- Alpine.js
- MySQL/PostgreSQL
- Redis (optional)

---

## Quy tắc Changelog

### Types of changes
- `Added` cho các tính năng mới
- `Changed` cho thay đổi trong tính năng hiện có
- `Deprecated` cho tính năng sẽ bị loại bỏ
- `Removed` cho tính năng đã bị loại bỏ
- `Fixed` cho bug fixes
- `Security` cho các vấn đề bảo mật

### Format
```markdown
## [Version] - YYYY-MM-DD

### Added
- Tính năng mới được thêm

### Changed
- Thay đổi trong tính năng hiện có

### Fixed
- Bug được sửa
```
