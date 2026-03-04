<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class WebCacheController extends Controller
{
    public function clear()
    {
        try {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            return "Cache cleared successfully!";
        } catch (\Exception $e) {
            return "Error clearing cache: " . $e->getMessage();
        }
    }
}
