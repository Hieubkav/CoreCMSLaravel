<?php

namespace App\Actions\Setup\Controller;

use Illuminate\Http\Request;
use App\Actions\Setup\CreatePostModule;

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
                    'redirect' => route('setup.complete')
                ];
            }

            // Validate input
            $validated = $request->validate([
                'enable_blog' => 'required|boolean',
                'blog_title' => 'required_if:enable_blog,true|string|max:255',
                'blog_description' => 'nullable|string|max:500',
                'default_post_type' => 'required_if:enable_blog,true|string|in:tin_tuc,dich_vu,trang_don',
                'enable_categories' => 'boolean',
                'enable_featured_posts' => 'boolean',
                'posts_per_page' => 'integer|min:1|max:50',
            ]);

            if (!$validated['enable_blog']) {
                return [
                    'success' => true,
                    'message' => 'Blog không được kích hoạt.',
                    'redirect' => route('setup.complete')
                ];
            }

            // Tạo blog module
            $result = CreatePostModule::handle($validated);

            if ($result['success']) {
                return [
                    'success' => true,
                    'message' => 'Cấu hình blog thành công!',
                    'redirect' => route('setup.complete'),
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
}
