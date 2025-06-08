<div class="container mx-auto px-4">
    <!-- Animated Stats Counters -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
        <div class="text-center p-6 rounded-lg bg-white shadow-lg hover:shadow-xl transition-shadow border-t-4 border-red-600 stagger-item">
            <div class="text-4xl font-bold text-red-700 counter" data-target="8500">0</div>
            <p class="text-gray-600 mt-2">Khách hàng</p>
        </div>
        <div class="text-center p-6 rounded-lg bg-white shadow-lg hover:shadow-xl transition-shadow border-t-4 border-red-600 stagger-item">
            <div class="text-4xl font-bold text-red-700 counter" data-target="150">0</div>
            <p class="text-gray-600 mt-2">Đối tác</p>
        </div>
        <div class="text-center p-6 rounded-lg bg-white shadow-lg hover:shadow-xl transition-shadow border-t-4 border-red-600 stagger-item">
            <div class="text-4xl font-bold text-red-700 counter" data-target="1200">0</div>
            <p class="text-gray-600 mt-2">Sản phẩm</p>
        </div>
        <div class="text-center p-6 rounded-lg bg-white shadow-lg hover:shadow-xl transition-shadow border-t-4 border-red-600 stagger-item">
            <div class="text-4xl font-bold text-red-700 counter" data-target="63">0</div>
            <p class="text-gray-600 mt-2">Khu vực phân phối</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Counter animation
    document.addEventListener('DOMContentLoaded', function() {
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counters = entry.target.querySelectorAll('.counter');
                    counters.forEach(counter => {
                        const target = parseInt(counter.getAttribute('data-target'));
                        const duration = 2000; // Animation duration in ms
                        const increment = target / (duration / 16); // Update every 16ms for smooth animation

                        let current = 0;
                        const updateCounter = () => {
                            current += increment;
                            if (current < target) {
                                counter.textContent = Math.ceil(current);
                                requestAnimationFrame(updateCounter);
                            } else {
                                counter.textContent = target.toLocaleString();
                            }
                        };
                        updateCounter();
                    });
                    counterObserver.unobserve(entry.target);
                }
            });
        }, observerOptions);

        const statsSection = document.querySelector('.grid.grid-cols-2.md\\:grid-cols-4');
        if (statsSection) {
            counterObserver.observe(statsSection);
        }
    });
</script>
@endpush
