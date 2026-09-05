<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LeadsController extends Controller
{
    private $folder = "meta.leads.";

    public function index(Request $request)
    {
        $verify_token = 'EAAa3r5oIkccBOyqHAenvrJx7eIqeLgNGKktJZBdI8Atxyw4ZCPBiKJ46xAxcbrTrCXsVrEe54SvAlRFfWPShNzBmCC9QZCcuL5qr21XCUDyg5GZBzFJWwhNjABrmKoZAqZAfQEgigdy1ioeSbqCaea8XSZCK0vrx9WzZArdYaCrefHc1w2b3UVZCXMnsZA9m1zno6AqaopZB6Yo';


                $lead_id = "122097828434861823";
        $accessToken = $verify_token;
        $url = "https://graph.facebook.com/v19.0/$lead_id?access_token=$accessToken";

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HTTPGET => true,
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            // handle error
            $error_msg = curl_error($ch);
            curl_close($ch);
            return response()->json(['error' => $error_msg], 500);
        }

        curl_close($ch);
        $data = json_decode($response, true);
d($data,1);

        return View($this->folder.'index');
    }
    // FacebookWebhookController.php
    public function verify(Request $request)
    {
        $verify_token = 'EAAa3r5oIkccBOyqHAenvrJx7eIqeLgNGKktJZBdI8Atxyw4ZCPBiKJ46xAxcbrTrCXsVrEe54SvAlRFfWPShNzBmCC9QZCcuL5qr21XCUDyg5GZBzFJWwhNjABrmKoZAqZAfQEgigdy1ioeSbqCaea8XSZCK0vrx9WzZArdYaCrefHc1w2b3UVZCXMnsZA9m1zno6AqaopZB6Yo';

        if ($request->hub_verify_token === $verify_token) {
            return response($request->hub_challenge);
        }

        return response('Invalid verification token', 403);
    }


}
