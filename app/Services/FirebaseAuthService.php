<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;

class FirebaseAuthService
{
    protected $jsonKeyPath;
    protected $httpClient;

    public function __construct()
    {
        $this->jsonKeyPath = base_path(config('services.firebase.credentials'));
        $this->httpClient = new Client();
    }
    public function getAccessToken()
    {
        try {
            // Load the service account credentials JSON file
            $jsonKey = json_decode(file_get_contents($this->jsonKeyPath), true);

            // Create a new instance of ServiceAccountCredentials with the required scope
            $credentials = new ServiceAccountCredentials(
                'https://www.googleapis.com/auth/firebase.messaging',
                $jsonKey
            );

            // Fetch the access token
            $authToken = $credentials->fetchAuthToken();

            return $authToken['access_token'];
        } catch (RequestException $e) {
            // Handle exceptions, e.g., log errors or throw a custom exception
            return null; // Or handle differently based on your application's needs
        }
    }

    public function getAccessToken33()
    {
        
        try {
            // Load the service account credentials JSON file
            $jsonKey = json_decode(file_get_contents($this->jsonKeyPath), true);
            
            // Create a new instance of ServiceAccountCredentials
            $credentials = new ServiceAccountCredentials(
                null,
                $jsonKey,
                // Define the scopes required for Firebase Messaging
                ['https://www.googleapis.com/auth/firebase.messaging'],
                null,
                // Set the Guzzle HTTP client
                $this->httpClient
            );

            // Fetch the access token
            $authToken = $credentials->fetchAuthToken();
            
            return $authToken['access_token'];
        } catch (RequestException $e) {
            printr($e->getMessage());
            // Handle exceptions, e.g., log errors or throw a custom exception
            return null; // Or handle differently based on your application's needs
        }
    }

    public function getAccessToken2()
    {
        $jsonKey = json_decode(file_get_contents(base_path(config('services.firebase.credentials'))), true);

        $client = new Client();
        $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];

        $credentials = new ApplicationDefaultCredentials($scopes, $jsonKey);

        $httpHandler = HttpHandlerFactory::build($client);
        $authToken = $credentials->fetchAuthToken($httpHandler);

        return $authToken['access_token'];
    }
    public function getAccessTokenOld()
    {
        $jsonKey = json_decode(file_get_contents(base_path(config('services.firebase.credentials'))), true);

        $client = new Client();
        $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];

        $oauth2 = new OAuth2([
            'audience' => OAuth2::TOKEN_CREDENTIAL_URI,
            'issuer' => $jsonKey['client_email'],
            'signingAlgorithm' => 'RS256',
            'signingKey' => $jsonKey['private_key'],
            'tokenCredentialUri' => OAuth2::TOKEN_CREDENTIAL_URI,
            'scope' => $scopes,
        ]);

        $httpHandler = HttpHandlerFactory::build($client);
        $authToken = $oauth2->fetchAuthToken($httpHandler);

        return $authToken['access_token'];
    }
}