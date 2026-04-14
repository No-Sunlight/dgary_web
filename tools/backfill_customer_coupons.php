<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make('Illuminate\\Contracts\\Console\\Kernel');
$kernel->bootstrap();

$sql = "INSERT INTO customer_coupons (customer_id, coupon_id, status, discount, created_at, updated_at)
SELECT c.id, cp.id, 1, cp.discount, NOW(), NOW()
FROM customers c
JOIN coupons cp ON cp.status = 1
LEFT JOIN customer_coupons cc ON cc.customer_id = c.id AND cc.coupon_id = cp.id
WHERE cc.id IS NULL";

$affected = $app->make('db')->affectingStatement($sql);

echo "Inserted rows: {$affected}" . PHP_EOL;
