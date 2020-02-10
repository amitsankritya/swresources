<?php
/**
 * Created by PhpStorm.
 * User: amitpandey
 * Date: 09/02/20
 * Time: 4:52 PM
 */

if (!function_exists('execute_api')) {
    function execute_api($endpoint, $method = "GET", $payload = array())
    {
        try {
            $handle = curl_init();

            if ($method == "GET" && !empty($payload)) {
                $endpoint = $endpoint . "?" . http_build_query($payload);
            }

            curl_setopt($handle, CURLOPT_URL, $endpoint);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($handle, CURLOPT_HEADER, false);
            if ($method == "POST" && !empty($payload)) {
                curl_setopt($handle, CURLOPT_POST, true);
                curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($payload));
            }

            $output = curl_exec($handle);
            curl_close($handle);
            return $output;
        } catch (\Exception $exception) {
            echo $exception->getMessage();
            die;
            //return json_encode($exception->getMessage());
        }
    }
}

if (!function_exists('get_api_data')) {
    function get_api_data($endpoint, $payload)
    {
        try {
            if (!empty($payload)) {
                $endpoint = $endpoint . "?" . rawurlencode($payload);
            }

            file_get_contents($endpoint);
        } catch (\Exception $exception) {
            return json_encode($exception->getMessage());
        }
    }
}