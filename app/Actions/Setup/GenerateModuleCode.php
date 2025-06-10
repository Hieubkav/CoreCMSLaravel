<?php

namespace App\Actions\Setup;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Exception;

class GenerateModuleCode
{
    /**
     * Generate code cho module được chọn
     */
    public static function handle(string $moduleName, array $moduleConfig = []): array
    {
        try {
            $results = [];
            
            switch ($moduleName) {
                case 'blog':
                    $results = self::generateBlogModule($moduleConfig);
                    break;
                    
                case 'staff':
                    $results = self::generateStaffModule($moduleConfig);
                    break;
                    
                case 'content_sections':
                    $results = self::generateContentSectionsModule($moduleConfig);
                    break;
                    
                case 'layout_components':
                    $results = self::generateLayoutComponentsModule($moduleConfig);
                    break;
                    
                case 'ecommerce':
                    $results = self::generateEcommerceModule($moduleConfig);
                    break;
                    
                case 'user_roles':
                    $results = self::generateUserRolesModule($moduleConfig);
                    break;
                    
                case 'settings_expansion':
                    $results = self::generateSettingsExpansionModule($moduleConfig);
                    break;
                    
                case 'web_design_management':
                    $results = self::generateWebDesignModule($moduleConfig);
                    break;
                    
                default:
                    throw new Exception("Module không được hỗ trợ: {$moduleName}");
            }
            
            return [
                'success' => true,
                'module' => $moduleName,
                'results' => $results
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'module' => $moduleName,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Generate Blog Module
     */
    private static function generateBlogModule(array $config): array
    {
        $results = [];
        
        // 1. Generate migrations
        $migrations = [
            'create_post_categories_table' => 'post_categories',
            'create_posts_table' => 'posts',
            'create_post_images_table' => 'post_images',
        ];
        
        foreach ($migrations as $name => $tableName) {
            $content = self::generateBasicMigration($tableName);
            $filename = date('Y_m_d_His') . '_' . $name . '.php';
            $path = database_path('migrations/' . $filename);
            File::put($path, $content);
            $results['migrations'][] = $filename;
        }
        
        // 2. Generate Models
        $models = ['Post', 'PostCategory', 'PostImage'];
        
        foreach ($models as $name) {
            $content = self::generateBasicModel($name);
            $path = app_path("Generated/Models/{$name}.php");
            File::ensureDirectoryExists(dirname($path));
            File::put($path, $content);
            $results['models'][] = $name;
        }
        
        // 3. Generate Filament Resources
        $resources = ['PostResource', 'PostCategoryResource'];
        
        foreach ($resources as $name) {
            $content = self::generateBasicResource($name);
            $path = app_path("Generated/Filament/Resources/{$name}.php");
            File::ensureDirectoryExists(dirname($path));
            File::put($path, $content);
            $results['resources'][] = $name;
        }
        
        // 4. Generate Observers
        $observers = ['PostObserver'];
        
        foreach ($observers as $name) {
            $content = self::generateBasicObserver($name);
            $path = app_path("Observers/{$name}.php");
            File::put($path, $content);
            $results['observers'][] = $name;
        }
        
        // 5. Generate Seeders
        $seeders = ['BlogModuleSeeder'];
        
        foreach ($seeders as $name) {
            $content = self::generateBasicSeeder($name);
            $path = database_path("seeders/{$name}.php");
            File::put($path, $content);
            $results['seeders'][] = $name;
        }
        
        return $results;
    }

    /**
     * Generate Staff Module
     */
    private static function generateStaffModule(array $config): array
    {
        $results = [];
        
        // Generate migrations
        $migrations = [
            'create_staff_table' => 'staff',
            'create_staff_images_table' => 'staff_images',
        ];
        
        foreach ($migrations as $name => $tableName) {
            $content = self::generateBasicMigration($tableName);
            $filename = date('Y_m_d_His') . '_' . $name . '.php';
            $path = database_path('migrations/' . $filename);
            File::put($path, $content);
            $results['migrations'][] = $filename;
        }
        
        // Generate Models
        $models = ['Staff'];
        
        foreach ($models as $name) {
            $content = self::generateBasicModel($name);
            $path = app_path("Generated/Models/{$name}.php");
            File::ensureDirectoryExists(dirname($path));
            File::put($path, $content);
            $results['models'][] = $name;
        }
        
        // Generate Filament Resources
        $resources = ['StaffResource'];
        
        foreach ($resources as $name) {
            $content = self::generateBasicResource($name);
            $path = app_path("Generated/Filament/Resources/{$name}.php");
            File::ensureDirectoryExists(dirname($path));
            File::put($path, $content);
            $results['resources'][] = $name;
        }
        
        return $results;
    }

    /**
     * Generate Content Sections Module
     */
    private static function generateContentSectionsModule(array $config): array
    {
        $results = [];
        
        $sections = $config['sections'] ?? ['galleries', 'testimonials', 'faqs', 'services', 'partners'];
        
        foreach ($sections as $section) {
            // Generate migration
            $migrationName = "create_{$section}_table";
            $migrationContent = self::generateBasicMigration($section);
            $filename = date('Y_m_d_His') . '_' . $migrationName . '.php';
            $path = database_path('migrations/' . $filename);
            File::put($path, $migrationContent);
            $results['migrations'][] = $filename;
        }
        
        return $results;
    }

    /**
     * Generate Layout Components Module
     */
    private static function generateLayoutComponentsModule(array $config): array
    {
        $results = [];
        
        // Generate Livewire Components
        $components = ['DynamicMenu', 'SearchBar', 'ToastNotifications'];
        
        foreach ($components as $name) {
            $content = self::generateBasicLivewireComponent($name);
            $path = app_path("Livewire/Public/{$name}.php");
            File::ensureDirectoryExists(dirname($path));
            File::put($path, $content);
            $results['livewire'][] = $name;
        }
        
        return $results;
    }

    /**
     * Generate User Roles Module (sử dụng Filament Shield)
     */
    private static function generateUserRolesModule(array $config): array
    {
        $results = [];
        
        // Chạy Filament Shield commands
        try {
            Artisan::call('shield:install', ['--fresh' => true]);
            $results['shield'] = 'Installed Filament Shield';
            
            Artisan::call('shield:generate', ['--all' => true]);
            $results['permissions'] = 'Generated all permissions';
            
        } catch (Exception $e) {
            $results['error'] = 'Error installing Shield: ' . $e->getMessage();
        }
        
        return $results;
    }

    /**
     * Generate E-commerce Module
     */
    private static function generateEcommerceModule(array $config): array
    {
        $results = [];
        
        // Generate migrations cho e-commerce
        $migrations = [
            'create_product_categories_table',
            'create_products_table',
            'create_carts_table',
            'create_orders_table',
        ];
        
        foreach ($migrations as $migrationName) {
            $content = self::generateBasicMigration(str_replace(['create_', '_table'], '', $migrationName));
            $filename = date('Y_m_d_His') . '_' . $migrationName . '.php';
            $path = database_path('migrations/' . $filename);
            File::put($path, $content);
            $results['migrations'][] = $filename;
        }
        
        return $results;
    }

    /**
     * Generate Settings Expansion Module
     */
    private static function generateSettingsExpansionModule(array $config): array
    {
        $results = [];
        
        // Generate additional settings tables
        $settingsTables = ['theme_settings', 'email_templates', 'notification_settings'];
        
        foreach ($settingsTables as $table) {
            $migrationName = "create_{$table}_table";
            $content = self::generateBasicMigration($table);
            $filename = date('Y_m_d_His') . '_' . $migrationName . '.php';
            $path = database_path('migrations/' . $filename);
            File::put($path, $content);
            $results['migrations'][] = $filename;
        }
        
        return $results;
    }

    /**
     * Generate Web Design Management Module
     */
    private static function generateWebDesignModule(array $config): array
    {
        $results = [];
        
        // Generate web design related migrations
        $migrations = [
            'create_web_designs_table' => 'web_designs',
            'create_page_builders_table' => 'page_builders',
        ];
        
        foreach ($migrations as $name => $tableName) {
            $content = self::generateBasicMigration($tableName);
            $filename = date('Y_m_d_His') . '_' . $name . '.php';
            $path = database_path('migrations/' . $filename);
            File::put($path, $content);
            $results['migrations'][] = $filename;
        }
        
        return $results;
    }

    /**
     * Generate basic migration template
     */
    private static function generateBasicMigration(string $tableName): string
    {
        $className = 'Create' . str_replace(' ', '', ucwords(str_replace('_', ' ', $tableName))) . 'Table';
        
        return "<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('{$tableName}', function (Blueprint \$table) {
            \$table->id();
            \$table->string('name');
            \$table->text('description')->nullable();
            \$table->string('status')->default('active');
            \$table->integer('order')->default(0);
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('{$tableName}');
    }
};
";
    }

    /**
     * Generate basic Livewire component template
     */
    private static function generateBasicLivewireComponent(string $componentName): string
    {
        return "<?php

namespace App\Livewire\Public;

use Livewire\Component;

class {$componentName} extends Component
{
    public function render()
    {
        return view('livewire.public." . strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $componentName)) . "');
    }
}
";
    }

    /**
     * Generate basic Model template
     */
    private static function generateBasicModel(string $modelName): string
    {
        return "<?php

namespace App\Generated\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class {$modelName} extends Model
{
    use HasFactory;

    protected \$fillable = [
        'name',
        'description',
        'status',
        'order',
    ];

    protected \$casts = [
        'status' => 'string',
        'order' => 'integer',
    ];
}
";
    }

    /**
     * Generate basic Filament Resource template
     */
    private static function generateBasicResource(string $resourceName): string
    {
        $modelName = str_replace('Resource', '', $resourceName);
        
        return "<?php

namespace App\Generated\Filament\Resources;

use App\Generated\Models\\{$modelName};
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class {$resourceName} extends Resource
{
    protected static ?string \$model = {$modelName}::class;

    protected static ?string \$navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form \$form): Form
    {
        return \$form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535),
                Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->default('active'),
                Forms\Components\TextInput::make('order')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table \$table): Table
    {
        return \$table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \\Filament\\Resources\\Pages\\ListRecords::route('/'),
            'create' => \\Filament\\Resources\\Pages\\CreateRecord::route('/create'),
            'edit' => \\Filament\\Resources\\Pages\\EditRecord::route('/{record}/edit'),
        ];
    }
}
";
    }

    /**
     * Generate basic Observer template
     */
    private static function generateBasicObserver(string $observerName): string
    {
        $modelName = str_replace('Observer', '', $observerName);
        
        return "<?php

namespace App\Observers;

use App\Generated\Models\\{$modelName};
use App\Actions\Cache\ClearViewCache;

class {$observerName}
{
    public function created({$modelName} \${$modelName}): void
    {
        ClearViewCache::forModel(\${$modelName});
    }

    public function updated({$modelName} \${$modelName}): void
    {
        ClearViewCache::forModel(\${$modelName});
    }

    public function deleted({$modelName} \${$modelName}): void
    {
        ClearViewCache::forModel(\${$modelName});
    }
}
";
    }

    /**
     * Generate basic Seeder template
     */
    private static function generateBasicSeeder(string $seederName): string
    {
        return "<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class {$seederName} extends Seeder
{
    public function run(): void
    {
        // Add seeder logic here
    }
}
";
    }
}
