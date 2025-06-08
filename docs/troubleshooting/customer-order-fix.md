# Customer Order Column Fix - Đã khắc phục

## **Vấn đề ban đầu:**
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'order' in 'order clause'
```

**URL lỗi**: `http://127.0.0.1:8000/admin/customers`

**Nguyên nhân**: CustomerResource đang cố gắng sắp xếp theo cột `order` nhưng bảng `customers` không có cột này.

## **Phân tích:**

### **CustomerResource.php (dòng 133):**
```php
->defaultSort('order', 'asc')
```

### **Migration customers ban đầu:**
```php
Schema::create('customers', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique()->nullable();
    $table->string('password')->nullable();
    $table->string('phone')->nullable();
    $table->text('address')->nullable();
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->timestamps();
    // ❌ THIẾU: $table->integer('order')->default(0);
});
```

### **So sánh với các model khác:**
- **Partners**: ✅ Có cột `order`
- **Employees**: ✅ Có cột `order`  
- **Products**: ✅ Có cột `order`
- **Posts**: ✅ Có cột `order`
- **Customers**: ❌ Không có cột `order`

## **Giải pháp đã áp dụng:**

### **1. Tạo migration thêm cột `order`:**
```bash
php artisan make:migration add_order_column_to_customers_table --table=customers
```

### **2. Nội dung migration:**
```php
public function up(): void
{
    Schema::table('customers', function (Blueprint $table) {
        $table->integer('order')->default(0)->after('address');
    });
}

public function down(): void
{
    Schema::table('customers', function (Blueprint $table) {
        $table->dropColumn('order');
    });
}
```

### **3. Chạy migration:**
```bash
php artisan migrate
```

### **4. Cập nhật Customer model:**
```php
protected $fillable = [
    'name',
    'email',
    'password',
    'phone',
    'address',
    'order',    // ← THÊM MỚI
    'status',
];
```

### **5. Cập nhật CustomerResource:**
```php
->defaultSort('order', 'asc')
->reorderable('order');  // ← THÊM MỚI để có thể kéo thả sắp xếp
```

## **Cấu trúc bảng customers sau khi sửa:**

```sql
CREATE TABLE customers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NULL,
    password VARCHAR(255) NULL,
    phone VARCHAR(255) NULL,
    address TEXT NULL,
    order INT DEFAULT 0,           -- ← CỘT MỚI
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

## **Tính năng mới có được:**

### **1. ✅ Sắp xếp theo thứ tự**
- Customers sẽ được hiển thị theo cột `order` tăng dần
- Nhất quán với các model khác

### **2. ✅ Kéo thả để sắp xếp**
- Có thể kéo thả để thay đổi thứ tự hiển thị
- Tự động cập nhật giá trị `order`

### **3. ✅ Form có trường Order**
- Có thể nhập thứ tự hiển thị khi tạo/sửa customer
- Giá trị mặc định là 0

### **4. ✅ Table hiển thị cột Order**
- Cột "Thứ tự" hiển thị trong danh sách
- Có thể sort theo cột này

## **Kết quả:**

### **Trước khi sửa:**
```
❌ SQLSTATE[42S22]: Column not found: 1054 Unknown column 'order'
❌ Không thể truy cập /admin/customers
```

### **Sau khi sửa:**
```
✅ Trang /admin/customers hoạt động bình thường
✅ Customers được sắp xếp theo thứ tự
✅ Có thể kéo thả để thay đổi thứ tự
✅ Nhất quán với các model khác
```

## **Lưu ý:**

### **1. Backward Compatible:**
- Migration có `down()` method để rollback nếu cần
- Giá trị mặc định `0` cho tất cả record hiện tại

### **2. Consistent Design:**
- Tất cả model quan trọng đều có cột `order`
- Cùng pattern sắp xếp và reorderable

### **3. User Experience:**
- Admin có thể sắp xếp customers theo ý muốn
- Drag & drop interface thân thiện

## **Test để kiểm tra:**

1. **Truy cập**: `http://127.0.0.1:8000/admin/customers` ✅
2. **Tạo customer mới**: Có trường "Thứ tự hiển thị" ✅
3. **Kéo thả sắp xếp**: Thay đổi thứ tự trong danh sách ✅
4. **Sort theo Order**: Click vào header "Thứ tự" ✅

🎉 **Lỗi đã được khắc phục hoàn toàn!**
