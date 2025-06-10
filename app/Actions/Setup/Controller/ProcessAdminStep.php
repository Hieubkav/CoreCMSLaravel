<?php

namespace App\Actions\Setup\Controller;

use App\Actions\Setup\CreateAdminUser;
use Illuminate\Http\Request;

class ProcessAdminStep
{
    /**
     * Xử lý bước tạo admin
     */
    public static function handle(Request $request): array
    {
        $data = $request->only(['name', 'email', 'password', 'password_confirmation']);
        return CreateAdminUser::createWithValidation($data);
    }
}
