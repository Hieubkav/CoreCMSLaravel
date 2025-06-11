# Visitor Tracking System - KISS Principle

## Tổng quan

Hệ thống tracking visitor được thiết kế theo nguyên tắc KISS (Keep It Simple, Stupid) với các đặc điểm:

- ✅ **Đơn giản**: Ai vào thì track, không dùng session phức tạp
- ✅ **Realtime**: Update stats mỗi 5 giây (có thể điều chỉnh)
- ✅ **Hiệu quả**: Reset = TRUNCATE table, không dùng DELETE
- ✅ **Linh hoạt**: Cấu hình tracking interval qua .env

## Cấu trúc Files

### 1. Models
- `app/Models/Visitor.php` - Model chính để lưu visitor data

### 2. Middleware
- `app/Http/Middleware/TrackVisitor.php` - Track mỗi request GET

### 3. Actions
- `app/Actions/System/GetVisitorStats.php` - Lấy thống kê
- `app/Actions/System/ResetVisitorStats.php` - Reset stats (TRUNCATE)
- `app/Actions/Setup/EnableVisitorTracking.php` - Setup tracking

### 4. Widgets
- `app/Filament/Admin/Widgets/AnalyticsOverviewWidget.php` - Widget admin
- `resources/views/filament/admin/widgets/analytics-overview-widget.blade.php` - View

### 5. Migration
- `database/migrations/*_create_visitors_table.php` - Bảng visitors

## Cấu hình

### .env Configuration
```env
# Visitor Tracking Configuration
VISITOR_TRACKING_INTERVAL=5  # Tracking interval (giây)
```

### Admin Configuration
```php
// Trong AdminConfiguration model
'enable_visitor_tracking' => true,
'visitor_analytics_enabled' => true,
'enable_analytics_dashboard' => true
```

## Logic Tracking

### TrackVisitor Middleware
```php
// Logic đơn giản
if (!$recentVisit) {
    Visitor::create([
        'ip_address' => $ipAddress,
        'user_agent' => $userAgent,
        'url' => $url,
        'session_id' => 'simple_' . uniqid(),
        'visited_at' => now(),
    ]);
}
```

### Reset Stats
```php
// TRUNCATE thay vì DELETE
DB::statement('TRUNCATE TABLE visitors');
```

## Cách sử dụng

### 1. Kích hoạt trong Setup
```php
// Tự động kích hoạt khi bật analytics trong admin config step
EnableVisitorTracking::enable();
```

### 2. Reset Stats
```php
// Từ admin panel hoặc code
ResetVisitorStats::reset();
```

### 3. Lấy Stats
```php
// Lấy thống kê overview
$stats = GetVisitorStats::overview();
// Returns: today_unique, today_total, total_unique, total_visits
```

### 4. Widget Realtime
- Auto-refresh mỗi 5 giây
- Hiển thị 4 metrics chính
- Button reset stats
- Responsive design

## Database Schema

```sql
CREATE TABLE visitors (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    ip_address VARCHAR(45) INDEX,
    user_agent TEXT,
    url VARCHAR(500) INDEX,
    content_id BIGINT NULL,
    session_id VARCHAR(100) INDEX,
    visited_at TIMESTAMP INDEX,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    INDEX(ip_address, visited_at),
    INDEX(content_id, visited_at),
    INDEX(session_id, visited_at)
);
```

## Performance

### Cache Strategy
- Cache stats 5 phút (today) / 1 giờ (total)
- Clear cache khi có visitor mới
- Clear cache khi reset stats

### Query Optimization
- Index trên ip_address, visited_at
- Distinct queries cho unique visitors
- Count queries cho total visits

## Troubleshooting

### Không thấy stats tăng khi F5
- **Nguyên nhân**: Tracking interval (default 5 giây)
- **Giải pháp**: Đợi 5 giây hoặc điều chỉnh VISITOR_TRACKING_INTERVAL

### Reset không về 0
- **Nguyên nhân**: Cache chưa được clear
- **Giải pháp**: Dùng ResetVisitorStats::reset() thay vì xóa manual

### Widget không hiển thị
- **Nguyên nhân**: visitor_analytics_enabled = false
- **Giải pháp**: Bật trong admin configuration

## Best Practices

1. **Production**: Set VISITOR_TRACKING_INTERVAL=3600 (1 giờ)
2. **Development**: Set VISITOR_TRACKING_INTERVAL=5 (5 giây)
3. **Reset**: Luôn dùng ResetVisitorStats::reset()
4. **Cache**: Để hệ thống tự quản lý cache
5. **Monitoring**: Check logs nếu có lỗi tracking

## Integration với Setup Wizard

Visitor tracking được tự động kích hoạt khi:
1. Hoàn thành admin config step
2. Bật visitor_analytics_enabled = true
3. EnableVisitorTracking::enable() được gọi
4. Tạo migration, model, middleware, widget
5. Cập nhật .env với tracking interval
