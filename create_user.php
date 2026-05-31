<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

$user = \App\Models\User::create([
    'name' => 'gm01',
    'email' => 'gm01@sealonline.test',
    'password' => 'botakkontol',
]);

echo "User created successfully! ID: " . $user->id . PHP_EOL;
