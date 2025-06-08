import './bootstrap';
import 'preline';
import 'flowbite';

import AOS from 'aos';
import 'aos/dist/aos.css';

// Import Swiper
import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

// Swiper initialization
Swiper.use([Navigation, Pagination, Autoplay]);

// Make Swiper available globally
window.Swiper = Swiper;

// Khởi tạo AOS với cấu hình tối ưu
AOS.init({
    duration: 600,
    easing: 'ease-in-out',
    once: true,  // Chỉ chạy 1 lần để tối ưu performance
    mirror: false,  // Tắt mirror để giảm tải
    offset: 50,  // Trigger sớm hơn
    delay: 0,  // Không delay
    disable: 'mobile'  // Tắt trên mobile để tối ưu
});

// Khởi tạo drawer menu với lazy loading
document.addEventListener('DOMContentLoaded', function() {
    const drawer = document.getElementById('drawer-navigation');
    if (drawer && typeof Drawer !== 'undefined') {
        const initDrawer = new Drawer(drawer);
    }
});
