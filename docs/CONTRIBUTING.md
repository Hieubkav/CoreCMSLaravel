# Hướng dẫn Đóng góp

Cảm ơn bạn quan tâm đến việc đóng góp cho dự án Vũ Phúc! Tài liệu này sẽ hướng dẫn bạn cách đóng góp hiệu quả.

## 🤝 Cách thức Đóng góp

### 1. Báo cáo Bug
- Sử dụng GitHub Issues để báo cáo bug
- Mô tả chi tiết vấn đề và cách tái hiện
- Cung cấp thông tin môi trường (OS, PHP version, etc.)
- Đính kèm screenshot nếu có thể

### 2. Đề xuất Tính năng
- Tạo Issue với label "enhancement"
- Mô tả rõ ràng tính năng mong muốn
- Giải thích lý do cần thiết
- Đề xuất cách triển khai (nếu có)

### 3. Đóng góp Code
- Fork repository
- Tạo branch mới cho feature/bugfix
- Viết code theo coding standards
- Thêm tests cho code mới
- Tạo Pull Request

## 📋 Quy trình Pull Request

### 1. Chuẩn bị
```bash
# Fork và clone repository
git clone https://github.com/your-username/vuphuc.git
cd vuphuc

# Tạo branch mới
git checkout -b feature/ten-tinh-nang
# hoặc
git checkout -b bugfix/ten-loi
```

### 2. Development
```bash
# Cài đặt dependencies
composer install
npm install

# Chạy tests
php artisan test

# Code style check
./vendor/bin/pint
```

### 3. Commit
```bash
# Commit với message rõ ràng
git add .
git commit -m "feat: thêm tính năng tìm kiếm nâng cao"

# Push lên fork
git push origin feature/ten-tinh-nang
```

### 4. Tạo Pull Request
- Mô tả chi tiết thay đổi
- Link đến Issue liên quan
- Thêm screenshot nếu có UI changes
- Đảm bảo tests pass

## 📝 Coding Standards

### PHP Code Style
Sử dụng Laravel Pint (PSR-12):
```bash
./vendor/bin/pint
```

### Naming Conventions
```php
// Classes: PascalCase
class PostController extends Controller

// Methods: camelCase
public function createPost()

// Variables: camelCase
$userName = 'John Doe';

// Constants: UPPER_SNAKE_CASE
const MAX_UPLOAD_SIZE = 1024;

// Database tables: snake_case
posts, user_profiles, category_posts
```

### Blade Templates
```blade
{{-- Comments sử dụng blade syntax --}}

{{-- Component names: kebab-case --}}
<x-post-card :post="$post" />

{{-- CSS classes: Tailwind utility classes --}}
<div class="bg-white rounded-lg shadow-md p-6">
```

### JavaScript
```javascript
// Variables: camelCase
const userName = 'John Doe';

// Functions: camelCase
function handleSubmit() {
    // ...
}

// Constants: UPPER_SNAKE_CASE
const API_BASE_URL = '/api';
```

## 🧪 Testing Guidelines

### Feature Tests
```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;

class PostTest extends TestCase
{
    public function test_user_can_view_post_list()
    {
        Post::factory()->count(5)->create();
        
        $response = $this->get('/posts');
        
        $response->assertStatus(200);
        $response->assertViewIs('posts.index');
    }
    
    public function test_admin_can_create_post()
    {
        $admin = User::factory()->admin()->create();
        
        $response = $this->actingAs($admin)
            ->post('/admin/posts', [
                'title' => 'Test Post',
                'content' => 'Test content',
                'status' => 'published',
            ]);
            
        $response->assertRedirect();
        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
        ]);
    }
}
```

### Unit Tests
```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ImageService;

class ImageServiceTest extends TestCase
{
    public function test_can_convert_to_webp()
    {
        $service = new ImageService();
        $result = $service->convertToWebp('/path/to/image.jpg');
        
        $this->assertTrue($result);
        $this->assertFileExists('/path/to/image.webp');
    }
}
```

## 📚 Documentation

### Code Comments
```php
/**
 * Tạo bài viết mới với tối ưu SEO tự động
 *
 * @param array $data Dữ liệu bài viết
 * @return Post
 * @throws ValidationException
 */
public function createPost(array $data): Post
{
    // Validate dữ liệu đầu vào
    $validated = $this->validatePostData($data);
    
    // Tự động tạo SEO metadata nếu chưa có
    if (empty($validated['seo_title'])) {
        $validated['seo_title'] = $this->generateSeoTitle($validated['title']);
    }
    
    return Post::create($validated);
}
```

### README Updates
Khi thêm tính năng mới, cập nhật:
- README.md chính
- docs/README.md
- Tài liệu API (nếu có)

## 🔍 Code Review Process

### Reviewer Checklist
- [ ] Code tuân thủ coding standards
- [ ] Tests đầy đủ và pass
- [ ] Documentation được cập nhật
- [ ] Không có security vulnerabilities
- [ ] Performance impact được đánh giá
- [ ] UI/UX consistent với design system

### Author Checklist
- [ ] Self-review code trước khi submit
- [ ] Tests pass locally
- [ ] Documentation updated
- [ ] Breaking changes được note
- [ ] Screenshots cho UI changes

## 🚀 Release Process

### Semantic Versioning
- **Major** (1.0.0): Breaking changes
- **Minor** (0.1.0): New features, backward compatible
- **Patch** (0.0.1): Bug fixes

### Commit Message Format
```
type(scope): description

feat(auth): thêm đăng nhập bằng Google
fix(posts): sửa lỗi pagination
docs(api): cập nhật API documentation
style(ui): cải thiện responsive design
refactor(models): tối ưu query performance
test(posts): thêm tests cho PostController
```

### Types
- `feat`: Tính năng mới
- `fix`: Sửa bug
- `docs`: Cập nhật documentation
- `style`: Thay đổi UI/styling
- `refactor`: Refactor code
- `test`: Thêm/sửa tests
- `chore`: Maintenance tasks

## 🛡️ Security Guidelines

### Báo cáo Security Issues
- **KHÔNG** tạo public issue cho security bugs
- Email trực tiếp: security@vuphuc.com
- Cung cấp chi tiết về vulnerability
- Chờ response trước khi public disclosure

### Security Best Practices
- Luôn validate input
- Sử dụng parameterized queries
- Escape output
- Implement proper authentication/authorization
- Keep dependencies updated

## 📞 Liên hệ

- **GitHub Issues**: Cho bugs và feature requests
- **Email**: dev@vuphuc.com
- **Discord**: [Link Discord server]

## 📄 License

Bằng việc đóng góp, bạn đồng ý rằng contributions của bạn sẽ được licensed theo MIT License.

---

Cảm ơn bạn đã đóng góp cho dự án Vũ Phúc! 🎉
