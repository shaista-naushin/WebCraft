<?php

namespace App\Http\Controllers;

use App\FormData;
use App\Page;
use App\Settings;
use App\User;
use App\Utils;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeadController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function captureLead()
    {
        $page = Page::where('id', request('x-form-page-id'))->first();
        if ($page) {

            $user = User::where('id', $page->user_id)->first();

            if (request()->has('x-form-autoresponder')) {
                $autoresponder = request()->input('x-form-autoresponder');

                if ($autoresponder == 'aweber' && request()->has('x-form-aweber-account')) {
                    $aweberClientId = Utils::setting(Settings::AWEBER_CLIENT_ID);
                    $aweberClientSecret = Utils::setting(Settings::AWEBER_CLIENT_SECRET);
                    $aweberData = explode('&', request('x-form-aweber-account'));

                    if ($aweberClientId && $aweberClientSecret && strlen($aweberClientId) > 0 && strlen($aweberClientSecret) > 0) {
                        $TOKEN_URL = 'https://auth.aweber.com/oauth2/token';
                        $client = new Client();
                        $response = $client->post(
                            $TOKEN_URL, [
                                'auth' => [
                                    $aweberClientId, $aweberClientSecret
                                ],
                                'json' => [
                                    'grant_type' => 'refresh_token',
                                    'refresh_token' => $user->aweber_refresh_token
                                ]
                            ]
                        );
                        $body = $response->getBody();
                        $newCreds = json_decode($body, true);
                        $accessToken = $newCreds['access_token'];
                        $refreshToken = $newCreds['refresh_token'];

                        DB::table('users')->where('id', $user->id)->update(['aweber_access_token' => $accessToken, 'aweber_refresh_token' => $refreshToken]);

                        $base_url = 'https://api.aweber.com/1.0/';
                        $subsUrl = $base_url . 'accounts/' . $aweberData[0] . '/lists/' . $aweberData[1] . '/subscribers';
                        $client = new Client();

                        $data = array(
                            'email' => request('email'),
                        );

                        try {
                            $client->post($subsUrl, [
                                'json' => $data,
                                'headers' => ['Authorization' => 'Bearer ' . $accessToken]
                            ]);
                        } catch (\Exception $e) {
                        }
                    }
                }

                if ($autoresponder == 'get_response' && request()->has('x-form-get-response-campaign')) {
                    $getResponseClientId = Utils::setting(Settings::GET_RESPONSE_CLIENT_ID);
                    $getResponseClientSecret = Utils::setting(Settings::GET_RESPONSE_CLIENT_SECRET);
                    $getResponseList = request('x-form-get-response-campaign');

                    if ($getResponseClientId && $getResponseClientSecret && strlen($getResponseClientId) > 0 && strlen($getResponseClientSecret) > 0) {
                        $client = new Client();
                        $response = $client->post(
                            'https://api.getresponse.com/v3/token', [
                                'auth' => [
                                    $getResponseClientId, $getResponseClientSecret
                                ],
                                'json' => [
                                    'grant_type' => 'refresh_token',
                                    'refresh_token' => $user->get_response_refresh_token
                                ]
                            ]
                        );
                        $body = $response->getBody();
                        $newCreds = json_decode($body, true);
                        $accessToken = $newCreds['access_token'];
                        $refreshToken = $newCreds['refresh_token'];

                        DB::table('users')->where('id', $user->id)->update(['get_response_access_token' => $accessToken, 'get_response_refresh_token' => $refreshToken]);

                        $client = new Client();

                        $cStd = new \stdClass();
                        $cStd->campaignId = $getResponseList;

                        $data = [
                            'email' => request('email'),
                            "campaign" => $cStd
                        ];

                        try {
                            $client->post('https://api.getresponse.com/v3/contacts', [
                                'json' => $data,
                                'headers' => ['Authorization' => 'Bearer ' . $accessToken]
                            ]);
                        } catch (\Exception $e) {
                        }
                    }
                }
            }

            return $this->captureDefault();
        }

        session()->flash('error_msg', 'Invalid page specified');
        return redirect()->back();
    }

    public function captureDefault()
    {
        $allInputs = request()->except(['x-form-type', 'x-form-autoresponder', 'x-form-aweber-account', 'x-form-aweber-list', 'x-form-get-response-campaign']);

        $name = null;
        $email = null;

        if (request()->has('name')) {
            $name = request('name');
        }

        if (request()->has('email')) {
            $email = request('email');
        }

        $data = new FormData();
        $data->page_id = request('x-form-page-id');
        $data->name = $name;
        $data->email = $email;
        $data->extra = json_encode($allInputs);
        $data->save();

        $redirectType = request('x-form-redirect-type');

        if ($redirectType) {
            if ($redirectType === 'same' && request()->has('x-form-redirect-message')) {
                session()->flash('success_msg', request('x-form-redirect-message'));
                return redirect()->back();
            }

            if ($redirectType === 'url' && request()->has('x-form-redirect-url')) {
                $redirect = request('x-form-redirect-url');
                if (strlen($redirect) > 0) {
                    return redirect()->away($redirect);
                }
            }
        } else {
            return redirect()->back();
        }

        session()->flash('success_msg', request('x-form-success'));
        return redirect()->back();
    }
}
