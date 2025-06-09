# API Documentation

## 📋 Tổng quan

Core Laravel Framework cung cấp RESTful API để tích hợp với các ứng dụng khác.

**Base URL:** `http://your-domain.com/api`

## 🔐 Authentication

### API Token

Sử dụng Bearer Token trong header:

```bash
Authorization: Bearer your-api-token
```

### Lấy API Token

```bash
POST /api/auth/login
Content-Type: application/json

{
    "email": "admin@example.com",
    "password": "password"
}
```

**Response:**
```json
{
    "success": true,
    "token": "your-api-token",
    "user": {
        "id": 1,
        "name": "Admin",
        "email": "admin@example.com"
    }
}
```

## 📝 Posts API

### Lấy danh sách bài viết

```bash
GET /api/posts
```

**Parameters:**
- `page` - Trang (mặc định: 1)
- `per_page` - Số bài viết/trang (mặc định: 15)
- `category` - ID danh mục
- `search` - Từ khóa tìm kiếm

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Tiêu đề bài viết",
            "slug": "tieu-de-bai-viet",
            "excerpt": "Tóm tắt bài viết...",
            "thumbnail": "http://domain.com/storage/posts/image.webp",
            "published_at": "2025-01-01T00:00:00Z",
            "category": {
                "id": 1,
                "name": "Tin tức"
            }
        }
    ],
    "meta": {
        "current_page": 1,
        "total": 50,
        "per_page": 15
    }
}
```

### Lấy chi tiết bài viết

```bash
GET /api/posts/{id}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Tiêu đề bài viết",
        "content": "Nội dung đầy đủ...",
        "thumbnail": "http://domain.com/storage/posts/image.webp",
        "published_at": "2025-01-01T00:00:00Z",
        "view_count": 150,
        "category": {
            "id": 1,
            "name": "Tin tức"
        },
        "tags": ["tag1", "tag2"]
    }
}
```

### Tạo bài viết mới

```bash
POST /api/posts
Authorization: Bearer your-token
Content-Type: application/json

{
    "title": "Tiêu đề mới",
    "content": "Nội dung bài viết...",
    "category_id": 1,
    "status": "published",
    "thumbnail": "base64-image-data"
}
```

## 🛒 Products API

### Lấy danh sách sản phẩm

```bash
GET /api/products
```

**Parameters:**
- `page` - Trang
- `category` - ID danh mục
- `min_price` - Giá tối thiểu
- `max_price` - Giá tối đa
- `search` - Từ khóa

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Tên sản phẩm",
            "slug": "ten-san-pham",
            "price": 100000,
            "sale_price": 80000,
            "thumbnail": "http://domain.com/storage/products/image.webp",
            "in_stock": true,
            "category": {
                "id": 1,
                "name": "Danh mục"
            }
        }
    ]
}
```

### Chi tiết sản phẩm

```bash
GET /api/products/{id}
```

## 👥 Users API

### Lấy thông tin user

```bash
GET /api/user
Authorization: Bearer your-token
```

### Cập nhật profile

```bash
PUT /api/user/profile
Authorization: Bearer your-token

{
    "name": "Tên mới",
    "email": "email@example.com"
}
```

## 🔍 Search API

### Tìm kiếm tổng hợp

```bash
GET /api/search?q=keyword
```

**Response:**
```json
{
    "success": true,
    "data": {
        "posts": [...],
        "products": [...],
        "total": 25
    },
    "suggestions": ["keyword1", "keyword2"]
}
```

## 📊 Analytics API

### Thống kê tổng quan

```bash
GET /api/analytics/overview
Authorization: Bearer your-token
```

**Response:**
```json
{
    "success": true,
    "data": {
        "total_visitors": 1500,
        "today_visitors": 45,
        "total_posts": 120,
        "total_products": 80,
        "total_orders": 25
    }
}
```

## 🌐 Website Settings API

### Lấy cấu hình website

```bash
GET /api/website-settings
```

**Response:**
```json
{
    "success": true,
    "data": {
        "site_name": "Website Name",
        "site_description": "Description...",
        "contact_info": {
            "phone": "+84123456789",
            "email": "info@example.com",
            "address": "123 Street, City"
        },
        "social_links": {
            "facebook": "https://facebook.com/page",
            "youtube": "https://youtube.com/channel"
        }
    }
}
```

## 🎨 Web Design API

### Lấy cấu hình thiết kế

```bash
GET /api/web-design
```

**Response:**
```json
{
    "success": true,
    "data": {
        "theme_info": {
            "primary_color": "#dc2626",
            "secondary_color": "#1f2937",
            "font_family": "Inter"
        },
        "css_variables": {
            "--primary-color": "#dc2626",
            "--font-family": "Inter, sans-serif"
        }
    }
}
```

## 🌍 Multi-Language API

### Lấy danh sách ngôn ngữ

```bash
GET /api/languages
```

### Lấy bản dịch

```bash
GET /api/translations/{language}
```

## ❌ Error Responses

### Lỗi xác thực

```json
{
    "success": false,
    "message": "Unauthenticated",
    "code": 401
}
```

### Lỗi validation

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "title": ["Title is required"],
        "email": ["Email format is invalid"]
    },
    "code": 422
}
```

### Lỗi server

```json
{
    "success": false,
    "message": "Internal server error",
    "code": 500
}
```

## 📝 Rate Limiting

- **Limit:** 60 requests/minute cho guest
- **Limit:** 1000 requests/minute cho authenticated users

## 📞 Hỗ trợ

- **Postman Collection:** [Download](link-to-postman)
- **GitHub Issues:** [Repository Issues](link)
- **Email:** api-support@example.com
