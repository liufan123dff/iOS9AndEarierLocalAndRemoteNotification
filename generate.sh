openssl x509 -in aps_development.cer -inform der -out PushCert.pem

openssl pkcs12 -nocerts -out PushKey.pem -in key.p12

cat PushCert.pem PushKey.pem > ck.pem

telnet gateway.sandbox.push.apple.com 2195

openssl s_client -connect gateway.sandbox.push.apple.com:2195 -cert PushCert.pem -key PushKey.pem