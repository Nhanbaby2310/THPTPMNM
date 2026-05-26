<?php
// Cấu hình VNPay Sandbox
// Để sử dụng VNPay thật, thay đổi các giá trị này bằng thông tin từ VNPay cung cấp

define('VNPAY_TMN_CODE', 'CGXZLS0Z');
define('VNPAY_HASH_SECRET', 'XNBCJFAKAZQSGTARRLGCHVZWCIOIGSHN');
define('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');
define('VNPAY_RETURN_URL', ''); // Sẽ được set động trong controller
define('VNPAY_API_URL', 'https://sandbox.vnpayment.vn/merchant_webapi/api/transaction');
define('VNPAY_VERSION', '2.1.0');
define('VNPAY_COMMAND', 'pay');
define('VNPAY_CURR_CODE', 'VND');
define('VNPAY_LOCALE', 'vn');
define('VNPAY_ORDER_TYPE', 'other');
