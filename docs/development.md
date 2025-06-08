# Hướng dẫn Phát triển

Tài liệu này cung cấp hướng dẫn chi tiết cho việc phát triển dự án Vũ Phúc.

## 🏗️ Kiến trúc Dự án

### Tech Stack
- **Backend**: Laravel 10.x
- **Admin Panel**: Filament 3.x
- **Frontend**: Livewire 3.x + Alpine.js
- **CSS Framework**: Tailwind CSS
- **Database**: MySQL/PostgreSQL
- **Image Processing**: Intervention Image

### Design Patterns
- **MVC**: Model-View-Controller
- **Observer Pattern**: Tự động xử lý file khi CRUD
- **Service Provider**: Tích hợp dữ liệu global
- **Repository Pattern**: Tách biệt logic database

## 📁 Cấu trúc Code

### Models
```
app/Models/
├── User.php              # Người dùng
├── Course.php            # Khóa học
├── Student.php           # Học viên
├── Post.php              # Bài viết
├── Category.php          # Danh mục
├── Setting.php           # Cài đặt
└── Slider.php            # Banner slider
```

### Livewire Components
```
app/Livewire/
├── HomePage.php          # Trang chủ
├── CourseList.php        # Danh sách khóa học
├── PostList.php          # Danh sách bài viết
└── SearchComponent.php   # Tìm kiếm
```

### Filament Resources
```
app/Filament/Resources/
├── CourseResource.php    # Quản lý khóa học
├── StudentResource.php   # Quản lý học viên
├── PostResource.php      # Quản lý bài viết
├── CategoryResource.php  # Quản lý danh mục
└── SettingResource.php   # Quản lý cài đặt
```

## 🔧 Quy trình Phát triển

### 1. Tạo Model mới
```bash
# Tạo model với migration
php artisan make:model ModelName -m

# Tạo với factory và seeder
php artisan make:model ModelName -mfs
```

### 2. Tạo Filament Resource
```bash
php artisan make:filament-resource ModelName --generate
```

### 3. Tạo Livewire Component
```bash
php artisan make:livewire ComponentName
```

### 4. Tạo Observer
```bash
php artisan make:observer ModelNameObserver --model=ModelName
```

## 📝 Coding Standards

### PHP (PSR-12)
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExampleModel extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
    ];
}
```

### Blade Templates
```blade
{{-- Sử dụng components --}}
<x-layout.app>
    <x-slot name="title">{{ $title }}</x-slot>
    
    <div class="container mx-auto px-4">
        @if($items->isNotEmpty())
            @foreach($items as $item)
                <x-card :item="$item" />
            @endforeach
        @else
            <x-empty-state message="Không có dữ liệu" />
        @endif
    </div>
</x-layout.app>
```

### Livewire Components
```php
<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class ExampleComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.example-component', [
            'items' => $this->getItems(),
        ]);
    }

    private function getItems()
    {
        return Model::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->paginate($this->perPage);
    }
}
```

## 🎨 Frontend Guidelines

### Tailwind CSS Classes
```html
<!-- Container patterns -->
<div class="container mx-auto px-4 py-8">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

<!-- Grid layouts -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

<!-- Responsive design -->
<div class="block md:hidden">Mobile only</div>
<div class="hidden md:block">Desktop only</div>
```

### Component Structure
```blade
{{-- resources/views/components/card.blade.php --}}
@props(['title', 'description', 'image' => null])

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-md overflow-hidden']) }}>
    @if($image)
        <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-48 object-cover">
    @endif
    
    <div class="p-6">
        <h3 class="text-xl font-semibold mb-2">{{ $title }}</h3>
        <p class="text-gray-600">{{ $description }}</p>
    </div>
</div>
```

## 🔍 Testing

### Feature Tests
```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class ExampleTest extends TestCase
{
    public function test_user_can_view_homepage()
    {
        $response = $this->get('/');
        
        $response->assertStatus(200);
        $response->assertSee('Vũ Phúc');
    }

    public function test_admin_can_create_post()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->post('/admin/posts', [
                'title' => 'Test Post',
                'content' => 'Test content',
            ]);
            
        $response->assertRedirect();
        $this->assertDatabaseHas('posts', ['title' => 'Test Post']);
    }
}
```

### Chạy Tests
```bash
# Chạy tất cả tests
php artisan test

# Chạy test cụ thể
php artisan test --filter ExampleTest

# Chạy với coverage
php artisan test --coverage
```

## 🚀 Deployment

### Build Production
```bash
# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Build assets
npm run build
```

### Environment Variables
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://vuphuc.com

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=vuphuc_prod

# Cache
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

## 📚 Tài liệu tham khảo

- [Laravel Documentation](https://laravel.com/docs)
- [Filament Documentation](https://filamentphp.com/docs)
- [Livewire Documentation](https://livewire.laravel.com/docs)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
