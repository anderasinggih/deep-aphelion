<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$user = App\Models\User::first();
if (!$user) {
    putenv('DB_CONNECTION=sqlite');
    putenv('DB_DATABASE=database/database.sqlite');
    Illuminate\Support\Facades\Artisan::call('migrate:fresh --seed');
    $user = App\Models\User::first();
}

$response = $kernel->handle(
    $request = Illuminate\Http\Request::create('/profile', 'GET')
);
$request->setUserResolver(function() use ($user) { return $user; });
Auth::login($user);

$html = clone $kernel->handle(Illuminate\Http\Request::create('/profile', 'GET'))->getContent();
if (strpos($html, 'x-data="{
        show: true,') !== false || strpos($html, 'show: 1,') !== false || strpos($html, 'x-data="{
        show: 1,') !== false) {
    echo "MODAL IS TRUE IN HTML\n";
} else {
    echo "MODAL IS FALSE IN HTML\n";
    // echo snippet to see the generated x-data for the modal
    preg_match_all('/x-data=\"\{\s*show:(.*?)\,/s', $html, $matches);
    print_r($matches[1]);
}
