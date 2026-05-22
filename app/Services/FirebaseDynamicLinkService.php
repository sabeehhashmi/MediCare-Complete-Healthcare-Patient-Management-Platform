<?php 
namespace App\Services;

use GuzzleHttp\Client;

class FirebaseDynamicLinkService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://firebasedynamiclinks.googleapis.com/v1/',
        ]);
    }

    public function createShortLink($link, $suffix = 'SHORT')
    {
        try{
            $key = 'AIzaSyBA9H2De-f73eKsDymdt3SMSIrRA9-FeWg';// env('FIREBASE_API_KEY','AIzaSyBA9H2De-f73eKsDymdt3SMSIrRA9-FeWg');
            $dynamic_link_domain = 'https://Mednero.page.link';//env('FIREBASE_DYNAMIC_LINK_DOMAIN','https://Mednero.page.link');
            $url = 'shortLinks?key=' . $key;
    
            $payload = [
                'dynamicLinkInfo' => [
                    'domainUriPrefix' => $dynamic_link_domain,
                    'link' => $link,
                ],
                'suffix' => [
                    'option' => $suffix,
                ],
            ];
    
            $response = $this->client->post($url, [
                'json' => $payload,
            ]);
    
            $data = json_decode($response->getBody(), true);
    
            return $data['shortLink'] ?? null;
        } catch (RequestException $e) {
            // Log the error message for debugging
            printr($e->getResponse()->getBody()->getContents());
            return null;
        }
    }
}
