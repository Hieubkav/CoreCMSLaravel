@extends('setup.layout')

@section('title', 'Module: E-commerce - Core Framework Setup')
@section('description', 'Cài đặt hệ thống thương mại điện tử: Sản phẩm, đơn hàng, thanh toán')

@section('content')
<div class="text-center mb-8">
    <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-shopping-cart text-2xl text-emerald-600"></i>
    </div>
    <h2 class="text-2xl font-bold text-gray-900 mb-2">E-commerce Module</h2>
    <p class="text-gray-600">
        Hệ thống thương mại điện tử hoàn chỉnh với quản lý sản phẩm, đơn hàng và thanh toán.
    </p>
</div>

<!-- Module Configuration Form -->
<form id="module-form" onsubmit="configureModule(event)">
    <div class="space-y-8">
        
        <!-- Module Enable/Disable -->
        <div class="border border-gray-200 rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-toggle-on text-emerald-600 mr-2"></i>
                    Cài đặt Module
                </h3>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="enable_module" name="enable_module" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                    <span class="ml-3 text-sm font-medium text-gray-900">Bật module này</span>
                </label>
            </div>
            
            <div id="module-content" class="space-y-6" style="display: none;">
                <!-- Module Description -->
                <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                    <h4 class="font-semibold text-emerald-800 mb-2">Tính năng của module:</h4>
                    <ul class="text-emerald-700 text-sm space-y-1">
                        <li>• <strong>Product Management:</strong> Quản lý sản phẩm với variants, categories</li>
                        <li>• <strong>Shopping Cart:</strong> Giỏ hàng với session/database storage</li>
                        <li>• <strong>Order Management:</strong> Quản lý đơn hàng và trạng thái</li>
                        <li>• <strong>Payment Integration:</strong> Tích hợp cổng thanh toán</li>
                        <li>• <strong>Inventory Tracking:</strong> Theo dõi tồn kho</li>
                        <li>• <strong>Customer Management:</strong> Quản lý khách hàng</li>
                    </ul>
                </div>

                <!-- E-commerce Features Selection -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Chọn tính năng cần cài đặt:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_products" 
                                       name="enable_products" 
                                       checked
                                       class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                <label for="enable_products" class="ml-2 block text-sm text-gray-900">
                                    <strong>Product Management</strong> - Quản lý sản phẩm
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_cart" 
                                       name="enable_cart" 
                                       checked
                                       class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                <label for="enable_cart" class="ml-2 block text-sm text-gray-900">
                                    <strong>Shopping Cart</strong> - Giỏ hàng
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_orders" 
                                       name="enable_orders" 
                                       checked
                                       class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                <label for="enable_orders" class="ml-2 block text-sm text-gray-900">
                                    <strong>Order Management</strong> - Quản lý đơn hàng
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_inventory" 
                                       name="enable_inventory" 
                                       checked
                                       class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                <label for="enable_inventory" class="ml-2 block text-sm text-gray-900">
                                    <strong>Inventory Tracking</strong> - Theo dõi tồn kho
                                </label>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_payments" 
                                       name="enable_payments" 
                                       class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                <label for="enable_payments" class="ml-2 block text-sm text-gray-900">
                                    <strong>Payment Gateway</strong> - Cổng thanh toán
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_customers" 
                                       name="enable_customers" 
                                       checked
                                       class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                <label for="enable_customers" class="ml-2 block text-sm text-gray-900">
                                    <strong>Customer Management</strong> - Quản lý khách hàng
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_coupons" 
                                       name="enable_coupons" 
                                       class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                <label for="enable_coupons" class="ml-2 block text-sm text-gray-900">
                                    <strong>Coupon System</strong> - Hệ thống mã giảm giá
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_reviews" 
                                       name="enable_reviews" 
                                       class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                                <label for="enable_reviews" class="ml-2 block text-sm text-gray-900">
                                    <strong>Product Reviews</strong> - Đánh giá sản phẩm
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Phương thức thanh toán:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                            <input type="checkbox" 
                                   id="payment_cod" 
                                   name="payment_methods[]" 
                                   value="cod"
                                   checked
                                   class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                            <label for="payment_cod" class="ml-2 block text-sm text-gray-900">
                                <strong>COD</strong> - Thanh toán khi nhận hàng
                            </label>
                        </div>
                        
                        <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                            <input type="checkbox" 
                                   id="payment_bank" 
                                   name="payment_methods[]" 
                                   value="bank_transfer"
                                   checked
                                   class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                            <label for="payment_bank" class="ml-2 block text-sm text-gray-900">
                                <strong>Bank Transfer</strong> - Chuyển khoản ngân hàng
                            </label>
                        </div>
                        
                        <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                            <input type="checkbox" 
                                   id="payment_vnpay" 
                                   name="payment_methods[]" 
                                   value="vnpay"
                                   class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                            <label for="payment_vnpay" class="ml-2 block text-sm text-gray-900">
                                <strong>VNPay</strong> - Cổng thanh toán VNPay
                            </label>
                        </div>
                        
                        <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                            <input type="checkbox" 
                                   id="payment_momo" 
                                   name="payment_methods[]" 
                                   value="momo"
                                   class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                            <label for="payment_momo" class="ml-2 block text-sm text-gray-900">
                                <strong>MoMo</strong> - Ví điện tử MoMo
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Sample Data Configuration -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <input type="checkbox" 
                               id="create_sample_data" 
                               name="create_sample_data" 
                               checked
                               class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                        <label for="create_sample_data" class="ml-2 block text-sm font-medium text-gray-900">
                            Tạo dữ liệu mẫu
                        </label>
                    </div>
                    
                    <div id="sample-data-options" class="ml-6 space-y-2 text-sm text-gray-600">
                        <div>• 50 sản phẩm mẫu với hình ảnh</div>
                        <div>• 10 danh mục sản phẩm</div>
                        <div>• 20 đơn hàng mẫu với các trạng thái khác nhau</div>
                        <div>• 30 khách hàng mẫu</div>
                        <div>• Cấu hình thanh toán cơ bản</div>
                    </div>
                </div>

                <!-- Warning Notice -->
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-orange-600 mt-1 mr-3"></i>
                        <div>
                            <h4 class="font-semibold text-orange-800">Lưu ý quan trọng</h4>
                            <p class="text-orange-700 text-sm mt-1">
                                Module E-commerce sẽ tạo nhiều bảng database và cấu hình phức tạp. 
                                Đảm bảo bạn thực sự cần tính năng này trước khi cài đặt.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Skip Option -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-yellow-600 mt-1 mr-3"></i>
                <div>
                    <h4 class="font-semibold text-yellow-800">Module tùy chọn</h4>
                    <p class="text-yellow-700 text-sm mt-1">
                        Module E-commerce phù hợp cho website bán hàng. Nếu bạn chỉ cần website giới thiệu, 
                        có thể bỏ qua module này để giảm độ phức tạp.
                    </p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center">
            <button type="button" 
                    onclick="skipModule()"
                    class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fas fa-skip-forward mr-2"></i>
                Bỏ qua module này
            </button>
            
            <button type="submit" 
                    class="px-8 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                <i class="fas fa-check mr-2"></i>
                Cài đặt module
            </button>
        </div>
    </div>
