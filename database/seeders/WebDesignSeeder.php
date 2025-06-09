<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Generated\Models\WebDesign;

class WebDesignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🎨 Bắt đầu tạo web designs...');

        // Xóa dữ liệu cũ
        WebDesign::truncate();

        // Tạo thiết kế mặc định
        $this->createDefaultDesign();
        
        // Tạo thiết kế tối
        $this->createDarkDesign();
        
        // Tạo thiết kế minimalist
        $this->createMinimalistDesign();
        
        // Tạo thiết kế corporate
        $this->createCorporateDesign();
        
        // Tạo thiết kế e-commerce
        $this->createEcommerceDesign();

        $this->command->info('✅ Đã tạo web designs thành công!');
    }

    private function createDefaultDesign()
    {
        $this->command->info('🏠 Tạo thiết kế mặc định...');

        WebDesign::create([
            'name' => 'Modern Red Theme',
            'description' => 'Thiết kế hiện đại với màu đỏ chủ đạo, phù hợp cho các website doanh nghiệp và dịch vụ.',
            'theme_type' => 'modern',
            'color_scheme' => 'light',
            
            // Colors
            'primary_color' => '#dc2626',
            'secondary_color' => '#6b7280',
            'accent_color' => '#f59e0b',
            'background_color' => '#ffffff',
            'text_color' => '#1f2937',
            'link_color' => '#dc2626',
            'border_color' => '#e5e7eb',
            
            // Typography
            'font_family_primary' => 'Inter',
            'font_family_secondary' => 'Inter',
            'font_size_base' => 16,
            'font_weight_normal' => 400,
            'font_weight_bold' => 600,
            'line_height' => '1.6',
            'letter_spacing' => '0',
            
            // Design elements
            'border_radius' => 8,
            'box_shadow' => '0 4px 6px rgba(0, 0, 0, 0.1)',
            'transition_duration' => '300ms',
            'animation_style' => 'smooth',
            
            // Layout
            'layout_style' => 'boxed',
            'container_width' => 1200,
            'sidebar_width' => 300,
            'header_height' => 80,
            'footer_height' => 200,
            'spacing_unit' => 16,
            'grid_columns' => 12,
            
            // Responsive
            'breakpoint_mobile' => 640,
            'breakpoint_tablet' => 768,
            'breakpoint_desktop' => 1024,
            
            // Hero
            'hero_style' => 'gradient',
            'hero_height' => 500,
            'hero_overlay' => true,
            'hero_text_align' => 'center',
            
            // Components
            'button_style' => 'solid',
            'button_size' => 'medium',
            'button_radius' => 8,
            'card_style' => 'elevated',
            'card_shadow' => '0 4px 6px rgba(0, 0, 0, 0.1)',
            'card_radius' => 12,
            'form_style' => 'modern',
            'input_style' => 'outlined',
            'input_radius' => 8,
            
            // Navigation
            'navigation_style' => 'horizontal',
            'navigation_position' => 'sticky',
            'navigation_background' => '#ffffff',
            'footer_style' => 'modern',
            'footer_background' => '#1f2937',
            
            // Status
            'is_active' => true,
            'is_default' => true,
            'order' => 1,
        ]);
    }

    private function createDarkDesign()
    {
        $this->command->info('🌙 Tạo thiết kế tối...');

        WebDesign::create([
            'name' => 'Dark Professional',
            'description' => 'Thiết kế tối chuyên nghiệp với màu sắc tương phản cao, phù hợp cho các website công nghệ và sáng tạo.',
            'theme_type' => 'modern',
            'color_scheme' => 'dark',
            
            // Colors - Dark theme
            'primary_color' => '#3b82f6',
            'secondary_color' => '#64748b',
            'accent_color' => '#10b981',
            'background_color' => '#0f172a',
            'text_color' => '#f1f5f9',
            'link_color' => '#3b82f6',
            'border_color' => '#334155',
            
            // Typography
            'font_family_primary' => 'Inter',
            'font_family_secondary' => 'Inter',
            'font_size_base' => 16,
            'font_weight_normal' => 400,
            'font_weight_bold' => 600,
            'line_height' => '1.6',
            
            // Design elements
            'border_radius' => 12,
            'box_shadow' => '0 8px 25px rgba(0, 0, 0, 0.3)',
            'transition_duration' => '250ms',
            'animation_style' => 'smooth',
            
            // Layout
            'layout_style' => 'boxed',
            'container_width' => 1200,
            'header_height' => 80,
            'footer_height' => 250,
            
            // Hero
            'hero_style' => 'gradient',
            'hero_height' => 600,
            'hero_overlay' => true,
            'hero_text_align' => 'center',
            
            // Components
            'button_style' => 'gradient',
            'button_size' => 'medium',
            'button_radius' => 12,
            'card_style' => 'elevated',
            'card_shadow' => '0 8px 25px rgba(0, 0, 0, 0.3)',
            'card_radius' => 16,
            'input_style' => 'filled',
            'input_radius' => 12,
            
            // Navigation
            'navigation_style' => 'horizontal',
            'navigation_position' => 'sticky',
            'navigation_background' => '#0f172a',
            'footer_background' => '#020617',
            
            // Status
            'is_active' => false,
            'is_default' => false,
            'order' => 2,
        ]);
    }

    private function createMinimalistDesign()
    {
        $this->command->info('✨ Tạo thiết kế minimalist...');

        WebDesign::create([
            'name' => 'Clean Minimalist',
            'description' => 'Thiết kế tối giản với nhiều khoảng trắng và typography sạch sẽ, phù hợp cho blog và portfolio.',
            'theme_type' => 'minimalist',
            'color_scheme' => 'light',
            
            // Colors - Minimal palette
            'primary_color' => '#000000',
            'secondary_color' => '#6b7280',
            'accent_color' => '#ef4444',
            'background_color' => '#ffffff',
            'text_color' => '#111827',
            'link_color' => '#000000',
            'border_color' => '#f3f4f6',
            
            // Typography - Clean fonts
            'font_family_primary' => 'Source Sans Pro',
            'font_family_secondary' => 'Merriweather',
            'font_size_base' => 18,
            'font_weight_normal' => 300,
            'font_weight_bold' => 600,
            'line_height' => '1.8',
            'letter_spacing' => '0.025em',
            
            // Design elements - Minimal
            'border_radius' => 0,
            'box_shadow' => 'none',
            'transition_duration' => '200ms',
            'animation_style' => 'fade',
            
            // Layout - More spacing
            'layout_style' => 'boxed',
            'container_width' => 1000,
            'header_height' => 100,
            'footer_height' => 150,
            'spacing_unit' => 24,
            
            // Hero - Simple
            'hero_style' => 'solid',
            'hero_height' => 400,
            'hero_overlay' => false,
            'hero_text_align' => 'left',
            
            // Components - Minimal
            'button_style' => 'outlined',
            'button_size' => 'medium',
            'button_radius' => 0,
            'card_style' => 'flat',
            'card_radius' => 0,
            'input_style' => 'underlined',
            'input_radius' => 0,
            
            // Navigation - Simple
            'navigation_style' => 'horizontal',
            'navigation_position' => 'top',
            'navigation_background' => 'transparent',
            'footer_background' => '#f9fafb',
            
            // Status
            'is_active' => false,
            'is_default' => false,
            'order' => 3,
        ]);
    }

    private function createCorporateDesign()
    {
        $this->command->info('🏢 Tạo thiết kế corporate...');

        WebDesign::create([
            'name' => 'Corporate Blue',
            'description' => 'Thiết kế doanh nghiệp chuyên nghiệp với màu xanh dương, phù hợp cho các công ty và tổ chức lớn.',
            'theme_type' => 'corporate',
            'color_scheme' => 'light',
            
            // Colors - Corporate blue
            'primary_color' => '#1e40af',
            'secondary_color' => '#64748b',
            'accent_color' => '#059669',
            'background_color' => '#ffffff',
            'text_color' => '#1e293b',
            'link_color' => '#1e40af',
            'border_color' => '#e2e8f0',
            
            // Typography - Professional
            'font_family_primary' => 'Roboto',
            'font_family_secondary' => 'Roboto',
            'font_size_base' => 16,
            'font_weight_normal' => 400,
            'font_weight_bold' => 700,
            'line_height' => '1.5',
            
            // Design elements - Conservative
            'border_radius' => 4,
            'box_shadow' => '0 2px 4px rgba(0, 0, 0, 0.1)',
            'transition_duration' => '150ms',
            'animation_style' => 'smooth',
            
            // Layout - Traditional
            'layout_style' => 'boxed',
            'container_width' => 1140,
            'header_height' => 90,
            'footer_height' => 300,
            'spacing_unit' => 16,
            
            // Hero - Professional
            'hero_style' => 'image',
            'hero_height' => 450,
            'hero_overlay' => true,
            'hero_text_align' => 'left',
            
            // Components - Conservative
            'button_style' => 'solid',
            'button_size' => 'medium',
            'button_radius' => 4,
            'card_style' => 'outlined',
            'card_radius' => 8,
            'input_style' => 'outlined',
            'input_radius' => 4,
            
            // Navigation - Traditional
            'navigation_style' => 'horizontal',
            'navigation_position' => 'top',
            'navigation_background' => '#ffffff',
            'footer_background' => '#1e293b',
            
            // Status
            'is_active' => false,
            'is_default' => false,
            'order' => 4,
        ]);
    }

    private function createEcommerceDesign()
    {
        $this->command->info('🛒 Tạo thiết kế e-commerce...');

        WebDesign::create([
            'name' => 'E-commerce Orange',
            'description' => 'Thiết kế thương mại điện tử với màu cam nổi bật, tối ưu cho việc bán hàng và chuyển đổi.',
            'theme_type' => 'ecommerce',
            'color_scheme' => 'light',
            
            // Colors - E-commerce optimized
            'primary_color' => '#ea580c',
            'secondary_color' => '#6b7280',
            'accent_color' => '#16a34a',
            'background_color' => '#ffffff',
            'text_color' => '#1f2937',
            'link_color' => '#ea580c',
            'border_color' => '#e5e7eb',
            
            // Typography - Readable
            'font_family_primary' => 'Open Sans',
            'font_family_secondary' => 'Open Sans',
            'font_size_base' => 16,
            'font_weight_normal' => 400,
            'font_weight_bold' => 600,
            'line_height' => '1.5',
            
            // Design elements - Friendly
            'border_radius' => 8,
            'box_shadow' => '0 4px 6px rgba(0, 0, 0, 0.1)',
            'transition_duration' => '200ms',
            'animation_style' => 'bounce',
            
            // Layout - Product focused
            'layout_style' => 'full-width',
            'container_width' => 1280,
            'header_height' => 70,
            'footer_height' => 250,
            'spacing_unit' => 16,
            'grid_columns' => 12,
            
            // Hero - Product showcase
            'hero_style' => 'gradient',
            'hero_height' => 400,
            'hero_overlay' => false,
            'hero_text_align' => 'center',
            
            // Components - Conversion optimized
            'button_style' => 'solid',
            'button_size' => 'large',
            'button_radius' => 8,
            'card_style' => 'elevated',
            'card_shadow' => '0 4px 6px rgba(0, 0, 0, 0.1)',
            'card_radius' => 12,
            'input_style' => 'outlined',
            'input_radius' => 8,
            
            // Navigation - E-commerce
            'navigation_style' => 'horizontal',
            'navigation_position' => 'sticky',
            'navigation_background' => '#ffffff',
            'footer_background' => '#374151',
            
            // Status
            'is_active' => false,
            'is_default' => false,
            'order' => 5,
        ]);
    }
}
