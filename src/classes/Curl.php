<?php

namespace src\classes;

class Curl
{

    protected $_url;

    public function __construct($url)
    {
        $this->_url = $url;
    }

    public function performPost($headers = array(), $data = array())
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL            => $this->_url,
            CURLOPT_POST           => true,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_RETURNTRANSFER => true,
        ));

        if (!empty($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }
        try {
            $data = curl_exec($curl);
            curl_close($curl);

            if (json_decode($data) != null) {
                $data = json_decode($data, true);
            } else {
                $res = array();
                parse_str($data, $res);
                $data = !empty($res) ? $res : $data;
            }

            return $data;
        } catch (\Exception $e) {
        }
    }

    public function performGet($headers = array())
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL            => $this->_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => 'GET',
        ));

        if (!empty($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        try {
            $data = curl_exec($curl);
            curl_close($curl);

            if (json_decode($data) != null) {
                $data = json_decode($data, true);
            } else {
                $res = array();
                parse_str($data, $res);
                $data = !empty($res) ? $res : $data;
            }

            return $data;
        } catch (\Exception $e) {
        }
    }
}
