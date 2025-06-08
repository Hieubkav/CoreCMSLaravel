# Cấu trúc Directory đã được cải thiện

## **Cấu trúc mới (Khoa học và nhất quán):**

```
storage/app/public/
├── products/
│   └── gallery/              # Ảnh chi tiết sản phẩm
├── posts/
│   ├── thumbnails/           # Ảnh đại diện bài viết
│   └── gallery/              # Ảnh bổ sung bài viết
├── employees/
│   ├── avatars/              # Ảnh đại diện nhân viên
│   ├── qr-codes/             # Mã QR nhân viên
│   └── gallery/              # Ảnh bổ sung nhân viên
├── partners/
│   └── logos/                # Logo đối tác
├── associations/
│   └── logos/                # Logo hiệp hội
├── sliders/
│   └── banners/              # Ảnh slider/banner
└── settings/
    ├── logos/                # Logo website
    ├── favicons/             # Favicon website
    └── og-images/            # Ảnh OG cho social media
```

## **Mapping Resource → Directory:**

### **1. ProductResource**
- **ProductImagesRelationManager**: `products/gallery/`
- **Mục đích**: Ảnh chi tiết sản phẩm

### **2. PostResource**
- **PostResource**: `posts/thumbnails/`
- **PostImagesRelationManager**: `posts/gallery/`
- **Mục đích**: Ảnh đại diện và ảnh bổ sung bài viết

### **3. CourseResource**
- **CourseResource (image_link)**: `courses/thumbnails/`
- **CourseImagesRelationManager**: `courses/gallery/`
- **Mục đích**: Ảnh đại diện và ảnh bổ sung khóa học

### **4. PartnerResource**
- **PartnerResource**: `partners/logos/`
- **Mục đích**: Logo đối tác

### **5. AssociationResource**
- **AssociationResource**: `associations/logos/`
- **Mục đích**: Logo hiệp hội

### **6. SliderResource**
- **SliderResource**: `sliders/banners/`
- **Mục đích**: Ảnh slider/banner

### **7. ManageSettings (Setting)**
- **ManageSettings (logo_link)**: `settings/logos/`
- **ManageSettings (favicon_link)**: `settings/favicons/`
- **ManageSettings (og_image_link)**: `settings/og-images/`
- **Mục đích**: Logo website, favicon và ảnh OG cho social media

## **Observer đã cập nhật:**

### **1. CourseObserver** ✅
- **Xử lý**: `image_link`
- **Khi xóa Course**: Xóa ảnh đại diện và tất cả ảnh gallery

### **2. SettingObserver** ✅
- **Xử lý**: `logo_link`, `favicon_link` và `og_image_link`
- **Khi cập nhật Setting**: Xóa file cũ khi thay đổi logo/favicon/OG image
- **Khi xóa Setting**: Xóa tất cả file liên quan

### **3. Các Observer khác** ✅
- **Đã hoạt động đúng** với directory mới vì ImageService chỉ cần đường dẫn file

## **Lợi ích của cấu trúc mới:**

### **1. Tổ chức khoa học**
- **Phân cấp rõ ràng**: Mỗi loại file có thư mục riêng
- **Dễ quản lý**: Biết ngay file nào thuộc chức năng gì
- **Dễ backup**: Có thể backup từng loại file riêng biệt

### **2. Hiệu suất tốt hơn**
- **Tìm kiếm nhanh**: File được phân loại rõ ràng
- **Ít xung đột**: Tên file không bị trùng lặp giữa các module

### **3. Bảo trì dễ dàng**
- **Debug nhanh**: Biết ngay file lỗi thuộc module nào
- **Cleanup dễ**: Có thể xóa toàn bộ thư mục khi không cần

### **4. Mở rộng linh hoạt**
- **Thêm module mới**: Chỉ cần tạo thư mục con mới
- **Thêm loại file**: Tạo thư mục con trong module hiện tại

## **Quy tắc đặt tên:**

### **1. Thư mục chính**
- **Tên số ít**: `product`, `post`, `employee` (không phải `products`)
- **Tên tiếng Anh**: Nhất quán với tên model

### **2. Thư mục con**
- **Mô tả chức năng**: `avatars`, `thumbnails`, `gallery`, `logos`, `banners`
- **Tên số nhiều**: Vì chứa nhiều file

### **3. Ví dụ tốt**
```
employees/avatars/     ✅
employees/gallery/     ✅
posts/thumbnails/      ✅
partners/logos/        ✅
```

### **4. Ví dụ không tốt**
```
employee/avatar/       ❌ (thiếu s)
posts/thumbnail/       ❌ (thiếu s)
partner/logo/          ❌ (thiếu s)
employees/images/      ❌ (không rõ nghĩa)
```

## **Kết luận:**

✅ **Cấu trúc directory đã được cải thiện hoàn toàn**
✅ **Tất cả Filament Resource đã được cập nhật**
✅ **Observer hoạt động đúng với cấu trúc mới**
✅ **Dễ quản lý và mở rộng trong tương lai**
