<?php

namespace OWolf\Imgur;

use League\OAuth2\Client\Token\AccessToken;
use OWolf\Laravel\Contracts\OAuthHandler;
use OWolf\Laravel\ProviderHandler;
use OWolf\Laravel\Traits\OAuthProvider;

class ImgurOAuthHandler extends ProviderHandler implements OAuthHandler
{
    use OAuthProvider;

    /**
     * @param  \League\OAuth2\Client\Token\AccessToken  $token
     * @return string|null
     */
    public function getName(AccessToken $token)
    {
        $resourceOwner = $this->getResourceOwner($token);
        return $resourceOwner->getBio();
    }

    /**
     * @param  \League\OAuth2\Client\Token\AccessToken  $token
     * @return string|null
     */
    public function getEmail(AccessToken $token)
    {
        return null;
    }
}
