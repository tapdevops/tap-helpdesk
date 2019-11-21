<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;

class ApiHelper extends ServiceProvider
{
    static function exec($param = array())
    {
        $public_key = 'B8C1FB623AA3CE4FCBAABC2CBCD18';
        $secret_key = 'B4B18358EEF2E9AEDE1FACE2E8A8A';
        $header = array(
            'Content-Type' => 'application/json',
            'AccessToken' => 'key',
            'Authorization' => 'Bearer e8NDkyjDgqvapG5XnIH6nVgq3QJTkwcTg6MpRlYVRpn3oOojoSmZaV54bYug6XfUfTQzmX37XzLoMEHLSNYqV53NuT2PcHFblFFi'
        );
        $api = "http://149.129.224.117:8080/api/";
        $ldap = "http://tap-ldapdev.tap-agri.com/";

        if (!empty($param['host'])) {
            if ($param['host'] == 'ldap') {
                $url = $ldap . ($param['method'] == 'login' ? $param['method'] : 'data-sap/' . $param['method']);
            } else {
                $url = $api . $param['method'];
            }
        } else {

            $url = $api . $param['method'];
        }

        $client = new Client();
        switch ($param["request"]) {
            case 'GET':

                $params = array(
                    'headers' => $header
                );

                $res = $client->request('GET', $url, $params);
                break;

            case 'POST':
                $params = [
                    'json' => $param['data'],
                    'headers' => $header
                ];

                $res = $client->request('POST', $url, $params);
                break;
            case 'DELETE':
                $params = [
                    'headers' => $header
                ];
                $res = $client->request('DELETE', $url, $params);
                break;
            case 'PUT':
                $params = [
                    'json' => $param['data'],
                    'headers' => $header,
                    'allow_redirects' => false,
                    'timeout' => 5
                ];

                $res = $client->request('PUT', $url, $params);
                break;
            case 'ACTIVE':
                $params = [
                    'json' => $param['data'],
                    'headers' => $header,
                ];

                $res = $client->request('DELETE', $url, $params);
                break;
        }

        $result = json_decode($res->getBody()->getContents());
        return $result;
    }

    
}
