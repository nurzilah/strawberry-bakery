<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

\Midtrans\Config::$serverKey = config('midtrans.server_key');
\Midtrans\Config::$isProduction = config('midtrans.is_production');

$orders = App\Models\Order::where('payment_status', 'pending')->get();
foreach($orders as $order) {
    echo "Order ID: {$order->id}, Midtrans ID: {$order->midtrans_order_id}\n";
    if ($order->midtrans_order_id) {
        try {
            $status = \Midtrans\Transaction::status($order->midtrans_order_id);
            echo "Status: " . $status->transaction_status . "\n";
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }
}
