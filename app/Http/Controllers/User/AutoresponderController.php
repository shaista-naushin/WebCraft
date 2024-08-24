<?php

namespace App\Http\Controllers\User;

use AdEspresso\OAuth2\Client\Provider\GetResponse;
use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Models\Utils;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use League\OAuth2\Client\Provider\GenericProvider;
use Illuminate\Support\Facades\Request;

class AutoresponderController extends Controller
{
    public function listAll()
    {
        $enable_get_response = false;
        $enable_aweber = false;

        $get_response_client_id = Utils::setting(Settings::GET_RESPONSE_CLIENT_ID);
        $get_response_client_secret = Utils::setting(Settings::GET_RESPONSE_CLIENT_SECRET);

        $aweber_client_id = Utils::setting(Settings::AWEBER_CLIENT_ID);
        $aweber_client_secret = Utils::setting(Settings::AWEBER_CLIENT_SECRET);

        if (strlen($get_response_client_id) > 0 && strlen($get_response_client_secret)) {
            $enable_get_response = true;
        }

        if (strlen($aweber_client_id) > 0 && strlen($aweber_client_secret)) {
            $enable_aweber = true;
        }

        return view('user.autoresponder', ['enable_get_response' => $enable_get_response, 'enable_aweber' => $enable_aweber]);
    }

    public function getResponse(Request $request)
    {
        $redirectUri = url('/connect/get_response/callback');
        $clientId = Utils::setting(Settings::GET_RESPONSE_CLIENT_ID); //"584b4c27-bc48-11ea-bb1c-00163ec8ce26";
        $clientSecret = Utils::setting(Settings::GET_RESPONSE_CLIENT_SECRET); //f46d16fc50adc73a01103ef4e0fca90af86cf6b8;

        // Create a OAuth2 client configured to use OAuth for authentication
        $provider = new GetResponse([
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'redirectUri' => $redirectUri,
            'urlAuthorize' => 'https://app.getresponse.com/oauth2_authorize.html',
            'urlAccessToken' => 'https://api.getresponse.com/v3/token',
            'urlResourceOwnerDetails' => 'https://api.getresponse.com/v3/accounts'
        ]);

        $authorizationUrl = $provider->getAuthorizationUrl();

        return redirect()->to($authorizationUrl);
    }

    public function getResponseCallback(Request $request)
    {
        $redirectUri = url('/connect/get_response/callback');
        $clientId = Utils::setting(Settings::GET_RESPONSE_CLIENT_ID);
        $clientSecret = Utils::setting(Settings::GET_RESPONSE_CLIENT_SECRET);

        // Create a OAuth2 client configured to use OAuth for authentication
        $provider = new GetResponse([
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'redirectUri' => $redirectUri,
            'urlAuthorize' => 'https://app.getresponse.com/oauth2_authorize.html',
            'urlAccessToken' => 'https://api.getresponse.com/v3/token',
            'urlResourceOwnerDetails' => 'https://api.getresponse.com/v3/accounts'
        ]);

        $code = $request->code;

        $token = $provider->getAccessToken('authorization_code', [
            'code' => $code
        ]);

        $accessToken = $token->getToken();
        $refreshToken = $token->getRefreshToken();

        DB::table('users')->where('id', Auth::user()->id)->update(['get_response_access_token' => $accessToken, 'get_response_refresh_token' => $refreshToken]);

        session()->flash('success_msg', 'Get Response Successfully Connected');

        return redirect()->to('/autoresponder/list');

    }

    public function disconnectGetResponse()
    {
        DB::table('users')->where('id', auth()->user()->id)->update(['get_response_access_token' => null, 'get_response_refresh_token' => null]);
        session()->flash('success_msg', 'Get Response Successfully Disconnected');
        return redirect()->to('/autoresponder/list');
    }

    public function aweber(Request $request)
    {
        $redirectUri = url('/aweber/callback');
        $clientId = Utils::setting(Settings::AWEBER_CLIENT_ID); //"gDHtn8xqDaWT9I9KN9lZkyIOxXDgBH5g";
        $clientSecret = Utils::setting(Settings::AWEBER_CLIENT_SECRET);
        $OAUTH_URL = 'https://auth.aweber.com/oauth2/';

        $scopes = array(
            'account.read',
            'list.read',
            'subscriber.write'
        );

        // Create a OAuth2 client configured to use OAuth for authentication
        $provider = new GenericProvider([
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'redirectUri' => $redirectUri,
            'scopes' => $scopes,
            'scopeSeparator' => ' ',
            'urlAuthorize' => $OAUTH_URL . 'authorize',
            'urlAccessToken' => $OAUTH_URL . 'token',
            'urlResourceOwnerDetails' => 'https://api.aweber.com/1.0/accounts'
        ]);

        $authorizationUrl = $provider->getAuthorizationUrl();

        return redirect()->to($authorizationUrl);

    }

    public function aweberCallback(Request $request)
    {
        $redirectUri = url('/aweber/callback');
        $clientId = Utils::setting(Settings::AWEBER_CLIENT_ID);
        $clientSecret = Utils::setting(Settings::AWEBER_CLIENT_SECRET);
        $OAUTH_URL = 'https://auth.aweber.com/oauth2/';

        $scopes = array(
            'account.read',
            'list.read',
            'subscriber.write',
        );

        // Create a OAuth2 client configured to use OAuth for authentication
        $provider = new GenericProvider([
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'redirectUri' => $redirectUri,
            'scopes' => $scopes,
            'scopeSeparator' => ' ',
            'urlAuthorize' => $OAUTH_URL . 'authorize',
            'urlAccessToken' => $OAUTH_URL . 'token',
            'urlResourceOwnerDetails' => 'https://api.aweber.com/1.0/accounts'
        ]);

        $code = request()->input('code');

        $token = $provider->getAccessToken('authorization_code', [
            'code' => $code
        ]);

        $accessToken = $token->getToken();
        $refreshToken = $token->getRefreshToken();

        DB::table('users')->where('id', auth()->user()->id)->update(['aweber_access_token' => $accessToken, 'aweber_refresh_token' => $refreshToken]);

        session()->flash('success_msg', 'Aweber Successfully Connected');

        return redirect()->to('/user/autoresponder/list');

    }

    public function disconnectAweber()
    {
        DB::table('users')->where('id', auth()->user()->id)->update(['aweber_access_token' => null, 'aweber_refresh_token' => null]);
        session()->flash('success_msg', 'Aweber Successfully Disconnected');
        return redirect()->to('/autoresponder/list');
    }
}
