<?php
// test-notification.php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->handleRequest(\Illuminate\Http\Request::capture());

use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Str;

$admin = User::first();
echo "Admin: " . $admin->name . "\n";

$notification = Notification::create([
    'uuid' => (string) Str::uuid(),
    'company_id' => $admin->company_id,
    'user_id' => $admin->id,
    'type' => 'test',
    'title' => 'Test Notification',
    'message' => 'Ceci est un test',
    'is_read' => false,
    'sent_at' => now(),
]);

echo "Notification créée: " . $notification->id . "\n";
