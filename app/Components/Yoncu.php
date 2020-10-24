<?php

namespace App\Components;

use Illuminate\Support\Arr;

class Yoncu
{
    public static function check($domains)
    {
        $domains = Arr::wrap($domains);
        $domains = implode(',', $domains);

        $YoncuUser    = '68845';
        $YoncuPass    = '8d67a99fcf12c78ddd0203a3efb4fcb840f743b0';

        $Post    = 'ka=' . urlencode($YoncuUser);
        $Post    .= '&sf=' . urlencode($YoncuPass);
        $Post    .= '&aa=' . urlencode($domains);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://www.yoncu.com/apiler/domain/get/sorgula.php");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_ENCODING, false);
        curl_setopt($ch, CURLOPT_COOKIESESSION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $Post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Connection: keep-alive',
            'User-Agent: ' . $_SERVER['SERVER_NAME'],
            'Referer: http://www.yoncu.com/',
            'Cookie: YoncuKoruma=' . $_SERVER['SERVER_ADDR'] . ';YoncuKorumaRisk=0',
        ));

        $response = json_decode(curl_exec($ch), true);

        curl_close($ch);

        if (isset($response[1]) === false) {
            return;
        }

        $domains = array_map(function ($domain) {
            return $domain !== 'DOLU';
        }, $response[1]);

        return $domains;
    }
}
