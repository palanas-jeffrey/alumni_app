<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config.php';

use GuzzleHttp\Client;

$response = null;
$checkout_url = null;
$encodedString = base64_encode($PAYMONGO_SECRET_KEY);

function startPayMongoTransaction($amount, $encodedString) {
    global $checkout_url;
    try {
        $client = new Client();
        $response = $client->request('POST', 'https://api.paymongo.com/v1/links', [
            'body' => json_encode([
                'data' => [
                    'attributes' => [
                        'amount' => $amount,
                        'description' => 'School donation'
                    ]
                ]
            ]),
            'headers' => [
                'accept' => 'application/json',
                'authorization' => 'Basic '. $encodedString,
                'content-type' => 'application/json',
            ],
        ]);

        $responseBody = $response->getBody(); 
        $responseData = json_decode($responseBody, true); 
        $checkout_url = $responseData['data']['attributes']['checkout_url'];

    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>