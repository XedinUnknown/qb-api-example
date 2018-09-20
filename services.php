<?php

use League\OAuth2\Client\Provider\GenericProvider;
use XedinUnknown\QbApiExample\Auth_Handler;
use XedinUnknown\QbApiExample\DI_Container;

return function ($rootPath) {
    return [
        'base_dir' => $rootPath,
        'base_url' => 'http://wpra-php-70.local/qb',
        'access_token_file_name' => 'access_token',
        'refresh_token_file_name' => 'refresh_token',

        'client_id' => '',
        'client_secret' => '',

        'oauth_provider' => function ( DI_Container $c ) {
            return new GenericProvider([
                'clientId'                  => $c->get('client_id'),    // The client ID assigned to you by the provider
                'clientSecret'              => $c->get('client_secret'),   // The client password assigned to you by the provider
                'redirectUri'               => 'http://wpra-php-70.local/qb/auth.php',
                'urlAuthorize'              => 'https://appcenter.intuit.com/connect/oauth2',
                'urlAccessToken'            => 'https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer',
                'urlResourceOwnerDetails'   => 'http://brentertainment.com/oauth2/lockdin/resource',
                'scopes'	                => 'com.intuit.quickbooks.accounting'
            ]);
        },

        'auth_handler' => function ( DI_Container $c ) {
            return new Auth_Handler( $c );
        }
    ];
};
