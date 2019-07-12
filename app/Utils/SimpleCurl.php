<?php

/**
 * Curl class
 *
 * @author SecretD - https://github.com/SecretD
 * @version 3.0
 */

namespace App\Utils;


/**
 * Sets some default functions and settings.
 */
class SimpleCurl {

    /**
     * Performs a get request on the chosen link and the chosen parameters
     * in the array.
     *
     * @param string $url
     * @param array $params
     *
     * @return string returns the content of the given url
     */
    public static function get($url, $fields = array() , $headers = array()) {
        $ch = curl_init();
        $url = $url."?". urldecode( http_build_query($fields));
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_USERAGENT => isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER['HTTP_USER_AGENT'] : "",
            CURLOPT_HEADER => false
        );
        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    /**
     * Performs a post request on the chosen link and the chosen parameters
     * in the array.
     *
     * @param string $url
     * @param array $fields
     *
     * @return string returns the content of the given url after post
     */
    public static function post($url, $fields = array() ,$headers = array()) {
        $ch = curl_init();
        //$fields['iprequest'] = $_SERVER[""];
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 150,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => http_build_query($fields),
            CURLOPT_POST => true,
            CURLOPT_USERAGENT => isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER['HTTP_USER_AGENT'] : "",
        );
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    /**
     * Performs a put request on the chosen link and the chosen parameters
     * in the array.
     *
     * @param string $url
     * @param array $fields
     *
     * @return string with the contents of the site
     */
    public static function put($url, $fields = array()) {
        $post_field_string = http_build_query($fields);
        $ch = curl_init($url);

        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => $post_field_string
        );
        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
		
		
		
        curl_close($ch);

        return $response;
    }

}
