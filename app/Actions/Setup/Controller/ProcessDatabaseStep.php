<?php

namespace App\Actions\Setup\Controller;

use App\Actions\Setup\SetupDatabase;
use Illuminate\Http\Request;

class ProcessDatabaseStep
{
    /**
     * Xử lý bước cấu hình database
     */
    public static function handle(Request $request): array
    {
        $request->validate([
            'test_connection' => 'boolean'
        ]);

        $result = $request->test_connection
            ? SetupDatabase::testConnection()
            : SetupDatabase::runMigrations();

        return $result;
    }
}
