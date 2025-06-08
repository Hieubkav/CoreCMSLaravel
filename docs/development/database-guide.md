# Cấu trúc cơ sở dữ liệu - Dự án Công ty Bánh

## 1. Setting (Cài đặt - 1 bản ghi)

Trường:
- company_name: string, not null - Tên công ty
- email: string, unique - Email liên hệ
- phone: string - Số điện thoại
- youtube_url: string, nullable - Liên kết kênh YouTube
- zalo_url: string, nullable - Liên kết Zalo
- facebook_url: string, nullable - Liên kết Facebook
- logo_url: string, nullable - Logo công ty
- meta_description: text, nullable - Mô tả meta cho SEO
- address1: text, nullable - Địa chỉ 1
- address2: text, nullable - Địa chỉ 2
- address3: text, nullable - Địa chỉ 3
- address4: text, nullable - Địa chỉ 4
- address5: text, nullable - Địa chỉ 5
- created_at, updated_at: timestamp - Thời gian tạo và cập nhật

Quan hệ: Không có

## 2. WebDesign (Thiết kế web - 1 bản ghi)

Trường:
- service_order: integer, default 0 - Thứ tự hiển thị dịch vụ
- service_status: boolean, default true - Trạng thái khu vực dịch vụ
- carousel_order: integer, default 0 - Thứ tự hiển thị carousel
- carousel_status: boolean, default true - Trạng thái khu vực carousel
- created_at, updated_at: timestamp - Thời gian tạo và cập nhật

Quan hệ: Không có

## 3. Service (Dịch vụ)

Trường:
- name: string, not null - Tên dịch vụ
- image: string, nullable - Ảnh minh họa dịch vụ
- description: text, nullable - Mô tả dịch vụ
- order: integer, default 0 - Thứ tự hiển thị
- status: boolean, default true - Trạng thái hoạt động
- created_at, updated_at: timestamp - Thời gian tạo và cập nhật

Quan hệ: Không có

## 4. Carousel (Băng chuyền hình ảnh)

Trường:
- image: string, not null - Đường dẫn hình ảnh
- order: integer, default 0 - Thứ tự hiển thị
- status: boolean, default true - Trạng thái hoạt động
- created_at, updated_at: timestamp - Thời gian tạo và cập nhật

Quan hệ: Không có

## 5. MenuItem (Mục menu)

Trường:
- parent_id: integer, nullable - ID của menu cha
- label: string, not null - Nhãn hiển thị
- type: enum('link', 'product', 'post'), not null - Kiểu menu item
- link: string, nullable - Đường dẫn liên kết
- product_id: integer, nullable - ID sản phẩm liên kết
- post_id: integer, nullable - ID bài viết liên kết
- order: integer, default 0 - Thứ tự hiển thị
- status: boolean, default true - Trạng thái hoạt động
- created_at, updated_at: timestamp - Thời gian tạo và cập nhật

Quan hệ:
- Tự tham chiếu (1-n): parent_id liên kết MenuItem cha (belongsTo), có nhiều MenuItem con (hasMany)
- 1-1: product_id liên kết Product (belongsTo)
- 1-1: post_id liên kết Post (belongsTo)

## 6. Customer (Khách hàng)

Trường:
- email: string, unique, nullable - Email khách hàng
- phone: string, unique, nullable - Số điện thoại khách hàng
- password: string, not null - Mật khẩu đăng nhập
- name: string, not null - Tên khách hàng
- address: text, nullable - Địa chỉ khách hàng
- order: integer, default 0 - Thứ tự hiển thị
- status: boolean, default true - Trạng thái hoạt động
- created_at, updated_at: timestamp - Thời gian tạo và cập nhật

Quan hệ: Không có

## 7. Partner (Đối tác)

Trường:
- name: string, not null - Tên đối tác
- logo: string, nullable - Logo đối tác
- website: string, nullable - Trang web đối tác
- description: text, nullable - Mô tả về đối tác
- order: integer, default 0 - Thứ tự hiển thị
- status: boolean, default true - Trạng thái hoạt động
- created_at, updated_at: timestamp - Thời gian tạo và cập nhật

Quan hệ: Không có

## 8. Post (Bài viết)

Trường:
- title: string, not null - Tiêu đề bài viết
- content: text, nullable - Nội dung bài viết
- image: string, nullable - Ảnh đại diện bài viết
- order: integer, default 0 - Thứ tự hiển thị
- status: boolean, default true - Trạng thái hoạt động
- created_at, updated_at: timestamp - Thời gian tạo và cập nhật

Quan hệ:
- 1-n: Được liên kết từ MenuItem (post_id)

## 9. DeliveryRoute (Tuyến giao hàng)

Trường:
- image: string, nullable - Ảnh minh họa tuyến
- description: text, nullable - Mô tả tuyến giao hàng
- order: integer, default 0 - Thứ tự hiển thị
- status: boolean, default true - Trạng thái hoạt động
- created_at, updated_at: timestamp - Thời gian tạo và cập nhật

Quan hệ: Không có

## 10. Product (Sản phẩm)

Trường:
- name: string, not null - Tên sản phẩm
- price: decimal(15,2), nullable - Giá sản phẩm
- description: text, nullable - Mô tả sản phẩm
- order: integer, default 0 - Thứ tự hiển thị
- status: boolean, default true - Trạng thái hoạt động
- created_at, updated_at: timestamp - Thời gian tạo và cập nhật

Quan hệ:
- 1-n: Có nhiều ProductImage (hasMany)
- 1-n: Được liên kết từ MenuItem (product_id)

## 11. ProductImage (Hình ảnh sản phẩm)

Trường:
- product_id: integer, not null - ID sản phẩm liên kết
- image: string, not null - Đường dẫn hình ảnh
- order: integer, default 0 - Thứ tự hiển thị
- status: boolean, default true - Trạng thái hoạt động
- created_at, updated_at: timestamp - Thời gian tạo và cập nhật

Quan hệ:
- n-1: Liên kết Product (product_id, belongsTo)

## 12. User (Người dùng)

Trường:
- name: string, not null - Tên người dùng
- email: string, unique, not null - Email người dùng
- password: string, not null - Mật khẩu đăng nhập
- order: integer, default 0 - Thứ tự hiển thị
- status: boolean, default true - Trạng thái hoạt động
- created_at, updated_at: timestamp - Thời gian tạo và cập nhật

Quan hệ: Không có

## Giải thích các mối quan hệ

1. MenuItem có quan hệ đệ quy với chính nó (self-referential relationship):
   - Một MenuItem có thể có một MenuItem cha (parent_id)
   - Một MenuItem có thể có nhiều MenuItem con (được tham chiếu từ parent_id của các MenuItem khác)

2. MenuItem có quan hệ với Product và Post:
   - Một MenuItem có thể liên kết đến một Product (product_id)
   - Một MenuItem có thể liên kết đến một Post (post_id)

3. Product có quan hệ với ProductImage:
   - Một Product có thể có nhiều ProductImage
   - Mỗi ProductImage thuộc về một Product duy nhất

## Lưu ý về migrations

Để đảm bảo các khóa ngoại hoạt động đúng, thứ tự thực thi migrations nên như sau:

1. Các bảng độc lập không có khóa ngoại:
   - settings
   - web_designs
   - services
   - carousels
   - customers
   - partners
   - delivery_routes
   - users
   - posts
   - products

2. Các bảng có khóa ngoại:
   - product_images (phụ thuộc vào products)
   - menu_items (phụ thuộc vào products, posts và chính nó)

Điều này đảm bảo rằng các bảng con được tạo sau các bảng cha để tránh lỗi khóa ngoại.