<?php

namespace App\Actions\Setup\Controller;

use App\Actions\Setup\ImportSampleData;
use Illuminate\Http\Request;

class ProcessSampleDataStep
{
    /**
     * Xử lý bước import dữ liệu mẫu
     */
    public static function handle(Request $request): array
    {
        $request->validate([
            'import_sample' => 'boolean'
        ]);

        return ImportSampleData::run($request->import_sample ?? false);
    }
}
