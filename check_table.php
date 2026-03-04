<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

if (Schema::hasTable('password_reset_tokens')) {
    echo "password_reset_tokens EXISTS";
} elseif (Schema::hasTable('password_resets')) {
    echo "password_resets EXISTS";
} else {
    echo "NONE EXISTS";
}
