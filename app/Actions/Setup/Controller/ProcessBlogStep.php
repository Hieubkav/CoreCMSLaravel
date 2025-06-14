<?php

namespace App\Actions\Setup\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Actions\Setup\CreatePostModule;
use App\Actions\Setup\Controller\SetupUtilities;

class ProcessBlogStep
{
    /**
     * Xử lý bước setup blog
     */
    public static function handle(Request $request): array
    {
        try {
            // Kiểm tra nếu user chọn bỏ qua
            if ($request->has('skip_blog') && $request->boolean('skip_blog')) {
                return [
                    'success' => true,
                    'message' => 'Đã bỏ qua cấu hình blog.',
                    'next_step' => self::getNextStep()
                ];
            }

            // Validate input
            $validator = Validator::make($request->all(), [
                'enable_blog' => 'nullable|boolean',
                'create_sample_data' => 'nullable|boolean',
                'blog_title' => 'required_if:enable_blog,1|string|max:255',
                'blog_description' => 'nullable|string|max:500',
                'default_post_type' => 'required_if:enable_blog,1|string|in:tin_tuc,dich_vu,trang_don',
                'enable_categories' => 'nullable|boolean',
                'enable_featured_posts' => 'nullable|boolean',
                'posts_per_page' => 'nullable|integer|min:1|max:50',
            ]);

            if ($validator->fails()) {
                return [
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ: ' . $validator->errors()->first(),
                    'errors' => $validator->errors()->toArray()
                ];
            }

            $validated = $validator->validated();

            // Convert checkbox values to boolean
            $validated['enable_blog'] = $request->has('enable_blog') && $request->enable_blog == '1';
            $validated['create_sample_data'] = $request->has('create_sample_data') && $request->create_sample_data == '1';
            $validated['enable_categories'] = $request->has('enable_categories') && $request->enable_categories == '1';
            $validated['enable_featured_posts'] = $request->has('enable_featured_posts') && $request->enable_featured_posts == '1';

            if (!$validated['enable_blog']) {
                return [
                    'success' => true,
                    'message' => 'Blog không được kích hoạt.',
                    'next_step' => self::getNextStep()
                ];
            }

            // Tạo blog module
            $result = CreatePostModule::handle($validated);

            if ($result['success']) {
                return [
                    'success' => true,
                    'message' => 'Cấu hình blog thành công!',
                    'next_step' => self::getNextStep(),
                    'data' => $result['data'] ?? []
                ];
            } else {
                return [
                    'success' => false,
                    'message' => $result['message'] ?? 'Có lỗi xảy ra khi cấu hình blog.',
                    'errors' => $result['errors'] ?? []
                ];
            }

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Lấy bước tiếp theo sau blog
     */
    private static function getNextStep(): string
    {
        // Sử dụng SetupUtilities để lấy next step theo đúng flow
        return SetupUtilities::getNextStep('blog') ?? 'complete';
    }
}
