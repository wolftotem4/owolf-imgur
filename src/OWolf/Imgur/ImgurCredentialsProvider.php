<?php

namespace OWolf\Imgur;

use Illuminate\Support\ServiceProvider;
use OWolf\Credentials\AccessTokenCredentials;
use OWolf\Credentials\ClientIdCredentials;
use OWolf\Laravel\UserOAuthManager;
use OWolf\Laravel\Util;
use WTotem4\OAuth2\Client\Provider\Imgur;

class ImgurCredentialsProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->resolving('owolf.provider', function ($manager, $app) {
            $manager->addDriver('imgur.oauth', function ($name, $config) {
                $oauth = array_get($config, 'oauth', []);

                $oauth['redirectUri'] = Util::redirectUri(array_get($oauth, 'redirectUri'), $name);

                $provider = new Imgur($oauth);
                return new ImgurOAuthHandler($provider, $name, $config);
            });
        });

        $this->app->resolving('owolf.credentials', function ($manager, $app) {
            $manager->addDriver('imgur.oauth', function ($name, $config) use ($app) {
                $manager = $this->app->make(UserOAuthManager::class);
                $session = $manager->session($name)->refreshExpired();
                return new AccessTokenCredentials($session->provider(), $session->getAccessToken());
            });

            $manager->addDriver('imgur.app', function ($name, $config) {
                $client_id = array_get($config, 'client_id');
                return new ClientIdCredentials(new Imgur(), $client_id);
            });
        });
    }
}