<?php

namespace App\Filament\Admin\Pages;

use App\Models\Setting;
use App\Actions\Image\ConvertImageToWebp;
use App\Traits\HandlesFileUploadFields;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Http\UploadedFile;

class ManageSettings extends Page
{
    use HandlesFileUploadFields;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Cài đặt Website';
    protected static ?string $title = 'Quản lý Cài đặt Website';
    protected static ?int $navigationSort = 100;

    protected static string $view = 'filament.admin.pages.manage-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $setting = Setting::first();

        if ($setting) {
            $this->data = $setting->toArray();

            // Chuyển đổi các file upload fields từ string thành array để Filament có thể hiển thị
            $fileFields = ['logo_link', 'favicon_link', 'placeholder_image'];
            $this->data = $this->convertFileFieldsToArray($this->data, $fileFields);
        } else {
            $this->data = [];
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Settings')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Thông tin cơ bản')
                            ->schema([
                                Forms\Components\Section::make('Thông tin website')
                                    ->schema([
                                        Forms\Components\TextInput::make('site_name')
                                            ->label('Tên website')
                                            ->required()
                                            ->maxLength(255),

                                        Forms\Components\TextInput::make('slogan')
                                            ->label('Slogan')
                                            ->maxLength(255),

                                        Forms\Components\Textarea::make('footer_description')
                                            ->label('Mô tả footer')
                                            ->rows(3),
                                    ])->columns(2),

                                Forms\Components\Section::make('SEO')
                                    ->schema([
                                        Forms\Components\TextInput::make('seo_title')
                                            ->label('Tiêu đề SEO')
                                            ->maxLength(60)
                                            ->helperText('Tối đa 60 ký tự'),

                                        Forms\Components\Textarea::make('seo_description')
                                            ->label('Mô tả SEO')
                                            ->maxLength(160)
                                            ->rows(3)
                                            ->helperText('Tối đa 160 ký tự'),
                                    ])->columns(1),
                            ]),

                        Forms\Components\Tabs\Tab::make('Liên hệ')
                            ->schema([
                                Forms\Components\Section::make('Thông tin liên hệ')
                                    ->schema([
                                        Forms\Components\TextInput::make('email')
                                            ->label('Email')
                                            ->email()
                                            ->maxLength(255),

                                        Forms\Components\TextInput::make('hotline')
                                            ->label('Hotline')
                                            ->maxLength(255),

                                        Forms\Components\Textarea::make('address')
                                            ->label('Địa chỉ')
                                            ->rows(2),

                                        Forms\Components\TextInput::make('working_hours')
                                            ->label('Giờ làm việc')
                                            ->maxLength(255),
                                    ])->columns(2),
                            ]),

                        Forms\Components\Tabs\Tab::make('Mạng xã hội')
                            ->schema([
                                Forms\Components\Section::make('Liên kết mạng xã hội')
                                    ->schema([
                                        Forms\Components\TextInput::make('facebook_link')
                                            ->label('Facebook')
                                            ->url()
                                            ->maxLength(255),

                                        Forms\Components\TextInput::make('zalo_link')
                                            ->label('Zalo')
                                            ->url()
                                            ->maxLength(255),

                                        Forms\Components\TextInput::make('youtube_link')
                                            ->label('YouTube')
                                            ->url()
                                            ->maxLength(255),

                                        Forms\Components\TextInput::make('tiktok_link')
                                            ->label('TikTok')
                                            ->url()
                                            ->maxLength(255),

                                        Forms\Components\TextInput::make('messenger_link')
                                            ->label('Messenger')
                                            ->url()
                                            ->maxLength(255),
                                    ])->columns(2),
                            ]),

                        Forms\Components\Tabs\Tab::make('Hệ thống')
                            ->schema([
                                Forms\Components\Section::make('Hình ảnh')
                                    ->schema([
                                        Forms\Components\FileUpload::make('logo_link')
                                            ->label('Logo Website')
                                            ->image()
                                            ->directory('logos')
                                            ->visibility('public')
                                            ->imageEditor()
                                            ->imageEditorAspectRatios([
                                                '16:9',
                                                '4:3',
                                                '1:1',
                                            ])
                                            ->maxSize(5120) // 5MB
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
                                            ->helperText('Tải lên logo website. Ảnh sẽ được tự động chuyển sang định dạng WebP.')
                                            ->saveUploadedFileUsing(function (UploadedFile $file): string {
                                                return ConvertImageToWebp::run($file, 'logos', 'logo');
                                            }),

                                        Forms\Components\FileUpload::make('favicon_link')
                                            ->label('Favicon')
                                            ->image()
                                            ->directory('favicons')
                                            ->visibility('public')
                                            ->imageEditor()
                                            ->imageEditorAspectRatios(['1:1'])
                                            ->maxSize(1024) // 1MB
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/x-icon'])
                                            ->helperText('Tải lên favicon (16x16 hoặc 32x32 px). Ảnh sẽ được tự động chuyển sang định dạng WebP.')
                                            ->saveUploadedFileUsing(function (UploadedFile $file): string {
                                                return ConvertImageToWebp::convertWithOptions($file, [
                                                    'directory' => 'favicons',
                                                    'name' => 'favicon',
                                                    'max_width' => 32,
                                                    'max_height' => 32,
                                                    'quality' => 90
                                                ]);
                                            }),

                                        Forms\Components\FileUpload::make('placeholder_image')
                                            ->label('Ảnh Placeholder')
                                            ->image()
                                            ->directory('placeholders')
                                            ->visibility('public')
                                            ->imageEditor()
                                            ->imageEditorAspectRatios([
                                                '16:9',
                                                '4:3',
                                                '1:1',
                                            ])
                                            ->maxSize(5120) // 5MB
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
                                            ->helperText('Ảnh mặc định khi không có ảnh khác. Ảnh sẽ được tự động chuyển sang định dạng WebP.')
                                            ->saveUploadedFileUsing(function (UploadedFile $file): string {
                                                return ConvertImageToWebp::run($file, 'placeholders', 'placeholder');
                                            }),
                                    ])->columns(1),

                                Forms\Components\Section::make('Cài đặt hệ thống')
                                    ->schema([
                                        Forms\Components\Select::make('status')
                                            ->label('Trạng thái')
                                            ->options([
                                                'active' => 'Hoạt động',
                                                'inactive' => 'Không hoạt động',
                                            ])
                                            ->default('active')
                                            ->required(),

                                        Forms\Components\TextInput::make('order')
                                            ->label('Thứ tự')
                                            ->numeric()
                                            ->default(1),
                                    ])->columns(2),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Lưu cài đặt')
                ->action('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Chuyển đổi các file upload fields từ array về string để lưu vào database
        $fileFields = ['logo_link', 'favicon_link', 'placeholder_image'];
        $data = $this->convertFileFieldsToString($data, $fileFields);

        $setting = Setting::first();

        if ($setting) {
            $setting->update($data);
        } else {
            Setting::create($data);
        }

        Notification::make()
            ->title('Đã lưu cài đặt thành công!')
            ->success()
            ->send();
    }
}
