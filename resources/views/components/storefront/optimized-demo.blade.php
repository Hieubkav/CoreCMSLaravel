{{-- 
    Component demo cho các tối ưu hóa layout và spacing mới
    Sử dụng tone màu đỏ-trắng minimalist với spacing nhất quán
--}}

<section class="storefront-section bg-white bg-pattern-subtle">
    <div class="storefront-container">
        <!-- Header Section -->
        <div class="text-center mb-12 animate-fade-in-optimized">
            <div class="inline-block relative">
                <h2 class="heading-optimized text-3xl md:text-4xl text-gray-900 mb-4">
                    Tối ưu hóa hoàn tất
                </h2>
                <div class="w-16 h-1 bg-gradient-to-r from-red-600 to-red-700 mx-auto rounded-full"></div>
            </div>
            <p class="text-optimized text-lg text-gray-600 max-w-2xl mx-auto mt-6">
                Layout và spacing đã được tối ưu hóa với tone màu đỏ-trắng minimalist, 
                spacing nhất quán và performance cao.
            </p>
        </div>

        <!-- Features Grid -->
        <div class="grid-responsive-optimized mb-16">
            <!-- Feature 1 -->
            <div class="storefront-card stagger-item">
                <div class="p-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-palette text-white text-xl"></i>
                    </div>
                    <h3 class="heading-optimized text-xl text-gray-900 mb-3">
                        Màu sắc hài hòa
                    </h3>
                    <p class="text-optimized text-gray-600 text-sm">
                        Tone đỏ-trắng minimalist với các biến thể màu được tối ưu hóa cho accessibility và thẩm mỹ.
                    </p>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="storefront-card stagger-item">
                <div class="p-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-ruler-combined text-white text-xl"></i>
                    </div>
                    <h3 class="heading-optimized text-xl text-gray-900 mb-3">
                        Spacing nhất quán
                    </h3>
                    <p class="text-optimized text-gray-600 text-sm">
                        Hệ thống spacing dựa trên grid 8px, đảm bảo tính nhất quán và harmony trong toàn bộ giao diện.
                    </p>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="storefront-card stagger-item">
                <div class="p-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-mobile-alt text-white text-xl"></i>
                    </div>
                    <h3 class="heading-optimized text-xl text-gray-900 mb-3">
                        Responsive tối ưu
                    </h3>
                    <p class="text-optimized text-gray-600 text-sm">
                        Layout tự động điều chỉnh hoàn hảo trên mọi thiết bị với breakpoints được tối ưu hóa.
                    </p>
                </div>
            </div>

            <!-- Feature 4 -->
            <div class="storefront-card stagger-item">
                <div class="p-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-rocket text-white text-xl"></i>
                    </div>
                    <h3 class="heading-optimized text-xl text-gray-900 mb-3">
                        Performance cao
                    </h3>
                    <p class="text-optimized text-gray-600 text-sm">
                        Animations được tối ưu hóa với GPU acceleration và reduced motion support.
                    </p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="text-center animate-fade-in-optimized">
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <button class="btn-primary-optimized">
                    <i class="fas fa-check"></i>
                    <span>Tối ưu hóa hoàn tất</span>
                </button>
                <button class="btn-secondary-optimized">
                    <i class="fas fa-cog"></i>
                    <span>Cài đặt nâng cao</span>
                </button>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="mt-16 bg-gray-25 rounded-2xl p-8 animate-fade-in-optimized">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="text-center stagger-item">
                    <div class="text-3xl font-bold text-red-600 mb-2">100%</div>
                    <div class="text-sm text-gray-600">Tối ưu hóa</div>
                </div>
                <div class="text-center stagger-item">
                    <div class="text-3xl font-bold text-red-600 mb-2">8px</div>
                    <div class="text-sm text-gray-600">Grid system</div>
                </div>
                <div class="text-center stagger-item">
                    <div class="text-3xl font-bold text-red-600 mb-2">60fps</div>
                    <div class="text-sm text-gray-600">Smooth animations</div>
                </div>
                <div class="text-center stagger-item">
                    <div class="text-3xl font-bold text-red-600 mb-2">A+</div>
                    <div class="text-sm text-gray-600">Accessibility</div>
                </div>
            </div>
        </div>

        <!-- Technical Details -->
        <div class="mt-12 animate-fade-in-optimized">
            <div class="bg-white border border-gray-100 rounded-xl p-6">
                <h3 class="heading-optimized text-lg text-gray-900 mb-4">
                    <i class="fas fa-code text-red-600 mr-2"></i>
                    Chi tiết kỹ thuật
                </h3>
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">CSS Optimizations:</h4>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li>• CSS Custom Properties cho color palette</li>
                            <li>• Consistent spacing scale (8px grid)</li>
                            <li>• GPU-accelerated animations</li>
                            <li>• Reduced motion support</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-2">JavaScript Enhancements:</h4>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li>• Intersection Observer API</li>
                            <li>• Debounced scroll handlers</li>
                            <li>• Lazy loading optimizations</li>
                            <li>• Performance monitoring</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    /* Demo-specific styles */
    .demo-highlight {
        background: linear-gradient(135deg, var(--bg-red-25) 0%, var(--bg-red-50) 100%);
        border-left: 4px solid var(--primary-red);
        padding: 1rem;
        border-radius: 0.5rem;
        margin: 1rem 0;
    }

    .demo-code {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 1rem;
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        font-size: 0.875rem;
        color: #334155;
        overflow-x: auto;
    }

    /* Animation demo */
    .demo-animation {
        animation: demoFloat 3s ease-in-out infinite;
    }

    @keyframes demoFloat {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }

    @media (prefers-reduced-motion: reduce) {
        .demo-animation {
            animation: none;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Demo interaction
        const buttons = document.querySelectorAll('.btn-primary-optimized, .btn-secondary-optimized');
        
        buttons.forEach(button => {
            button.addEventListener('click', function() {
                // Add click feedback
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
                
                console.log('✅ Button clicked:', this.textContent.trim());
            });
        });

        // Demo stats counter animation
        const stats = document.querySelectorAll('.text-3xl.font-bold');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        });

        stats.forEach(stat => observer.observe(stat));

        function animateCounter(element) {
            const text = element.textContent;
            const isPercentage = text.includes('%');
            const isFps = text.includes('fps');
            const isGrade = text.includes('+');
            
            if (isPercentage || isFps) {
                const finalValue = parseInt(text);
                let currentValue = 0;
                const increment = finalValue / 30;
                
                const timer = setInterval(() => {
                    currentValue += increment;
                    if (currentValue >= finalValue) {
                        currentValue = finalValue;
                        clearInterval(timer);
                    }
                    element.textContent = Math.floor(currentValue) + (isPercentage ? '%' : 'fps');
                }, 50);
            }
        }
    });
</script>
@endpush
