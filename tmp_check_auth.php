<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Hash;
use App\Models\User;

$emails = ['dosen@example.com', 'mahasiswa@example.com'];
$password = 'password123';

foreach ($emails as $email) {
    $u = User::where('email', $email)->first();
    if (!$u) {
        echo "user not found: $email\n";
        continue;
    }
    $ok = Hash::check($password, $u->password) ? 'OK' : 'MISMATCH';
    echo $u->email . ' | role:' . $u->role . ' | password_check:' . $ok . "\n";
}
