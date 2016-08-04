<?php

// ??????????deviceToken???????????????
$deviceToken = '20f8ba0e1c6bbf5f26f27abe7c6c30e27d8b2745a7408201c2208ee8da328f5c';

// Put your private key's passphrase here:
$passphrase = '123456';

// Put your alert message here:
$message = 'My first push test!';

////////////////////////////////////////////////////////////////////////////////

$ctx = stream_context_create();
stream_context_set_option($ctx, 'ssl', 'local_cert', 'ck.pem');
stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

// Open a connection to the APNS server
//??????????
 //$fp = stream_socket_client(?ssl://gateway.push.apple.com:2195?, $err, $errstr, 60, //STREAM_CLIENT_CONNECT, $ctx);
//?????????????appstore??????
$fp = stream_socket_client(
'ssl://gateway.sandbox.push.apple.com:2195', $err,
$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

if (!$fp)
exit("Failed to connect: $err $errstr" . PHP_EOL);

echo 'Connected to APNS' . PHP_EOL;

// Create the payload body
$body['aps'] = array(
'category' => 'kNotificationCategoryIdentifile',
'alert' => $message,
'sound' => 'default'
);

// Encode the payload as JSON
$payload = json_encode($body);

// Build the binary notification
$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

// Send it to the server
$result = fwrite($fp, $msg, strlen($msg));

if (!$result)
echo 'Message not delivered' . PHP_EOL;
else
echo 'Message successfully delivered' . PHP_EOL;

// Close the connection to the server
fclose($fp);
?>
