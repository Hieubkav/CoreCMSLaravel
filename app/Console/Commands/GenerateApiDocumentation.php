<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

class GenerateApiDocumentation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:generate-docs {--format=markdown : Output format (markdown, json, html)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate API documentation from routes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Generating API documentation...');

        $format = $this->option('format');
        $routes = $this->getApiRoutes();

        switch ($format) {
            case 'json':
                $this->generateJsonDocs($routes);
                break;
            case 'html':
                $this->generateHtmlDocs($routes);
                break;
            case 'markdown':
            default:
                $this->generateMarkdownDocs($routes);
                break;
        }

        $this->info('✅ API documentation generated successfully!');
    }

    /**
     * Get API routes
     */
    private function getApiRoutes(): array
    {
        $apiRoutes = [];

        foreach (Route::getRoutes() as $route) {
            if (str_starts_with($route->uri(), 'api/')) {
                $apiRoutes[] = [
                    'uri' => $route->uri(),
                    'methods' => $route->methods(),
                    'name' => $route->getName(),
                    'action' => $route->getActionName(),
                    'middleware' => $route->middleware(),
                    'parameters' => $route->parameterNames(),
                ];
            }
        }

        return $apiRoutes;
    }

    /**
     * Generate Markdown documentation
     */
    private function generateMarkdownDocs(array $routes): void
    {
        $markdown = $this->buildMarkdownContent($routes);

        $path = base_path('docs/api-documentation.md');
        File::ensureDirectoryExists(dirname($path));
        File::put($path, $markdown);

        $this->info("📝 Markdown documentation saved to: {$path}");
    }

    /**
     * Generate JSON documentation
     */
    private function generateJsonDocs(array $routes): void
    {
        $json = json_encode([
            'info' => [
                'title' => 'Core Laravel API',
                'version' => '1.0.0',
                'description' => 'API documentation for Core Laravel application',
                'generated_at' => now()->toISOString(),
            ],
            'base_url' => config('app.url') . '/api',
            'routes' => $routes,
        ], JSON_PRETTY_PRINT);

        $path = base_path('docs/api-documentation.json');
        File::ensureDirectoryExists(dirname($path));
        File::put($path, $json);

        $this->info("📄 JSON documentation saved to: {$path}");
    }

    /**
     * Generate HTML documentation
     */
    private function generateHtmlDocs(array $routes): void
    {
        $html = $this->buildHtmlContent($routes);

        $path = base_path('docs/api-documentation.html');
        File::ensureDirectoryExists(dirname($path));
        File::put($path, $html);

        $this->info("🌐 HTML documentation saved to: {$path}");
    }

    /**
     * Build Markdown content
     */
    private function buildMarkdownContent(array $routes): string
    {
        $markdown = "# Core Laravel API Documentation\n\n";
        $markdown .= "Generated on: " . now()->format('Y-m-d H:i:s') . "\n\n";
        $markdown .= "Base URL: `" . config('app.url') . "/api`\n\n";

        $markdown .= "## Authentication\n\n";
        $markdown .= "Some endpoints require authentication using Laravel Sanctum tokens.\n";
        $markdown .= "Include the token in the Authorization header: `Authorization: Bearer {token}`\n\n";

        $markdown .= "## Rate Limiting\n\n";
        $markdown .= "API endpoints are rate limited:\n";
        $markdown .= "- **Authenticated users**: 60 requests per minute\n";
        $markdown .= "- **Guest users**: 60 requests per minute\n\n";

        $markdown .= "## Response Format\n\n";
        $markdown .= "All API responses follow this format:\n\n";
        $markdown .= "```json\n";
        $markdown .= "{\n";
        $markdown .= "  \"success\": true,\n";
        $markdown .= "  \"data\": {},\n";
        $markdown .= "  \"message\": \"Optional message\",\n";
        $markdown .= "  \"meta\": {},\n";
        $markdown .= "  \"timestamp\": \"2024-01-01T00:00:00.000000Z\"\n";
        $markdown .= "}\n";
        $markdown .= "```\n\n";

        $markdown .= "## Endpoints\n\n";

        $groupedRoutes = $this->groupRoutesByPrefix($routes);

        foreach ($groupedRoutes as $prefix => $prefixRoutes) {
            $markdown .= "### " . ucfirst(str_replace('_', ' ', $prefix)) . "\n\n";

            foreach ($prefixRoutes as $route) {
                $methods = implode(', ', array_filter($route['methods'], fn($m) => $m !== 'HEAD'));
                $markdown .= "#### `{$methods}` {$route['uri']}\n\n";

                if ($route['name']) {
                    $markdown .= "**Route Name:** `{$route['name']}`\n\n";
                }

                $markdown .= $this->getRouteDescription($route) . "\n\n";

                if (!empty($route['parameters'])) {
                    $markdown .= "**Parameters:**\n";
                    foreach ($route['parameters'] as $param) {
                        $markdown .= "- `{$param}` (required)\n";
                    }
                    $markdown .= "\n";
                }

                if (in_array('auth:sanctum', $route['middleware'])) {
                    $markdown .= "**Authentication:** Required\n\n";
                }

                $markdown .= $this->getExampleRequest($route) . "\n\n";
                $markdown .= "---\n\n";
            }
        }

        return $markdown;
    }

    /**
     * Build HTML content
     */
    private function buildHtmlContent(array $routes): string
    {
        $groupedRoutes = $this->groupRoutesByPrefix($routes);

        $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Core Laravel API Documentation</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; line-height: 1.6; margin: 0; padding: 20px; background: #f8f9fa; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #dc2626; border-bottom: 3px solid #dc2626; padding-bottom: 10px; }
        h2 { color: #1f2937; margin-top: 30px; }
        h3 { color: #374151; }
        h4 { color: #4b5563; background: #f3f4f6; padding: 10px; border-left: 4px solid #dc2626; }
        code { background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-family: "Fira Code", monospace; }
        pre { background: #1e293b; color: #e2e8f0; padding: 20px; border-radius: 6px; overflow-x: auto; }
        .method { display: inline-block; padding: 4px 8px; border-radius: 4px; font-weight: bold; margin-right: 10px; }
        .get { background: #10b981; color: white; }
        .post { background: #3b82f6; color: white; }
        .put { background: #f59e0b; color: white; }
        .delete { background: #ef4444; color: white; }
        .auth-required { background: #fef3c7; border: 1px solid #f59e0b; padding: 8px; border-radius: 4px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Core Laravel API Documentation</h1>
        <p><strong>Generated on:</strong> ' . now()->format('Y-m-d H:i:s') . '</p>
        <p><strong>Base URL:</strong> <code>' . config('app.url') . '/api</code></p>

        <h2>📋 Table of Contents</h2>
        <ul>';

        foreach ($groupedRoutes as $prefix => $prefixRoutes) {
            $html .= '<li><a href="#' . $prefix . '">' . ucfirst(str_replace('_', ' ', $prefix)) . '</a></li>';
        }

        $html .= '</ul>

        <h2>🔐 Authentication</h2>
        <p>Some endpoints require authentication using Laravel Sanctum tokens.</p>
        <p>Include the token in the Authorization header: <code>Authorization: Bearer {token}</code></p>

        <h2>⚡ Rate Limiting</h2>
        <ul>
            <li><strong>Authenticated users:</strong> 60 requests per minute</li>
            <li><strong>Guest users:</strong> 60 requests per minute</li>
        </ul>';

        foreach ($groupedRoutes as $prefix => $prefixRoutes) {
            $html .= '<h2 id="' . $prefix . '">' . ucfirst(str_replace('_', ' ', $prefix)) . '</h2>';

            foreach ($prefixRoutes as $route) {
                $methods = array_filter($route['methods'], fn($m) => $m !== 'HEAD');
                $methodsHtml = '';
                foreach ($methods as $method) {
                    $methodsHtml .= '<span class="method ' . strtolower($method) . '">' . $method . '</span>';
                }

                $html .= '<h4>' . $methodsHtml . $route['uri'] . '</h4>';

                if (in_array('auth:sanctum', $route['middleware'])) {
                    $html .= '<div class="auth-required">🔒 <strong>Authentication Required</strong></div>';
                }

                $html .= '<p>' . $this->getRouteDescription($route) . '</p>';

                if (!empty($route['parameters'])) {
                    $html .= '<h5>Parameters:</h5><ul>';
                    foreach ($route['parameters'] as $param) {
                        $html .= '<li><code>' . $param . '</code> (required)</li>';
                    }
                    $html .= '</ul>';
                }

                $html .= '<h5>Example Request:</h5>';
                $html .= '<pre><code>' . htmlspecialchars($this->getExampleRequest($route)) . '</code></pre>';
            }
        }

        $html .= '</div></body></html>';

        return $html;
    }

    /**
     * Group routes by prefix
     */
    private function groupRoutesByPrefix(array $routes): array
    {
        $grouped = [];

        foreach ($routes as $route) {
            $parts = explode('/', $route['uri']);
            $prefix = $parts[1] ?? 'general'; // Skip 'api' part

            if (!isset($grouped[$prefix])) {
                $grouped[$prefix] = [];
            }

            $grouped[$prefix][] = $route;
        }

        return $grouped;
    }

    /**
     * Get route description
     */
    private function getRouteDescription(array $route): string
    {
        $descriptions = [
            'api.products.index' => 'Get a paginated list of products with filtering options',
            'api.products.show' => 'Get detailed information about a specific product',
            'api.products.categories' => 'Get all product categories',
            'api.products.search' => 'Search products by name or description',
            'api.posts.index' => 'Get a paginated list of blog posts',
            'api.posts.show' => 'Get detailed information about a specific blog post',
            'api.posts.categories' => 'Get all post categories',
            'api.cart.index' => 'Get current cart contents',
            'api.cart.store' => 'Add an item to the cart',
            'api.cart.update' => 'Update cart item quantity',
            'api.cart.destroy' => 'Remove an item from the cart',
            'api.cart.clear' => 'Clear all items from the cart',
            'api.search.suggestions' => 'Get search suggestions for products and posts',
            'webhooks.payment' => 'Handle payment webhook from payment providers',
            'webhooks.inventory' => 'Handle inventory updates from external systems',
            'webhooks.shipping' => 'Handle shipping status updates',
        ];

        return $descriptions[$route['name']] ?? 'API endpoint for ' . str_replace(['api.', '.'], ['', ' '], $route['name'] ?? 'this route');
    }

    /**
     * Get example request
     */
    private function getExampleRequest(array $route): string
    {
        $method = array_filter($route['methods'], fn($m) => $m !== 'HEAD')[0] ?? 'GET';
        $url = config('app.url') . '/' . $route['uri'];

        $example = "curl -X {$method} \\\n";
        $example .= "  '{$url}' \\\n";
        $example .= "  -H 'Accept: application/json' \\\n";
        $example .= "  -H 'Content-Type: application/json'";

        if (in_array('auth:sanctum', $route['middleware'])) {
            $example .= " \\\n  -H 'Authorization: Bearer {your-token}'";
        }

        if (in_array($method, ['POST', 'PUT', 'PATCH'])) {
            $example .= " \\\n  -d '{\"example\": \"data\"}'";
        }

        return $example;
    }
}
