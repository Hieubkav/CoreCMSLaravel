# API Documentation

Tài liệu API cho dự án Vũ Phúc - Website Doanh Nghiệp.

## 🔗 Base URL

```
Development: http://127.0.0.1:8000/api
Production: https://vuphuc.com/api
```

## 🔐 Authentication

API sử dụng Laravel Sanctum để xác thực.

### Lấy Token
```http
POST /api/login
Content-Type: application/json

{
    "email": "admin@vuphuc.com",
    "password": "password"
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "token": "1|abc123...",
        "user": {
            "id": 1,
            "name": "Admin",
            "email": "admin@vuphuc.com"
        }
    }
}
```

### Sử dụng Token
```http
Authorization: Bearer 1|abc123...
```

## 📝 Posts API

### Lấy danh sách bài viết
```http
GET /api/posts
```

**Parameters:**
- `page` (int): Số trang (default: 1)
- `per_page` (int): Số bài viết mỗi trang (default: 10, max: 50)
- `category_id` (int): Lọc theo danh mục
- `search` (string): Tìm kiếm theo tiêu đề
- `status` (string): published|draft

**Response:**
```json
{
    "success": true,
    "data": {
        "data": [
            {
                "id": 1,
                "title": "Tiêu đề bài viết",
                "slug": "tieu-de-bai-viet",
                "excerpt": "Tóm tắt bài viết...",
                "featured_image": "https://vuphuc.com/storage/posts/image.webp",
                "category": {
                    "id": 1,
                    "name": "Tin tức",
                    "slug": "tin-tuc"
                },
                "published_at": "2024-01-15T10:30:00Z",
                "created_at": "2024-01-15T10:00:00Z"
            }
        ],
        "current_page": 1,
        "last_page": 5,
        "per_page": 10,
        "total": 50
    }
}
```

### Lấy chi tiết bài viết
```http
GET /api/posts/{id}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Tiêu đề bài viết",
        "slug": "tieu-de-bai-viet",
        "content": "Nội dung đầy đủ của bài viết...",
        "excerpt": "Tóm tắt bài viết...",
        "featured_image": "https://vuphuc.com/storage/posts/image.webp",
        "seo_title": "SEO Title",
        "seo_description": "SEO Description",
        "og_image": "https://vuphuc.com/storage/posts/og-image.webp",
        "category": {
            "id": 1,
            "name": "Tin tức",
            "slug": "tin-tuc"
        },
        "tags": ["tag1", "tag2"],
        "published_at": "2024-01-15T10:30:00Z",
        "created_at": "2024-01-15T10:00:00Z",
        "updated_at": "2024-01-15T11:00:00Z"
    }
}
```

## 📂 Categories API

### Lấy danh sách danh mục
```http
GET /api/categories
```

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Tin tức",
            "slug": "tin-tuc",
            "description": "Mô tả danh mục",
            "posts_count": 25,
            "created_at": "2024-01-01T00:00:00Z"
        }
    ]
}
```

## 🎓 Courses API

### Lấy danh sách khóa học
```http
GET /api/courses
```

**Parameters:**
- `category` (string): Lọc theo danh mục
- `status` (string): active|inactive
- `featured` (boolean): Khóa học nổi bật

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Excel VBA Cơ Bản",
            "slug": "excel-vba-co-ban",
            "description": "Khóa học Excel VBA từ cơ bản đến nâng cao...",
            "price": 2500000,
            "duration": "40 giờ",
            "level": "Cơ bản",
            "image": "https://vuphuc.com/storage/courses/excel-vba.webp",
            "instructor": "Thầy Vũ Phúc",
            "requirements": ["Kiến thức Excel cơ bản"],
            "learning_outcomes": ["Tự động hóa Excel", "Viết macro"],
            "gallery": [
                "https://vuphuc.com/storage/courses/gallery/1.webp",
                "https://vuphuc.com/storage/courses/gallery/2.webp"
            ],
            "status": "active",
            "created_at": "2024-01-01T00:00:00Z"
        }
    ]
}
```

### Lấy chi tiết nhân viên
```http
GET /api/employees/{id}
```

## ⚙️ Settings API

### Lấy cài đặt website
```http
GET /api/settings
```

**Response:**
```json
{
    "success": true,
    "data": {
        "site_name": "Vũ Phúc",
        "site_description": "Mô tả website",
        "logo": "https://vuphuc.com/storage/settings/logo.webp",
        "favicon": "https://vuphuc.com/storage/settings/favicon.ico",
        "contact_phone": "0123456789",
        "contact_email": "info@vuphuc.com",
        "contact_address": "Địa chỉ công ty",
        "social_facebook": "https://facebook.com/vuphuc",
        "social_youtube": "https://youtube.com/vuphuc",
        "placeholder_image": "https://vuphuc.com/storage/settings/placeholder.webp"
    }
}
```

## 🎠 Sliders API

### Lấy danh sách slider
```http
GET /api/sliders
```

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Tiêu đề slider",
            "description": "Mô tả slider",
            "image": "https://vuphuc.com/storage/sliders/banner.webp",
            "link": "https://vuphuc.com/about",
            "order": 1,
            "status": "active"
        }
    ]
}
```

## 🔍 Search API

### Tìm kiếm toàn bộ
```http
GET /api/search
```

**Parameters:**
- `q` (string, required): Từ khóa tìm kiếm
- `type` (string): posts|employees|all (default: all)
- `limit` (int): Số kết quả tối đa (default: 10)

**Response:**
```json
{
    "success": true,
    "data": {
        "posts": [
            {
                "id": 1,
                "title": "Tiêu đề bài viết",
                "slug": "tieu-de-bai-viet",
                "excerpt": "Tóm tắt...",
                "featured_image": "https://vuphuc.com/storage/posts/image.webp",
                "type": "post"
            }
        ],
        "employees": [
            {
                "id": 1,
                "name": "Nguyễn Văn A",
                "position": "Giám đốc",
                "avatar": "https://vuphuc.com/storage/employees/avatar.webp",
                "type": "employee"
            }
        ],
        "total": 2
    }
}
```

## 📊 Statistics API

### Lấy thống kê tổng quan
```http
GET /api/statistics
```

**Response:**
```json
{
    "success": true,
    "data": {
        "posts_count": 150,
        "categories_count": 8,
        "employees_count": 25,
        "recent_posts": [
            {
                "id": 1,
                "title": "Bài viết mới nhất",
                "published_at": "2024-01-15T10:30:00Z"
            }
        ]
    }
}
```

## ❌ Error Responses

### Format lỗi chuẩn
```json
{
    "success": false,
    "message": "Thông báo lỗi",
    "errors": {
        "field_name": ["Chi tiết lỗi"]
    }
}
```

### Mã lỗi HTTP
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error

## 📝 Rate Limiting

- **Unauthenticated**: 60 requests/minute
- **Authenticated**: 1000 requests/minute

## 🔧 Testing API

### Sử dụng cURL
```bash
# Lấy danh sách bài viết
curl -X GET "http://127.0.0.1:8000/api/posts" \
     -H "Accept: application/json"

# Tìm kiếm
curl -X GET "http://127.0.0.1:8000/api/search?q=tin%20tức" \
     -H "Accept: application/json"
```

### Sử dụng Postman
Import collection từ file: `docs/postman/vuphuc-api.json`

---

*Cập nhật lần cuối: {{ date('d/m/Y') }}*
