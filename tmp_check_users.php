<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$count = \App\Models\User::count();
echo "users:$count\n";
$users = \App\Models\User::all();
foreach ($users as $u) {
    echo $u->id . ' | ' . $u->email . ' | ' . $u->role . "\n";
}
