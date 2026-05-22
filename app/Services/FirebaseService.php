<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\DynamicLink\ShortenLongDynamicLink;
use Kreait\Firebase\Contract\DynamicLinks;
use Kreait\Firebase\DynamicLink\ShortenLongDynamicLink\FailedToShortenLongDynamicLink;

class FirebaseService
{
    protected $database;
    protected $dynamicLinks;

    public function __construct()
    {

        $credentials = config('services.firebase.credentials');
        $firebaseConfigPath = $credentials ? base_path($credentials) : null;

        if ($firebaseConfigPath && file_exists($firebaseConfigPath) && !is_dir($firebaseConfigPath)) {
            $firebase = (new Factory)
                ->withServiceAccount($firebaseConfigPath)
                ->withDatabaseUri(config('services.firebase.database') ?? 'https://mydoctorworld-e6907-default-rtdb.firebaseio.com');

            $this->database = $firebase->createDatabase();

            $factory = (new Factory)->withServiceAccount($firebaseConfigPath);
            $dynamicLinksDomain = 'https://mednero.page.link';
            $this->dynamicLinks = $factory->createDynamicLinksService($dynamicLinksDomain);
        }
    }

    public function getDatabase()
    {
        return $this->database;
    }

    public function shortenUrl($longUrl)
    {
        //$longLink = 'https://mednero.page.link?'.$longUrl;
        try {
            echo $link = $this->dynamicLinks->shortenLongDynamicLink($longLink);
            //$link = $this->dynamicLinks->shortenLongDynamicLink($longLink, ShortenLongDynamicLink::WITH_UNGUESSABLE_SUFFIX);
            //echo $link = $this->dynamicLinks->shortenLongDynamicLink($longLink, ShortenLongDynamicLink::WITH_SHORT_SUFFIX);
        } catch (FailedToShortenLongDynamicLink $e) {
            echo $e->getMessage(); exit;
        }
        
    }

    
}