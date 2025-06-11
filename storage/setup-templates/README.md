# Core Framework Setup Templates

Thư mục này chứa tất cả templates và files cần thiết để tạo ra một dự án mới từ Core Framework.

## Cấu trúc

### 📁 actions/
- `setup/` - Actions cho setup wizard
- `system/` - Actions hệ thống (GetVisitorStats, ResetVisitorStats)

### 📁 docs/
- `VISITOR_TRACKING.md` - Documentation cho visitor tracking system

### 📁 filament/
- `pages/` - Filament admin pages
- `resources/` - Filament resources
- `widgets/` - Filament widgets

### 📁 middleware/
- `TrackVisitor.php` - Middleware tracking visitors

### 📁 migrations/
- Database migrations cho các bảng cơ bản

### 📁 models/
- Tất cả models cần thiết cho framework
- `Visitor.php` - Model cho visitor tracking

### 📁 observers/
- Model observers

### 📁 views/
- Blade templates cho widgets và components

### 📁 widgets/
- Widget classes

## Visitor Tracking System

### Đặc điểm chính:
- ✅ **KISS Principle**: Đơn giản, hiệu quả
- ✅ **Realtime**: Update mỗi 5 giây (configurable)
- ✅ **Reset = TRUNCATE**: Nhanh và sạch
- ✅ **No Session Complexity**: Chỉ dùng IP + URL + thời gian

### Files liên quan:
```
models/Visitor.php
middleware/TrackVisitor.php
actions/system/GetVisitorStats.php
actions/system/ResetVisitorStats.php
actions/setup/EnableVisitorTracking.php
widgets/AnalyticsOverviewWidget.php
views/filament/admin/widgets/analytics-overview-widget.blade.php
docs/VISITOR_TRACKING.md
```

### Cấu hình:
```env
VISITOR_TRACKING_INTERVAL=5  # Tracking interval (giây)
```

### Admin Configuration:
```php
'enable_visitor_tracking' => true,
'visitor_analytics_enabled' => true,
'enable_analytics_dashboard' => true
```

## Cách hoạt động

### 1. Setup Process
1. User chạy setup wizard
2. Đến bước admin config
3. Bật visitor analytics
4. `EnableVisitorTracking::enable()` được gọi
5. Tạo migration, model, middleware, widget
6. Cập nhật .env và admin config

### 2. Tracking Process
1. User truy cập website
2. `TrackVisitor` middleware chạy
3. Check duplicate trong X giây (config)
4. Nếu không duplicate → tạo visitor record
5. Clear cache để update stats
6. Widget auto-refresh mỗi 5 giây

### 3. Reset Process
1. Admin click "Reset Stats"
2. `ResetVisitorStats::reset()` được gọi
3. Count records trước khi xóa
4. `TRUNCATE TABLE visitors`
5. Clear tất cả cache
6. Return kết quả

## Integration Points

### Setup Wizard
- `ProcessAdminConfigStep.php` tự động kích hoạt tracking
- Kiểm tra `visitor_analytics_enabled` flag
- Gọi `EnableVisitorTracking::enable()`

### Admin Panel
- Widget hiển thị stats realtime
- Button reset stats
- Auto-refresh mỗi 5 giây
- Responsive design

### Performance
- Cache strategy: 5 phút (today) / 1 giờ (total)
- Database indexes cho performance
- TRUNCATE thay vì DELETE cho reset

## Best Practices

### Development
```env
VISITOR_TRACKING_INTERVAL=5  # Test realtime
```

### Production
```env
VISITOR_TRACKING_INTERVAL=3600  # 1 giờ để tránh spam
```

### Monitoring
- Check logs nếu có lỗi tracking
- Monitor database size
- Regular backup trước khi reset

## Troubleshooting

### Stats không tăng khi F5
- Đợi theo tracking interval
- Check middleware có được register không
- Check admin config có bật không

### Reset không về 0
- Dùng `ResetVisitorStats::reset()` thay vì manual
- Check cache có được clear không
- Check database permissions

### Widget không hiển thị
- Check `visitor_analytics_enabled` trong admin config
- Check widget có được register không
- Check permissions

## Files được tạo tự động

Khi chạy `EnableVisitorTracking::enable()`:

1. **Migration**: `*_create_visitors_table.php`
2. **Model**: `app/Models/Visitor.php`
3. **Middleware**: `app/Http/Middleware/TrackVisitor.php`
4. **Actions**: `app/Actions/System/GetVisitorStats.php`, `ResetVisitorStats.php`
5. **Widget**: `app/Filament/Admin/Widgets/AnalyticsOverviewWidget.php`
6. **View**: `resources/views/filament/admin/widgets/analytics-overview-widget.blade.php`
7. **Config**: Update `.env` và `AdminConfiguration`

Tất cả files được copy từ templates trong thư mục này.
