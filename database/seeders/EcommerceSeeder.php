<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Models\ProductVariant;
use App\Models\Coupon;
use App\Models\ShippingMethod;
use App\Models\PaymentMethod;

class EcommerceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🛒 Bắt đầu tạo dữ liệu mẫu cho E-commerce Module...');

        $this->createProductCategories();
        $this->createProductAttributes();
        $this->createProducts();
        $this->createCoupons();
        $this->createShippingMethods();
        $this->createPaymentMethods();

        $this->command->info('🎉 Hoàn thành tạo dữ liệu mẫu E-commerce!');
    }

    /**
     * Tạo product categories
     */
    private function createProductCategories()
    {
        $categories = [
            [
                'name' => 'Điện thoại & Máy tính bảng',
                'slug' => 'dien-thoai-may-tinh-bang',
                'description' => 'Điện thoại thông minh, máy tính bảng và phụ kiện',
                'icon' => 'fas fa-mobile-alt',
                'status' => 'active',
                'order' => 1,
                'children' => [
                    [
                        'name' => 'Điện thoại',
                        'slug' => 'dien-thoai',
                        'description' => 'Điện thoại thông minh các hãng',
                        'icon' => 'fas fa-mobile-alt',
                        'status' => 'active',
                        'order' => 1
                    ],
                    [
                        'name' => 'Máy tính bảng',
                        'slug' => 'may-tinh-bang',
                        'description' => 'iPad, Samsung Tab và các tablet khác',
                        'icon' => 'fas fa-tablet-alt',
                        'status' => 'active',
                        'order' => 2
                    ]
                ]
            ],
            [
                'name' => 'Laptop & Máy tính',
                'slug' => 'laptop-may-tinh',
                'description' => 'Laptop, PC và linh kiện máy tính',
                'icon' => 'fas fa-laptop',
                'status' => 'active',
                'order' => 2,
                'children' => [
                    [
                        'name' => 'Laptop',
                        'slug' => 'laptop',
                        'description' => 'Laptop gaming, văn phòng, ultrabook',
                        'icon' => 'fas fa-laptop',
                        'status' => 'active',
                        'order' => 1
                    ],
                    [
                        'name' => 'PC & Linh kiện',
                        'slug' => 'pc-linh-kien',
                        'description' => 'Máy tính để bàn và linh kiện',
                        'icon' => 'fas fa-desktop',
                        'status' => 'active',
                        'order' => 2
                    ]
                ]
            ],
            [
                'name' => 'Thời trang',
                'slug' => 'thoi-trang',
                'description' => 'Quần áo, giày dép, phụ kiện thời trang',
                'icon' => 'fas fa-tshirt',
                'status' => 'active',
                'order' => 3,
                'children' => [
                    [
                        'name' => 'Quần áo nam',
                        'slug' => 'quan-ao-nam',
                        'description' => 'Thời trang nam',
                        'icon' => 'fas fa-male',
                        'status' => 'active',
                        'order' => 1
                    ],
                    [
                        'name' => 'Quần áo nữ',
                        'slug' => 'quan-ao-nu',
                        'description' => 'Thời trang nữ',
                        'icon' => 'fas fa-female',
                        'status' => 'active',
                        'order' => 2
                    ]
                ]
            ]
        ];

        foreach ($categories as $categoryData) {
            $children = $categoryData['children'] ?? [];
            unset($categoryData['children']);

            $category = ProductCategory::firstOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );

            foreach ($children as $childData) {
                $childData['parent_id'] = $category->id;
                ProductCategory::firstOrCreate(
                    ['slug' => $childData['slug']],
                    $childData
                );
            }
        }

        $this->command->info('✅ Đã tạo ' . ProductCategory::count() . ' product categories');
    }

    /**
     * Tạo product attributes
     */
    private function createProductAttributes()
    {
        $attributes = [
            [
                'name' => 'Màu sắc',
                'slug' => 'mau-sac',
                'type' => 'select',
                'options' => ['Đen', 'Trắng', 'Xanh', 'Đỏ', 'Vàng', 'Hồng'],
                'is_required' => false,
                'is_filterable' => true,
                'is_variation' => true,
                'status' => 'active',
                'order' => 1
            ],
            [
                'name' => 'Kích thước',
                'slug' => 'kich-thuoc',
                'type' => 'select',
                'options' => ['S', 'M', 'L', 'XL', 'XXL'],
                'is_required' => false,
                'is_filterable' => true,
                'is_variation' => true,
                'status' => 'active',
                'order' => 2
            ],
            [
                'name' => 'Dung lượng',
                'slug' => 'dung-luong',
                'type' => 'select',
                'options' => ['64GB', '128GB', '256GB', '512GB', '1TB'],
                'is_required' => false,
                'is_filterable' => true,
                'is_variation' => true,
                'status' => 'active',
                'order' => 3
            ],
            [
                'name' => 'Chất liệu',
                'slug' => 'chat-lieu',
                'type' => 'select',
                'options' => ['Cotton', 'Polyester', 'Denim', 'Silk', 'Wool'],
                'is_required' => false,
                'is_filterable' => true,
                'is_variation' => false,
                'status' => 'active',
                'order' => 4
            ]
        ];

        foreach ($attributes as $attributeData) {
            ProductAttribute::firstOrCreate(
                ['slug' => $attributeData['slug']],
                $attributeData
            );
        }

        $this->command->info('✅ Đã tạo ' . count($attributes) . ' product attributes');
    }

    /**
     * Tạo products mẫu
     */
    private function createProducts()
    {
        // Lấy categories
        $phoneCategory = ProductCategory::where('slug', 'dien-thoai')->first();
        $laptopCategory = ProductCategory::where('slug', 'laptop')->first();
        $menClothingCategory = ProductCategory::where('slug', 'quan-ao-nam')->first();

        if (!$phoneCategory || !$laptopCategory || !$menClothingCategory) {
            $this->command->warn('Không tìm thấy categories, bỏ qua tạo products');
            return;
        }

        $products = [
            [
                'category_id' => $phoneCategory->id,
                'name' => 'iPhone 15 Pro Max',
                'slug' => 'iphone-15-pro-max',
                'short_description' => 'Điện thoại iPhone 15 Pro Max mới nhất với chip A17 Pro',
                'description' => 'iPhone 15 Pro Max với thiết kế titan cao cấp, camera 48MP, chip A17 Pro mạnh mẽ và pin lâu dài.',
                'sku' => 'IP15PM-001',
                'price' => 29990000,
                'sale_price' => 27990000,
                'stock_quantity' => 50,
                'type' => 'variable',
                'status' => 'active',
                'is_featured' => true,
                'order' => 1
            ],
            [
                'category_id' => $laptopCategory->id,
                'name' => 'MacBook Air M2',
                'slug' => 'macbook-air-m2',
                'short_description' => 'Laptop MacBook Air với chip M2 mạnh mẽ',
                'description' => 'MacBook Air M2 với thiết kế mỏng nhẹ, hiệu năng vượt trội và thời lượng pin ấn tượng.',
                'sku' => 'MBA-M2-001',
                'price' => 25990000,
                'stock_quantity' => 30,
                'type' => 'simple',
                'status' => 'active',
                'is_featured' => true,
                'order' => 2
            ],
            [
                'category_id' => $menClothingCategory->id,
                'name' => 'Áo thun nam basic',
                'slug' => 'ao-thun-nam-basic',
                'short_description' => 'Áo thun nam cotton 100% thoáng mát',
                'description' => 'Áo thun nam chất liệu cotton 100%, form regular fit, phù hợp mọi hoạt động hàng ngày.',
                'sku' => 'SHIRT-001',
                'price' => 199000,
                'sale_price' => 149000,
                'stock_quantity' => 100,
                'type' => 'variable',
                'status' => 'active',
                'is_featured' => false,
                'order' => 3
            ]
        ];

        foreach ($products as $productData) {
            Product::firstOrCreate(
                ['sku' => $productData['sku']],
                $productData
            );
        }

        $this->command->info('✅ Đã tạo ' . count($products) . ' products');
    }

    /**
     * Tạo coupons mẫu
     */
    private function createCoupons()
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'name' => 'Chào mừng khách hàng mới',
                'description' => 'Giảm 10% cho đơn hàng đầu tiên',
                'type' => 'percentage',
                'value' => 10,
                'minimum_amount' => 500000,
                'maximum_discount' => 100000,
                'usage_limit' => 1000,
                'usage_limit_per_user' => 1,
                'first_order_only' => true,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(3),
                'status' => 'active',
                'order' => 1
            ],
            [
                'code' => 'SALE50K',
                'name' => 'Giảm 50K',
                'description' => 'Giảm 50.000đ cho đơn hàng từ 1 triệu',
                'type' => 'fixed',
                'value' => 50000,
                'minimum_amount' => 1000000,
                'usage_limit' => 500,
                'starts_at' => now(),
                'expires_at' => now()->addMonth(),
                'status' => 'active',
                'order' => 2
            ],
            [
                'code' => 'FREESHIP',
                'name' => 'Miễn phí vận chuyển',
                'description' => 'Miễn phí ship cho đơn từ 500K',
                'type' => 'fixed',
                'value' => 30000,
                'minimum_amount' => 500000,
                'maximum_discount' => 30000,
                'usage_limit' => 200,
                'starts_at' => now(),
                'expires_at' => now()->addWeeks(2),
                'status' => 'active',
                'order' => 3
            ]
        ];

        foreach ($coupons as $couponData) {
            Coupon::firstOrCreate(
                ['code' => $couponData['code']],
                $couponData
            );
        }

        $this->command->info('✅ Đã tạo ' . count($coupons) . ' coupons');
    }

    /**
     * Tạo shipping methods
     */
    private function createShippingMethods()
    {
        $shippingMethods = [
            [
                'name' => 'Giao hàng tiêu chuẩn',
                'slug' => 'giao-hang-tieu-chuan',
                'description' => 'Giao hàng trong 3-5 ngày làm việc',
                'cost_type' => 'fixed',
                'cost' => 30000,
                'free_shipping_threshold' => 500000,
                'min_delivery_days' => 3,
                'max_delivery_days' => 5,
                'delivery_time_text' => '3-5 ngày làm việc',
                'status' => 'active',
                'order' => 1
            ],
            [
                'name' => 'Giao hàng nhanh',
                'slug' => 'giao-hang-nhanh',
                'description' => 'Giao hàng trong 1-2 ngày làm việc',
                'cost_type' => 'fixed',
                'cost' => 50000,
                'min_delivery_days' => 1,
                'max_delivery_days' => 2,
                'delivery_time_text' => '1-2 ngày làm việc',
                'status' => 'active',
                'order' => 2
            ],
            [
                'name' => 'Nhận tại cửa hàng',
                'slug' => 'nhan-tai-cua-hang',
                'description' => 'Nhận hàng trực tiếp tại cửa hàng',
                'cost_type' => 'free',
                'cost' => 0,
                'requires_address' => false,
                'is_pickup' => true,
                'pickup_address' => '123 Đường ABC, Quận 1, TP.HCM',
                'delivery_time_text' => 'Ngay khi đặt hàng',
                'status' => 'active',
                'order' => 3
            ]
        ];

        foreach ($shippingMethods as $methodData) {
            ShippingMethod::firstOrCreate(
                ['slug' => $methodData['slug']],
                $methodData
            );
        }

        $this->command->info('✅ Đã tạo ' . count($shippingMethods) . ' shipping methods');
    }

    /**
     * Tạo payment methods
     */
    private function createPaymentMethods()
    {
        $paymentMethods = [
            [
                'name' => 'Thanh toán khi nhận hàng',
                'slug' => 'thanh-toan-khi-nhan-hang',
                'description' => 'Thanh toán bằng tiền mặt khi nhận hàng',
                'type' => 'cash_on_delivery',
                'fixed_fee' => 0,
                'instructions' => 'Bạn sẽ thanh toán bằng tiền mặt khi nhận hàng từ shipper.',
                'status' => 'active',
                'order' => 1
            ],
            [
                'name' => 'Chuyển khoản ngân hàng',
                'slug' => 'chuyen-khoan-ngan-hang',
                'description' => 'Chuyển khoản qua ngân hàng',
                'type' => 'bank_transfer',
                'fixed_fee' => 0,
                'gateway_config' => [
                    'bank_info' => [
                        'bank_name' => 'Ngân hàng Vietcombank',
                        'account_number' => '1234567890',
                        'account_name' => 'CONG TY ABC',
                        'branch' => 'Chi nhánh TP.HCM'
                    ]
                ],
                'instructions' => 'Vui lòng chuyển khoản theo thông tin được cung cấp và gửi ảnh chụp biên lai.',
                'status' => 'active',
                'order' => 2
            ],
            [
                'name' => 'VNPay',
                'slug' => 'vnpay',
                'description' => 'Thanh toán qua VNPay',
                'type' => 'vnpay',
                'percentage_fee' => 2.5,
                'min_fee' => 5000,
                'max_fee' => 50000,
                'status' => 'active',
                'order' => 3
            ],
            [
                'name' => 'MoMo',
                'slug' => 'momo',
                'description' => 'Thanh toán qua ví MoMo',
                'type' => 'momo',
                'fixed_fee' => 0,
                'status' => 'active',
                'order' => 4
            ]
        ];

        foreach ($paymentMethods as $methodData) {
            PaymentMethod::firstOrCreate(
                ['slug' => $methodData['slug']],
                $methodData
            );
        }

        $this->command->info('✅ Đã tạo ' . count($paymentMethods) . ' payment methods');
    }
}
