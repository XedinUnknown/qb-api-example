<?php
/**
 * Auth_Handler class.
 *
 * @package QbApiExample
 */

namespace XedinUnknown\QbApiExample;

use League\OAuth2\Client\Token\AccessToken;

/**
 * Handles authentication.
 *
 * @package QbApiExample
 */
class Auth_Handler extends Handler {

    public function hook() {
        $script = basename($_SERVER['SCRIPT_FILENAME']);

        if($script === 'index.php') {
            $this->main_page();
        }
        if($script === 'auth.php') {
            $this->auth_page();
        }
    }

    protected function main_page() {
        if(!($token = $this->get_token())) {
            header(vsprintf('Location: %1$s/auth.php', [$this->get_config('base_url')]));
            die();
        }
    }


    protected function auth_page() {
        $provider = $this->get_config('oauth_provider');
        /* @var $provider \League\OAuth2\Client\Provider\GenericProvider */

        // First time on this page - no code passed from QB API
        if (!isset($_GET['code'])) {
            $redirectUrl = $provider->getAuthorizationUrl();
            $_SESSION['oauth2state'] = $provider->getState();
            header(vsprintf('Location: %1$s', [$redirectUrl]));
            die();
        }

        // Checking integrity of data passed from QB API after redirect
        if (!isset($_SESSION['oauth2state']) || !isset($_GET['state']) || $_SESSION['oauth2state'] !== $_GET['state']) {
            echo 'Security problem';
            die();
        }

        unset($_SESSION['oauth2state']);
        $accessToken = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        $this->save_token($accessToken);
        header(vsprintf('Location: %1$s', [$this->get_url()]));

        return;
    }

    /**
     * @return AccessToken
     */
    public function get_token() {
        $tokenData = (array) json_decode(file_get_contents($this->get_token_file_path()));

        return new AccessToken($tokenData);
    }

    /**
     * Saves token.
     *
     * @param AccessToken $token Token to save
     */
    public function save_token(AccessToken $token) {
        file_put_contents($this->get_token_file_path(), json_encode($token->jsonSerialize()));
    }

    /**
     * @return string The path to the token file.
     */
    protected function get_token_file_path() {
        $rootPath = rtrim($this->get_config('base_dir'), '/');
        $tokenFile = ltrim($this->get_config('access_token_file_name'), '/' );
        $tokenFilePath = "$rootPath/$tokenFile";

        return $tokenFilePath;
    }

}
