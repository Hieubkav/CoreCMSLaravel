<?php

namespace App\Generated\Actions;

use App\Generated\Models\WebDesign;
use Illuminate\Support\Facades\Cache;
use Lorisleiva\Actions\Concerns\AsAction;

class GenerateDesignCssAction
{
    use AsAction;

    /**
     * Generate CSS for a web design
     */
    public function handle(WebDesign $design): string
    {
        return Cache::remember("web_design_css_{$design->id}", 3600, function () use ($design) {
            return $this->generateCss($design);
        });
    }

    /**
     * Generate CSS content
     */
    private function generateCss(WebDesign $design): string
    {
        $css = "/* Generated CSS for {$design->name} */\n\n";
        
        // CSS Variables
        $css .= ":root {\n";
        foreach ($design->getCssVariables() as $variable => $value) {
            $css .= "  {$variable}: {$value};\n";
        }
        $css .= "}\n\n";
        
        // Base styles
        $css .= $this->generateBaseStyles($design);
        
        // Layout styles
        $css .= $this->generateLayoutStyles($design);
        
        // Component styles
        $css .= $this->generateComponentStyles($design);
        
        // Navigation styles
        $css .= $this->generateNavigationStyles($design);
        
        // Hero styles
        $css .= $this->generateHeroStyles($design);
        
        // Responsive styles
        $css .= $this->generateResponsiveStyles($design);
        
        // Custom CSS
        if ($design->custom_css) {
            $css .= "\n/* Custom CSS */\n";
            $css .= $design->custom_css . "\n";
        }
        
        return $css;
    }

    /**
     * Generate base styles
     */
    private function generateBaseStyles(WebDesign $design): string
    {
        return "
/* Base Styles */
* {
  box-sizing: border-box;
}

body {
  font-family: var(--font-family-primary), sans-serif;
  font-size: var(--font-size-base);
  font-weight: var(--font-weight-normal);
  line-height: var(--line-height);
  letter-spacing: var(--letter-spacing);
  color: var(--text-color);
  background-color: var(--background-color);
  margin: 0;
  padding: 0;
  transition: all var(--transition-duration) ease;
}

h1, h2, h3, h4, h5, h6 {
  font-family: var(--font-family-secondary), sans-serif;
  font-weight: var(--font-weight-bold);
  color: var(--text-color);
  margin: 0 0 1rem 0;
}

a {
  color: var(--link-color);
  text-decoration: none;
  transition: all var(--transition-duration) ease;
}

a:hover {
  opacity: 0.8;
}

img {
  max-width: 100%;
  height: auto;
}

";
    }

    /**
     * Generate layout styles
     */
    private function generateLayoutStyles(WebDesign $design): string
    {
        $containerStyle = $design->layout_style === 'full-width' ? 'width: 100%;' : 'max-width: var(--container-width);';
        
        return "
/* Layout Styles */
.container {
  {$containerStyle}
  margin: 0 auto;
  padding: 0 var(--spacing-unit);
}

.header {
  height: var(--header-height);
  background: var(--navigation-background);
  position: " . ($design->navigation_position === 'sticky' ? 'sticky' : 'relative') . ";
  top: 0;
  z-index: 1000;
}

.footer {
  min-height: var(--footer-height);
  background: var(--footer-background);
  color: white;
  padding: calc(var(--spacing-unit) * 2) 0;
}

.sidebar {
  width: var(--sidebar-width);
  background: var(--background-color);
  border-right: 1px solid var(--border-color);
}

.grid {
  display: grid;
  grid-template-columns: repeat(var(--grid-columns), 1fr);
  gap: var(--spacing-unit);
}

";
    }

    /**
     * Generate component styles
     */
    private function generateComponentStyles(WebDesign $design): string
    {
        $buttonStyles = $this->getButtonStyles($design);
        $cardStyles = $this->getCardStyles($design);
        $formStyles = $this->getFormStyles($design);
        
        return "
/* Component Styles */

/* Buttons */
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: " . $this->getButtonPadding($design->button_size) . ";
  font-size: " . $this->getButtonFontSize($design->button_size) . ";
  font-weight: var(--font-weight-bold);
  border: none;
  border-radius: var(--button-radius);
  cursor: pointer;
  transition: all var(--transition-duration) ease;
  text-decoration: none;
  {$buttonStyles}
}

.btn:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Cards */
.card {
  background: var(--background-color);
  border-radius: var(--card-radius);
  padding: calc(var(--spacing-unit) * 1.5);
  transition: all var(--transition-duration) ease;
  {$cardStyles}
}

.card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

/* Forms */
.form-group {
  margin-bottom: var(--spacing-unit);
}

.form-label {
  display: block;
  margin-bottom: calc(var(--spacing-unit) / 2);
  font-weight: var(--font-weight-bold);
  color: var(--text-color);
}

.form-input {
  width: 100%;
  padding: calc(var(--spacing-unit) / 1.5);
  border-radius: var(--input-radius);
  font-size: var(--font-size-base);
  transition: all var(--transition-duration) ease;
  {$formStyles}
}

.form-input:focus {
  outline: none;
  border-color: var(--primary-color);
  box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
}

";
    }

    /**
     * Generate navigation styles
     */
    private function generateNavigationStyles(WebDesign $design): string
    {
        $navDirection = $design->navigation_style === 'vertical' ? 'column' : 'row';
        
        return "
/* Navigation Styles */
.navbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 var(--spacing-unit);
  height: 100%;
}

