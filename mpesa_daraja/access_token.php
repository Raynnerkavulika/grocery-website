<?php

$consumerKey = 'myconsumerkey';
$consumerSecret = 'myconsumersecret';

$credentials = base64_encode($consumerKey .':'. $consumerSecret);

$curl = curl_init();
curl_setopt($curl,CURLOPT_URL,'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant-type=client-credentials');
curl_setopt($curl,CURLOPT_HTTPHEADER,['Authorization:Basic'.$credentials]);
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);

$response = curl_exec($curl);
curl_close($curl);

$accessToken = json_decode($response,true)['access_token'];

echo $accessToken;