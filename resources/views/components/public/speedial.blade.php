<!-- Scroll to top button -->
<button id="scroll-to-top" class="fixed bottom-20 right-6 z-30 bg-blue-600 text-white rounded-full w-12 h-12 shadow-lg flex items-center justify-center hover:bg-blue-700 transition-all opacity-0 invisible" aria-label="Scroll to top">
    <i class="fas fa-chevron-up"></i>
</button>

<!-- Contact buttons -->
@if(isset($settings) && $settings)
<div class="fixed bottom-6 right-6 z-40">
    <div class="flex flex-col-reverse items-end space-y-2 space-y-reverse">
        @if($settings->hotline)
        <a href="tel:{{ $settings->hotline }}" class="bg-green-500 text-white rounded-full w-12 h-12 shadow-lg flex items-center justify-center hover:bg-green-600 transition-all group" aria-label="Call">
            <i class="fas fa-phone"></i>
            <span class="absolute right-14 bg-gray-800 text-white text-sm px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                {{ $settings->hotline }}
            </span>
        </a>
        @endif

        @if($settings->email)
        <a href="mailto:{{ $settings->email }}" class="bg-red-500 text-white rounded-full w-12 h-12 shadow-lg flex items-center justify-center hover:bg-red-600 transition-all group" aria-label="Email">
            <i class="fas fa-envelope"></i>
            <span class="absolute right-14 bg-gray-800 text-white text-sm px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                Email
            </span>
        </a>
        @endif
    </div>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const scrollToTopBtn = document.getElementById('scroll-to-top');

    function toggleScrollButton() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > 300) {
            scrollToTopBtn.classList.remove('opacity-0', 'invisible');
            scrollToTopBtn.classList.add('opacity-100', 'visible');
        } else {
            scrollToTopBtn.classList.add('opacity-0', 'invisible');
            scrollToTopBtn.classList.remove('opacity-100', 'visible');
        }
    }

    if (scrollToTopBtn) {
        scrollToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    window.addEventListener('scroll', toggleScrollButton);
    toggleScrollButton();
});
</script>

