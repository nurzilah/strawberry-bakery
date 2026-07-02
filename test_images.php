<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach(\App\Models\Product::take(5)->get() as $p) {
    echo "ID: " . $p->id . " Image: " . $p->image . "\n";
}
