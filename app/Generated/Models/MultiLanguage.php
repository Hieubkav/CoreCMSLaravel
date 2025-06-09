<?php

namespace App\Generated\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;

class MultiLanguage extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'key',
        'language_code',
        'value',
        'group',
        'description',
        'is_active',
        'is_default',
        'order',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Default values for attributes.
     */
    protected $attributes = [
        'is_active' => true,
        'is_default' => false,
        'order' => 0,
        'group' => 'general',
    ];

    /**
     * Supported languages
     */
    public static function getSupportedLanguages(): array
    {
        return [
            'vi' => [
                'name' => 'Tiếng Việt',
                'native_name' => 'Tiếng Việt',
                'flag' => '🇻🇳',
                'direction' => 'ltr',
                'enabled' => true,
            ],
            'en' => [
                'name' => 'English',
                'native_name' => 'English',
                'flag' => '🇺🇸',
                'direction' => 'ltr',
                'enabled' => true,
            ],
            'zh' => [
                'name' => 'Chinese',
                'native_name' => '中文',
                'flag' => '🇨🇳',
                'direction' => 'ltr',
                'enabled' => false,
            ],
            'ja' => [
                'name' => 'Japanese',
                'native_name' => '日本語',
                'flag' => '🇯🇵',
                'direction' => 'ltr',
                'enabled' => false,
            ],
            'ko' => [
                'name' => 'Korean',
                'native_name' => '한국어',
                'flag' => '🇰🇷',
                'direction' => 'ltr',
                'enabled' => false,
            ],
            'th' => [
                'name' => 'Thai',
                'native_name' => 'ไทย',
                'flag' => '🇹🇭',
                'direction' => 'ltr',
                'enabled' => false,
            ],
            'fr' => [
                'name' => 'French',
                'native_name' => 'Français',
                'flag' => '🇫🇷',
                'direction' => 'ltr',
                'enabled' => false,
            ],
            'de' => [
                'name' => 'German',
                'native_name' => 'Deutsch',
                'flag' => '🇩🇪',
                'direction' => 'ltr',
                'enabled' => false,
            ],
            'es' => [
                'name' => 'Spanish',
                'native_name' => 'Español',
                'flag' => '🇪🇸',
                'direction' => 'ltr',
                'enabled' => false,
            ],
            'ar' => [
                'name' => 'Arabic',
                'native_name' => 'العربية',
                'flag' => '🇸🇦',
                'direction' => 'rtl',
                'enabled' => false,
            ],
        ];
    }

    /**
     * Translation groups
     */
    public static function getTranslationGroups(): array
    {
        return [
            'general' => 'Chung',
            'navigation' => 'Điều hướng',
            'forms' => 'Biểu mẫu',
            'messages' => 'Thông báo',
            'errors' => 'Lỗi',
            'validation' => 'Xác thực',
            'auth' => 'Xác thực',
            'admin' => 'Quản trị',
            'frontend' => 'Giao diện',
            'email' => 'Email',
            'seo' => 'SEO',
            'ecommerce' => 'Thương mại điện tử',
            'blog' => 'Blog',
            'custom' => 'Tùy chỉnh',
        ];
    }

    /**
     * Get enabled languages
     */
    public static function getEnabledLanguages(): array
    {
        return Cache::remember('enabled_languages', 3600, function () {
            $languages = static::getSupportedLanguages();
            return array_filter($languages, function ($lang) {
                return $lang['enabled'];
            });
        });
    }

    /**
     * Get default language
     */
    public static function getDefaultLanguage(): string
    {
        return Cache::remember('default_language', 3600, function () {
            $setting = static::where('key', 'default_language')
                ->where('is_active', true)
                ->first();
            
            return $setting ? $setting->value : 'vi';
        });
    }

    /**
     * Get current language
     */
    public static function getCurrentLanguage(): string
    {
        return App::getLocale();
    }

    /**
     * Set current language
     */
    public static function setCurrentLanguage(string $languageCode): void
    {
        if (array_key_exists($languageCode, static::getSupportedLanguages())) {
            App::setLocale($languageCode);
            session(['language' => $languageCode]);
        }
    }

    /**
     * Get translation by key
     */
    public static function trans(string $key, string $languageCode = null, string $default = null): string
    {
        $languageCode = $languageCode ?: static::getCurrentLanguage();
        
        $cacheKey = "translation_{$languageCode}_{$key}";
        
        return Cache::remember($cacheKey, 3600, function () use ($key, $languageCode, $default) {
            $translation = static::where('key', $key)
                ->where('language_code', $languageCode)
                ->where('is_active', true)
                ->first();
            
            if ($translation) {
                return $translation->value;
            }
            
            // Fallback to default language
            $defaultLang = static::getDefaultLanguage();
            if ($languageCode !== $defaultLang) {
                $fallback = static::where('key', $key)
                    ->where('language_code', $defaultLang)
                    ->where('is_active', true)
                    ->first();
                
                if ($fallback) {
                    return $fallback->value;
                }
            }
            
            return $default ?: $key;
        });
    }

    /**
     * Set translation
     */
    public static function setTranslation(string $key, string $value, string $languageCode = null, string $group = 'general'): self
    {
        $languageCode = $languageCode ?: static::getCurrentLanguage();
        
        $translation = static::updateOrCreate(
            [
                'key' => $key,
                'language_code' => $languageCode,
            ],
            [
                'value' => $value,
                'group' => $group,
                'is_active' => true,
            ]
        );
        
        // Clear cache
        static::clearTranslationCache($key, $languageCode);
        
        return $translation;
    }

    /**
     * Get all translations for a group
     */
    public static function getGroupTranslations(string $group, string $languageCode = null): array
    {
        $languageCode = $languageCode ?: static::getCurrentLanguage();
        
        $cacheKey = "group_translations_{$languageCode}_{$group}";
        
        return Cache::remember($cacheKey, 3600, function () use ($group, $languageCode) {
            return static::where('group', $group)
                ->where('language_code', $languageCode)
                ->where('is_active', true)
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    /**
     * Import translations from array
     */
    public static function importTranslations(array $translations, string $languageCode, string $group = 'general'): int
    {
        $imported = 0;
        
        foreach ($translations as $key => $value) {
            static::setTranslation($key, $value, $languageCode, $group);
            $imported++;
        }
        
        return $imported;
    }

    /**
     * Export translations to array
     */
    public static function exportTranslations(string $languageCode = null, string $group = null): array
    {
        $languageCode = $languageCode ?: static::getCurrentLanguage();
        
        $query = static::where('language_code', $languageCode)
            ->where('is_active', true);
        
        if ($group) {
            $query->where('group', $group);
        }
        
        return $query->pluck('value', 'key')->toArray();
    }

    /**
     * Get missing translations
     */
    public static function getMissingTranslations(string $sourceLanguage = 'vi', string $targetLanguage = 'en'): array
    {
        $sourceKeys = static::where('language_code', $sourceLanguage)
            ->where('is_active', true)
            ->pluck('key')
            ->toArray();
        
        $targetKeys = static::where('language_code', $targetLanguage)
            ->where('is_active', true)
            ->pluck('key')
            ->toArray();
        
        return array_diff($sourceKeys, $targetKeys);
    }

    /**
     * Get translation statistics
     */
    public static function getTranslationStats(): array
    {
        $stats = [];
        $languages = static::getSupportedLanguages();
        
        foreach ($languages as $code => $language) {
            if (!$language['enabled']) continue;
            
            $stats[$code] = [
                'language' => $language,
                'total_translations' => static::where('language_code', $code)
                    ->where('is_active', true)
                    ->count(),
                'groups' => static::where('language_code', $code)
                    ->where('is_active', true)
                    ->distinct('group')
                    ->count('group'),
            ];
        }
        
        return $stats;
    }

    /**
     * Clear translation cache
     */
    public static function clearTranslationCache(string $key = null, string $languageCode = null): void
    {
        if ($key && $languageCode) {
            Cache::forget("translation_{$languageCode}_{$key}");
        } else {
            // Clear all translation caches
            Cache::forget('enabled_languages');
            Cache::forget('default_language');
            
            $languages = array_keys(static::getSupportedLanguages());
            $groups = array_keys(static::getTranslationGroups());
            
            foreach ($languages as $lang) {
                foreach ($groups as $group) {
                    Cache::forget("group_translations_{$lang}_{$group}");
                }
            }
        }
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // Clear cache when translation is updated
        static::saved(function ($model) {
            static::clearTranslationCache($model->key, $model->language_code);
        });

        static::deleted(function ($model) {
            static::clearTranslationCache($model->key, $model->language_code);
        });

        // Auto-increment order for new records
        static::creating(function ($model) {
            if (is_null($model->order)) {
                $maxOrder = static::where('language_code', $model->language_code)
                    ->where('group', $model->group)
                    ->max('order');
                $model->order = ($maxOrder ?? 0) + 1;
            }
        });
    }
}
