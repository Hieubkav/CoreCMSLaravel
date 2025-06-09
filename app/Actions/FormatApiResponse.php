<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Http\JsonResponse;

class FormatApiResponse
{
    use AsAction;

    /**
     * Format successful API response
     */
    public function handle(
        $data = null,
        ?string $message = null,
        int $statusCode = 200,
        array $meta = []
    ): JsonResponse {
        $response = [
            'success' => true,
            'timestamp' => now()->toISOString(),
        ];

        if ($message) {
            $response['message'] = $message;
        }

        if ($data !== null) {
            $response['data'] = $data;
        }

        if (!empty($meta)) {
            $response['meta'] = $meta;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Format error API response
     */
    public static function error(
        string $message,
        int $statusCode = 400,
        array $errors = [],
        $debug = null
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $message,
            'timestamp' => now()->toISOString(),
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        if ($debug && config('app.debug')) {
            $response['debug'] = $debug;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Format validation error response
     */
    public static function validationError(
        array $errors,
        string $message = 'Validation failed'
    ): JsonResponse {
        return static::error($message, 422, $errors);
    }

    /**
     * Format not found response
     */
    public static function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return static::error($message, 404);
    }

    /**
     * Format unauthorized response
     */
    public static function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return static::error($message, 401);
    }

    /**
     * Format forbidden response
     */
    public static function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return static::error($message, 403);
    }

    /**
     * Format server error response
     */
    public static function serverError(
        string $message = 'Internal server error',
        $debug = null
    ): JsonResponse {
        return static::error($message, 500, [], $debug);
    }

    /**
     * Format paginated response
     */
    public static function paginated(
        $paginatedData,
        ?string $message = null,
        array $additionalMeta = []
    ): JsonResponse {
        $meta = array_merge([
            'pagination' => [
                'current_page' => $paginatedData->currentPage(),
                'last_page' => $paginatedData->lastPage(),
                'per_page' => $paginatedData->perPage(),
                'total' => $paginatedData->total(),
                'from' => $paginatedData->firstItem(),
                'to' => $paginatedData->lastItem(),
                'has_more_pages' => $paginatedData->hasMorePages(),
            ],
            'links' => [
                'first' => $paginatedData->url(1),
                'last' => $paginatedData->url($paginatedData->lastPage()),
                'prev' => $paginatedData->previousPageUrl(),
                'next' => $paginatedData->nextPageUrl(),
            ],
        ], $additionalMeta);

        return static::run($paginatedData->items(), $message, 200, $meta);
    }

    /**
     * Format collection response
     */
    public static function collection(
        $collection,
        ?string $message = null,
        array $meta = []
    ): JsonResponse {
        $data = [
            'items' => $collection,
            'count' => is_countable($collection) ? count($collection) : 0,
        ];

        return static::run($data, $message, 200, $meta);
    }

    /**
     * Format created response
     */
    public static function created(
        $data = null,
        string $message = 'Resource created successfully'
    ): JsonResponse {
        return static::run($data, $message, 201);
    }

    /**
     * Format updated response
     */
    public static function updated(
        $data = null,
        string $message = 'Resource updated successfully'
    ): JsonResponse {
        return static::run($data, $message, 200);
    }

    /**
     * Format deleted response
     */
    public static function deleted(string $message = 'Resource deleted successfully'): JsonResponse
    {
        return static::run(null, $message, 200);
    }

    /**
     * Format no content response
     */
    public static function noContent(): JsonResponse
    {
        return response()->json(null, 204);
    }
}
