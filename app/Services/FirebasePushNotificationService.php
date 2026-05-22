<?php

namespace App\Services;

use GuzzleHttp\Client;

class FirebasePushNotificationService
{
    protected $firebaseAuthService;
    protected $httpClient;

    public function __construct(FirebaseAuthService $firebaseAuthService)
    {
        $this->firebaseAuthService = $firebaseAuthService;
        $this->httpClient = new Client();
    }

    public function sendNotification($targetToken, $title, $body)
    {
        try {
            $accessToken = $this->firebaseAuthService->getAccessToken();
            
            $response = $this->httpClient->post('https://fcm.googleapis.com/v1/projects/'.env('FIREBASE_PROJECT_ID').'/messages:send', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'message' => [
                        'token' => $targetToken,
                        'notification' => $title,
                        'data'=>$body,
                    ],
                ],
            ]);
            printr($response->getBody()->getContents());
            return $response->getBody()->getContents();
        } catch (RequestException $e) {
            // Handle exceptions, e.g., log errors or throw a custom exception
            return null; // Or handle differently based on your application's needs
        }
    }

    public function sendNotificationsss($targetToken, $title, $body)
    {
        try {
            $accessToken = $this->firebaseAuthService->getAccessToken();
            
            // Prepare the Firebase Cloud Messaging API request
            $response = $this->httpClient->post('https://fcm.googleapis.com/v1/projects/'.env('FIREBASE_PROJECT_ID').'/messages:send', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'message' => [
                        'token' => $targetToken,
                        'notification' => [
                            'title' => "test",
                            'body' => "desc",
                        ],
                    ],
                ],
            ]);
            printr($response); exit;
            
            $resp= $response->getBody()->getContents();
            printr(json_decode($resp));
        } catch (RequestException $e) {
            printr($e->getMessage());
            // Handle exceptions, e.g., log errors or throw a custom exception
            return null; // Or handle differently based on your application's needs
        }
    }

    public function sendNotificationOld($targetToken, $title, $body)
    {
        $accessToken = $this->authService->getAccessToken();

        $client = new Client();
        $response = $client->post('https://fcm.googleapis.com/v1/projects/'.env('FIREBASE_PROJECT_ID').'/messages:send', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'message' => [
                    'token' => $targetToken,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                ],
            ],
        ]);

        return $response->getBody()->getContents();
    }
}