.nav-menu {
  display: flex;
  flex-direction: {$navDirection};
  list-style: none;
  margin: 0;
  padding: 0;
  gap: calc(var(--spacing-unit) * 1.5);
}

.nav-item {
  position: relative;
}

.nav-link {
  display: block;
  padding: calc(var(--spacing-unit) / 2) var(--spacing-unit);
  color: var(--text-color);
  font-weight: var(--font-weight-normal);
  border-radius: calc(var(--border-radius) / 2);
  transition: all var(--transition-duration) ease;
}

.nav-link:hover,
.nav-link.active {
  background: var(--primary-color);
  color: white;
}

.dropdown-menu {
  position: absolute;
  top: 100%;
  left: 0;
  background: var(--background-color);
  border: 1px solid var(--border-color);
  border-radius: var(--border-radius);
  box-shadow: var(--box-shadow);
  min-width: 200px;
  opacity: 0;
  visibility: hidden;
  transform: translateY(-10px);
  transition: all var(--transition-duration) ease;
}

.nav-item:hover .dropdown-menu {
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
}

";
    }

    /**
     * Generate hero styles
     */
    private function generateHeroStyles(WebDesign $design): string
    {
        $heroBackground = $this->getHeroBackground($design);
        $textAlign = $design->hero_text_align;
        
        return "
/* Hero Styles */
.hero {
  height: var(--hero-height);
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: {$textAlign};
  position: relative;
  overflow: hidden;
  {$heroBackground}
}

.hero-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.4);
  z-index: 1;
}

.hero-content {
  position: relative;
  z-index: 2;
  color: white;
  max-width: 800px;
  padding: 0 var(--spacing-unit);
}

.hero-title {
  font-size: clamp(2rem, 5vw, 4rem);
  font-weight: var(--font-weight-bold);
  margin-bottom: var(--spacing-unit);
}

.hero-subtitle {
  font-size: clamp(1.1rem, 2.5vw, 1.5rem);
  margin-bottom: calc(var(--spacing-unit) * 2);
  opacity: 0.9;
}

";
    }

    /**
     * Generate responsive styles
     */
    private function generateResponsiveStyles(WebDesign $design): string
    {
        return "
/* Responsive Styles */
@media (max-width: {$design->breakpoint_tablet}px) {
  .container {
    padding: 0 calc(var(--spacing-unit) / 2);
  }
  
  .nav-menu {
    flex-direction: column;
    gap: calc(var(--spacing-unit) / 2);
  }
  
  .hero-title {
    font-size: 2.5rem;
  }
  
  .grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: {$design->breakpoint_mobile}px) {
  .hero {
    height: calc(var(--hero-height) * 0.7);
  }
  
  .hero-title {
    font-size: 2rem;
  }
  
  .btn {
    width: 100%;
    justify-content: center;
  }
}

";
    }

    /**
     * Get button styles based on design
     */
    private function getButtonStyles(WebDesign $design): string
    {
        switch ($design->button_style) {
            case 'outlined':
                return "background: transparent; border: 2px solid var(--primary-color); color: var(--primary-color);";
            case 'ghost':
                return "background: transparent; border: none; color: var(--primary-color);";
            case 'gradient':
                return "background: linear-gradient(135deg, var(--primary-color), var(--accent-color)); color: white;";
            default: // solid
                return "background: var(--primary-color); color: white;";
        }
    }

    /**
     * Get card styles based on design
     */
    private function getCardStyles(WebDesign $design): string
    {
        switch ($design->card_style) {
            case 'flat':
                return "border: none; box-shadow: none;";
            case 'outlined':
                return "border: 1px solid var(--border-color); box-shadow: none;";
            case 'filled':
                return "background: var(--secondary-color); border: none; box-shadow: none;";
            default: // elevated
                return "border: none; box-shadow: var(--card-shadow);";
        }
    }

    /**
     * Get form styles based on design
     */
    private function getFormStyles(WebDesign $design): string
    {
        switch ($design->input_style) {
            case 'filled':
                return "background: var(--border-color); border: none;";
            case 'underlined':
                return "background: transparent; border: none; border-bottom: 2px solid var(--border-color);";
            default: // outlined
                return "background: var(--background-color); border: 1px solid var(--border-color);";
        }
    }

    /**
     * Get hero background based on style
     */
    private function getHeroBackground(WebDesign $design): string
    {
        switch ($design->hero_style) {
            case 'gradient':
                return "background: linear-gradient(135deg, var(--primary-color), var(--accent-color));";
            case 'solid':
                return "background: var(--primary-color);";
            case 'pattern':
                return "background: var(--primary-color); background-image: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><circle cx=\"50\" cy=\"50\" r=\"2\" fill=\"white\" opacity=\"0.1\"/></svg>');";
            default:
                return "background: var(--primary-color);";
        }
    }

    /**
     * Get button padding based on size
     */
    private function getButtonPadding(string $size): string
    {
        switch ($size) {
            case 'small':
                return "0.5rem 1rem";
            case 'large':
                return "1rem 2rem";
            case 'extra-large':
                return "1.25rem 2.5rem";
            default: // medium
                return "0.75rem 1.5rem";
        }
    }

    /**
     * Get button font size based on size
     */
    private function getButtonFontSize(string $size): string
    {
        switch ($size) {
            case 'small':
                return "0.875rem";
            case 'large':
                return "1.125rem";
            case 'extra-large':
                return "1.25rem";
            default: // medium
                return "1rem";
        }
    }
}