</form>

<!-- Navigation -->
<div id="navigation-section" class="hidden border-t pt-6 mt-6">
    <div class="flex justify-between">
        <a href="{{ route('setup.step', 'module-content') }}" 
           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Quay lại
        </a>
        
        <button onclick="goToNextStep('module-layout')"
                class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
            Tiếp theo
            <i class="fas fa-arrow-right ml-2"></i>
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
function configureModule(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());
    
    // Convert checkboxes to boolean
    data.enable_module = document.getElementById('enable_module').checked;
    data.create_sample_data = document.getElementById('create_sample_data').checked;
    data.enable_products = document.getElementById('enable_products').checked;
    data.enable_cart = document.getElementById('enable_cart').checked;
    data.enable_orders = document.getElementById('enable_orders').checked;
    data.enable_inventory = document.getElementById('enable_inventory').checked;
    data.enable_payments = document.getElementById('enable_payments').checked;
    data.enable_customers = document.getElementById('enable_customers').checked;
    data.enable_coupons = document.getElementById('enable_coupons').checked;
    data.enable_reviews = document.getElementById('enable_reviews').checked;
    
    // Handle payment methods
    const paymentMethods = [];
    document.querySelectorAll('input[name="payment_methods[]"]:checked').forEach(checkbox => {
        paymentMethods.push(checkbox.value);
    });
    data.payment_methods = paymentMethods;
    
    data.module_key = 'ecommerce';
    
    showLoading('Đang cấu hình E-commerce module...');
    
    submitStep('{{ route('setup.process', 'module-ecommerce') }}', data, (response) => {
        document.getElementById('navigation-section').classList.remove('hidden');
        
        // Auto proceed to next step after 2 seconds
        setTimeout(() => {
            goToNextStep('module-layout');
        }, 2000);
    });
}

function skipModule() {
    const data = {
        enable_module: false,
        create_sample_data: false,
        module_key: 'ecommerce',
        skipped: true
    };
    
    showLoading('Đang bỏ qua module...');
    
    submitStep('{{ route('setup.process', 'module-ecommerce') }}', data, (response) => {
        document.getElementById('navigation-section').classList.remove('hidden');
        
        // Auto proceed to next step after 1 second
        setTimeout(() => {
            goToNextStep('module-layout');
        }, 1000);
    });
}

// Toggle module content and sample data options
document.addEventListener('DOMContentLoaded', function() {
    const enableToggle = document.getElementById('enable_module');
    const moduleContent = document.getElementById('module-content');
    const sampleDataToggle = document.getElementById('create_sample_data');
    const sampleDataOptions = document.getElementById('sample-data-options');
    
    enableToggle.addEventListener('change', function() {
        if (this.checked) {
            moduleContent.style.display = 'block';
        } else {
            moduleContent.style.display = 'none';
        }
    });
    
    sampleDataToggle.addEventListener('change', function() {
        if (this.checked) {
            sampleDataOptions.style.display = 'block';
        } else {
            sampleDataOptions.style.display = 'none';
        }
    });
    
    console.log('E-commerce module page loaded');
});
</script>
@endpush
