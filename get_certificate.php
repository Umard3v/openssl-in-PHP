<?php
//generate key pair
$private_key=openssl_pkey_new([
    "private_key_type"=>OPENSSL_KEYTYPE_RSA,
    "private_key_bits"=>2048,
]);
//for extracting the private key from $private_key to $privatekeyout
openssl_pkey_export($private_key, $privatekeyout);
//saving the private key to the file
file_put_contents('private-key.pem', $privatekeyout);
//extracting the public key from private key
$details=openssl_pkey_get_details($private_key);
$public_key=$details['key'];

file_put_contents('public-key.pem',$public_key);

echo("Private and Public keys generated successfully.\n");

//for creating csr
$dn=[
    "countryName"=>"PK",
    "stateOrProvinceName"=>"ISB",
    "localityName"=>"ISB",
    "organizationName"=>"NCP",
    "organizationalUnitName"=>"Ninvast",
    "commonName"=>"ncp.com",
    "emailAddress"=>"umarfa342@gmail.com"
];
$csr=openssl_csr_new($dn,$private_key,['digest_algo'=>'sha256']);
//exporting csr
openssl_csr_export($csr,$csrout);
//saving csr to the file
file_put_contents('csr.pem',$csrout);


echo("csr generated successfully");
/* Parse and print the CSR details
$csrInfo = openssl_csr_get_subject($csr);

echo "\n CSR Details:\n";
foreach ($csrInfo as $key => $value) {
    echo "$key: $value\n";
};*/

//using csr to create the self signed certificate
$certificate=openssl_csr_sign($csr,null,$private_key,365,['digest_algo'=>"sha256"]);

openssl_x509_export($certificate,$certificateout);
file_put_contents('ca-certificate.pem',$certificateout);

echo("\nself signed certificate generated.\n");

?>