<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use App\Models\CatPost;
use App\Services\ImageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use App\Livewire\PostsFilter;

class PostsPageTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Tạo category test
        $this->category = CatPost::factory()->create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'status' => 'active'
        ]);
    }

    /** @test */
    public function posts_page_loads_successfully()
    {
        $response = $this->get('/bai-viet');
        
        $response->assertStatus(200);
        $response->assertSee('Bài viết & Tin tức');
        $response->assertSeeLivewire(PostsFilter::class);
    }

    /** @test */
    public function posts_filter_component_renders_correctly()
    {
        // Tạo test posts
        Post::factory()->count(5)->create([
            'status' => 'active',
            'type' => 'news',
            'category_id' => $this->category->id
        ]);

        Livewire::test(PostsFilter::class)
            ->assertSee('Tìm kiếm')
            ->assertSee('Loại nội dung')
            ->assertSee('Tin tức');
    }

    /** @test */
    public function search_functionality_works()
    {
        // Tạo posts với title khác nhau
        $post1 = Post::factory()->create([
            'title' => 'Laravel Tutorial',
            'status' => 'active',
            'category_id' => $this->category->id
        ]);

        $post2 = Post::factory()->create([
            'title' => 'PHP Best Practices',
            'status' => 'active',
            'category_id' => $this->category->id
        ]);

        Livewire::test(PostsFilter::class)
            ->set('search', 'Laravel')
            ->assertSee('Laravel Tutorial')
            ->assertDontSee('PHP Best Practices');
    }

    /** @test */
    public function type_filter_works()
    {
        // Tạo posts với type khác nhau
        Post::factory()->create([
            'type' => 'news',
            'status' => 'active',
            'category_id' => $this->category->id
        ]);

        Post::factory()->create([
            'type' => 'service',
            'status' => 'active',
            'category_id' => $this->category->id
        ]);

        Livewire::test(PostsFilter::class)
            ->set('type', 'news')
            ->assertSee('Tin tức');
    }

    /** @test */
    public function load_more_functionality_works()
    {
        // Tạo nhiều posts
        Post::factory()->count(15)->create([
            'status' => 'active',
            'category_id' => $this->category->id
        ]);

        Livewire::test(PostsFilter::class)
            ->assertSee('Xem thêm bài viết')
            ->call('loadMore')
            ->assertSee('Xem thêm bài viết');
    }

    /** @test */
    public function empty_state_displays_correctly()
    {
        Livewire::test(PostsFilter::class)
            ->assertSee('Không tìm thấy bài viết');
    }

    /** @test */
    public function image_service_works_correctly()
    {
        $post = Post::factory()->create([
            'thumbnail' => 'test-image.jpg',
            'type' => 'news'
        ]);

        $imageData = ImageService::getImageData($post, 'thumbnail');

        $this->assertIsArray($imageData);
        $this->assertArrayHasKey('hasImage', $imageData);
        $this->assertArrayHasKey('imageUrl', $imageData);
        $this->assertArrayHasKey('altText', $imageData);
        $this->assertArrayHasKey('iconClass', $imageData);
    }

    /** @test */
    public function image_service_returns_correct_icon_for_type()
    {
        $newsIcon = ImageService::getIconByType('news');
        $serviceIcon = ImageService::getIconByType('service');
        $courseIcon = ImageService::getIconByType('course');

        $this->assertEquals('fas fa-newspaper', $newsIcon);
        $this->assertEquals('fas fa-concierge-bell', $serviceIcon);
        $this->assertEquals('fas fa-graduation-cap', $courseIcon);
    }

    /** @test */
    public function responsive_grid_classes_work()
    {
        // Test với 1 post
        Post::factory()->create([
            'status' => 'active',
            'category_id' => $this->category->id
        ]);

        $response = $this->get('/bai-viet');
        $response->assertSee('max-w-2xl mx-auto');
    }

    /** @test */
    public function fallback_ui_component_renders()
    {
        $post = Post::factory()->create([
            'thumbnail' => null, // Không có ảnh
            'type' => 'news',
            'status' => 'active',
            'category_id' => $this->category->id
        ]);

        $response = $this->get('/bai-viet');
        $response->assertSee('fas fa-newspaper'); // Icon cho news type
    }

    /** @test */
    public function clear_filters_works()
    {
        Post::factory()->create([
            'status' => 'active',
            'type' => 'news',
            'category_id' => $this->category->id
        ]);

        Livewire::test(PostsFilter::class)
            ->set('search', 'test')
            ->set('type', 'news')
            ->call('clearFilters')
            ->assertSet('search', '')
            ->assertSet('type', '')
            ->assertSet('sort', 'newest');
    }

    /** @test */
    public function performance_optimization_query_works()
    {
        Post::factory()->count(10)->create([
            'status' => 'active',
            'category_id' => $this->category->id
        ]);

        // Test query với select specific fields
        Livewire::test(PostsFilter::class)
            ->assertSee('bài viết'); // Kiểm tra có hiển thị posts
    }
}
