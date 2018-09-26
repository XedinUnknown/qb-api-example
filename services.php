<?php

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\GenericProvider;
use QuickBooksOnline\API\DataService\DataService;
use XedinUnknown\QbApiExample\Auth_Handler;
use XedinUnknown\QbApiExample\DI_Container;

return function ($rootPath) {
    return [
        'base_dir' => $rootPath,
        'base_url' => 'http://wpra-php-70.local/qb',
        'access_token_file_name' => 'access_token',
        'refresh_token_file_name' => 'refresh_token',
        'log_dir' => 'logs',
        'log_dir_path' => function ( DI_Container $c ) {
            $base_dir = rtrim($c->get('base_dir'), '/');
            $log_dir = ltrim($c->get('log_dir'), '/');

            return "$base_dir/$log_dir";
        },

        'client_id' => '',
        'client_secret' => '',

        'oauth_provider' => function ( DI_Container $c ): AbstractProvider {
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

        'auth_handler' => function ( DI_Container $c ): Auth_Handler {
            return new Auth_Handler( $c );
        },

        'data_service' => function ( DI_Container $c ): DataService {
            $handler =  $c->get('auth_handler');
            /* @var $handler Auth_Handler */

            $token = $handler->get_token();

            $service = DataService::Configure(array(
                'auth_mode' => 'oauth2',
                'ClientID' => $c->get('client_id'),
                'ClientSecret' => $c->get('client_secret'),
                'accessTokenKey' =>  $token->getToken(),
                'refreshTokenKey' => $token->getRefreshToken(),
                'QBORealmID' => '123146096244749',
                'baseUrl' => 'https://sandbox-quickbooks.api.intuit.com'
            ));

            $service->setLogLocation($c->get('log_dir_path'));

            return $service;
        },
    ];
};
