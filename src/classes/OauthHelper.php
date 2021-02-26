<?php

namespace src\classes;

class OauthHelper
{

    /**
     * OAuth 1 Headers generator based on the given parameters
     *
     * @param string $method request method e.g GET, POST, PUT
     * @param string $url full url of the endpoint
     * @param string $consumer_key consumer key received from the third party OAuth system
     * @param string $consumer_secret consumer secret received from the third party OAuth system
     * @param string|null $token OPTIONAL: access token
     * @param string|null $token_secret OPTIONAL: access token secret
     *
     * @return [type]
     */
    public static function getHeadersForOauth($method, $url, $consumer_key, $consumer_secret, $token = null, $token_secret = null)
    {

        $oauth_nonce      = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 20);
        $timestamp        = time();
        $signature_method = 'HMAC-SHA1';
        $oauth_version    = '1.0';

        $params = [
            'oauth_consumer_key'     => $consumer_key,
            'oauth_signature_method' => $signature_method,
            'oauth_nonce'            => $oauth_nonce,
            'oauth_timestamp'        => $timestamp,
            'oauth_version'          => $oauth_version,
        ];

        if (!empty($token)) {
            $params['oauth_token'] = $token;
        }

        ksort($params);

        $signature = "$method&" . rawurlencode($url) . "&" . rawurlencode(http_build_query($params));

        $sig_string = rawurlencode($consumer_secret) . "&" . (empty($token_secret) ? "" : rawurlencode($token_secret));
        $signature  = base64_encode(hash_hmac("sha1", $signature, $sig_string, true));

        $headers = array(
            'Authorization: OAuth '
            . 'oauth_consumer_key="' . $params['oauth_consumer_key'] . '", '
            . 'oauth_nonce="' . $params['oauth_nonce'] . '", '
            . 'oauth_signature_method="' . $params['oauth_signature_method'] . '", '
            . 'oauth_timestamp="' . $params['oauth_timestamp'] . '", '
            . (!empty($token) ? 'oauth_timestamp="' . $params['oauth_timestamp'] . '", ' : "")
            . 'oauth_version="' . $params['oauth_version'] . '", '
            . 'oauth_signature="' . $signature . '"'
            , "Content-Type: application/json; charset=utf-8"
            , 'Content-Length: ' . strlen(""),
        );

        return $headers;
    }
}
