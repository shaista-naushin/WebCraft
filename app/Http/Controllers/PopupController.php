<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\Plugin;
use App\Models\Popup;
use App\Models\Settings;
use App\Models\User;
use App\Models\Utils;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PopupController extends Controller
{
    public function getAll()
    {
        $popups = Popup::where('user_id', auth()->user()->id)->select(['id', 'user_id', 'name', 'status', 'created_at', 'updated_at'])->orderBy('created_at', 'desc')->get();
        return view('admin.popup.list', ['popup' => $popups]);
    }

    public function destroy($id)
    {
        $popup = Popup::where('id', $id)->where('user_id', auth()->user()->id)->first();

        if (!$popup) {
            return response()->json(['error' => 'Popup not found'], 400);
        }

        $popup->delete();

        session()->flash('success_msg', 'Popup deleted successfully');

        return redirect()->back();
    }

    public function create()
    {
        return view('admin.popup.create');
    }

    public function edit($id)
    {
        $popup = Popup::where('id', $id)->where('user_id', auth()->user()->id)->first();

        if (!$popup) {
            session()->flash('error_msg', 'Popup not found');
            return redirect()->back();
        }

        return view('admin.popup.edit', compact('popup'));
    }

    public function save()
    {
        $name = request('name');
        $title = request('title');
        $type = request('type');
        $animation = request('animation');

        $v = Validator::make(
            [
                'name' => $name,
                'title' => $title,
                'type' => $type,
                'animation' => $animation,
            ],
            [
                'name' => 'required',
                'title' => 'required',
                'type' => 'required',
                'animation' => 'required',
            ]
        );

        if ($v->fails()) {
            session()->flash('error_msg', Utils::messages($v));
            return redirect()->back()->withInput(request()->all());
        }

        try {
            $popup = new Popup();
            $popup->user_id = auth()->user()->id;
            $popup->name = $name;
            $popup->title = $title;
            $popup->type = $type;
            $popup->delay = request('delay');
            $popup->animation = $animation;
            $popup->status = 1;
            $popup->save();

            session()->flash('success_msg', 'Popup created successfully');
            return redirect('/popup/list');
        } catch (\Exception $e) {
            session()->flash('error_msg', $e->getMessage());
            return redirect()->back()->withInput(request()->all());
        }
    }

    public function update($id)
    {
        $popup = Popup::where('id', $id)->first();

        if (!$popup) {
            session()->flash('error_msg', 'Popup not found');
            return redirect()->back();
        }

        $name = request('name');
        $title = request('title');
        $type = request('type');
        $animation = request('animation');

        $v = Validator::make(
            [
                'name' => $name,
                'title' => $title,
                'type' => $type,
                'animation' => $animation,
            ],
            [
                'name' => 'required',
                'title' => 'required',
                'type' => 'required',
                'animation' => 'required',
            ]
        );

        if ($v->fails()) {
            session()->flash('error_msg', Utils::messages($v));
            return redirect()->back()->withInput(request()->all());
        }

        try {
            $popup->name = $name;
            $popup->title = $title;
            $popup->type = $type;
            $popup->delay = request('delay');
            $popup->animation = $animation;
            $popup->save();

            session()->flash('success_msg', 'Popup updated successfully');
            return redirect('/popup/list');
        } catch (\Exception $e) {
            session()->flash('success_msg', $e->getMessage());
            return redirect()->back()->withInput(request()->all());
        }
    }

    public function enable($id)
    {
        $popup = Popup::where('id', $id)->where('user_id', auth()->user()->id)->first();

        if (!$popup) {
            session()->flash('error_msg', 'Popup not found');
            return redirect()->back();
        }

        $popup->status = 1;
        $popup->save();

        return back();
    }

    public function disable($id)
    {
        $popup = Popup::where('id', $id)->where('user_id', auth()->user()->id)->first();

        if (!$popup) {
            session()->flash('error_msg', 'Popup not found');
            return redirect()->back();
        }

        $popup->status = 0;
        $popup->save();

        return back();
    }

    public function duplicate($id)
    {
        $popup = Popup::where('id', $id)->where('user_id', auth()->user()->id)->first();

        if (!$popup) {
            session()->flash('error_msg', 'Popup not found');
            return redirect()->back();
        }

        $newPopup = $popup->replicate()->fill([
            'name' => $popup->name . ' - copy'
        ]);

        $newPopup->save();

        return back();
    }

    public function editor($id)
    {
        $popup = Popup::where('id', $id)->where('user_id', auth()->user()->id)->first();

        if ($popup) {
            return view('admin.popup.editor', ['popup' => $popup]);
        }

        session()->flash('error_msg', 'Popup not found');
        return redirect()->to('/popup/list');
    }

    public function editorCSS($componentId)
    {
        $component = Component::where('id', $componentId)->first();
        if ($component) {
            return $component->custom_css;
        }

        return '';
    }

    public function editorJS($componentId)
    {
        $component = Component::where('id', $componentId)->first();
        if ($component) {
            return $component->custom_js;
        }

        return '';
    }

    public function getResponseCampaigns()
    {
        $getResponseCampaigns = [];

        $getResponseClientId = Utils::setting(Settings::GET_RESPONSE_CLIENT_ID);
        $getResponseClientSecret = Utils::setting(Settings::GET_RESPONSE_CLIENT_SECRET);

        if (!is_null(auth()->user()->get_response_access_token) && !is_null($getResponseClientId) && !is_null($getResponseClientSecret) && strlen(auth()->user()->get_response_access_token) > 0 && strlen($getResponseClientId) > 0 && strlen($getResponseClientSecret) > 0) {
            $client = new Client();
            $response = $client->post(
                'https://api.getresponse.com/v3/token', [
                    'auth' => [
                        $getResponseClientId, $getResponseClientSecret
                    ],
                    'json' => [
                        'grant_type' => 'refresh_token',
                        'refresh_token' => auth()->user()->get_response_refresh_token
                    ]
                ]
            );
            $body = $response->getBody();
            $newCreds = json_decode($body, true);
            $accessToken = $newCreds['access_token'];
            $refreshToken = $newCreds['refresh_token'];

            DB::table('users')->where('id', auth()->user()->id)->update(['get_response_access_token' => $accessToken, 'get_response_refresh_token' => $refreshToken]);

            $client = new Client();
            $accessToken = $newCreds['access_token'];

            $request = $client->get('https://api.getresponse.com/v3/campaigns',
                ['headers' => ['Authorization' => 'Bearer ' . $accessToken]]
            );

            $body = $request->getBody();
            $lists = json_decode($body, true);

            foreach ($lists as $list) {
                $getResponseCampaigns[] = ['campaign_id' => $list['campaignId'], 'account_name' => $list['name']];
            }
        }

        return $getResponseCampaigns;
    }

    public function getAweberLists()
    {
        $aweberLists = [];

        $aweberClientId = Utils::setting(Settings::AWEBER_CLIENT_ID);
        $aweberClientSecret = Utils::setting(Settings::AWEBER_CLIENT_SECRET);

        if (!is_null(auth()->user()->aweber_access_token) && !is_null($aweberClientId) && !is_null($aweberClientSecret) && strlen(auth()->user()->aweber_access_token) > 0 && strlen($aweberClientId) > 0 && strlen($aweberClientSecret) > 0) {
            $TOKEN_URL = 'https://auth.aweber.com/oauth2/token';
            $client = new Client();
            $clientId = $aweberClientId;
            $clientSecret = $aweberClientSecret;
            $response = $client->post(
                $TOKEN_URL, [
                    'auth' => [
                        $clientId, $clientSecret
                    ],
                    'json' => [
                        'grant_type' => 'refresh_token',
                        'refresh_token' => auth()->user()->aweber_refresh_token
                    ]
                ]
            );
            $body = $response->getBody();
            $newCreds = json_decode($body, true);
            $accessToken = $newCreds['access_token'];
            $refreshToken = $newCreds['refresh_token'];

            DB::table('users')->where('id', auth()->user()->id)->update(['aweber_access_token' => $accessToken, 'aweber_refresh_token' => $refreshToken]);

            $url = 'https://api.aweber.com/1.0/';
            $client = new Client();
            $accessToken = $newCreds['access_token'];

            // get all of the accounts
            $accounts = $this->getCollection($client, $accessToken, $url . 'accounts');

            foreach ($accounts as $account) {
                // get all the list entries for the first account
                $listsUrl = $account['lists_collection_link'];
                $lists = $this->getCollection($client, $accessToken, $listsUrl);


                foreach ($lists as $list) {
                    $aweberLists[] = ['account_id' => $account['id'], 'list_id' => $list['id'], 'list_name' => $list['name']];
                }
            }
        }

        return $aweberLists;
    }

    public function getCollection($client, $accessToken, $url)
    {
        $collection = array();
        while (isset($url)) {
            $request = $client->get($url,
                ['headers' => ['Authorization' => 'Bearer ' . $accessToken]]
            );
            $body = $request->getBody();
            $page = json_decode($body, true);
            $collection = array_merge($page['entries'], $collection);
            $url = isset($page['next_collection_link']) ? $page['next_collection_link'] : null;
        }
        return $collection;
    }

    public function editorFrame($id)
    {
        $adminIds = User::where('role', 'admin')->pluck('id');
        $popup = Popup::where('id', $id)->where('user_id', auth()->user()->id)->first();
        $components = Component::whereIn('user_id', $adminIds)->where('status', 1)->get();
        $plugins = Plugin::whereIn('user_id', $adminIds)->where('status', 1)->get();

        $aweberLists = $this->getAweberLists();

        $getResponseCampaigns = $this->getResponseCampaigns();

        $pluginsCssLinks = [];

        $pluginsJSLinks = [];

        $defaultCssLinks = [
            'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
            'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css',
            '/editor/stylesheets/bootstrap-social.css',
            '/editor/stylesheets/editor.css',
            '/editor/stylesheets/froala_blocks.min.css',
            'https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;500;600;700;800&family=Bangers&family=Concert+One&family=Graduate&family=Harmattan&family=Luckiest+Guy&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Orbitron:wght@400;500;600;700;800;900&family=Oswald:wght@200;300;400;500;600;700&family=Pacifico&family=Sen:wght@400;700;800&display=swap'
        ];

        $defaultJSLinks = [
            'https://code.jquery.com/jquery-3.3.1.slim.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js',
            'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js'
        ];

        foreach ($components as $component) {
            if (strlen($component->custom_css) > 0) {
                array_push($defaultCssLinks, url('/popup/editor/css/' . $component->id));
            }

            if (strlen($component->custom_js) > 0) {
                array_push($defaultJSLinks, url('/popup/editor/js/' . $component->id));
            }
        }

        foreach ($plugins as $plugin) {
            if (strlen($plugin->custom_css) > 0) {
                $links = explode("\n", trim($plugin->custom_css));
                foreach ($links as $link) {
                    $link = str_replace("\r", '', $link);
                    $link = str_replace("\n", '', $link);
                    array_push($pluginsCssLinks, $link);
                }
            }

            if (strlen($plugin->custom_js) > 0) {
                $links = explode("\n", trim($plugin->custom_js));
                foreach ($links as $link) {
                    $link = str_replace("\r", '', $link);
                    $link = str_replace("\n", '', $link);
                    array_push($pluginsJSLinks, $link);
                }
            }
        }

        return view('admin.popup.editor-frame',
            [
                'popup' => $popup,
                'components' => $components,
                'defaultCSSLinks' => $defaultCssLinks,
                'defaultJSLinks' => $defaultJSLinks,
                'plugins' => $plugins,
                'pluginsCSSLinks' => $pluginsCssLinks,
                'pluginsJSLinks' => $pluginsJSLinks,
                'aweberLists' => $aweberLists,
                'getResponseCampaigns' => $getResponseCampaigns
            ]);
    }

    public function editorLoad($id)
    {
        $popup = Popup::where('id', $id)->where('user_id', auth()->user()->id)->first();
        return response()->json([
            'gjs-assets' => $popup->assets,
            'gjs-components' => $popup->components,
            'gjs-css' => $popup->css,
            'gjs-html' => $popup->html,
            'gjs-styles' => $popup->styles
        ]);
    }

    public function uploadAsset(Request $request)
    {
        $rules = array('image' => 'required|image|mimes:jpeg,jpg,png,gif,svg|max:2048');
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        $imageName = time() . '.' . request()->image->getClientOriginalExtension();

        request()->image->move(public_path('images'), $imageName);

        $url = '/images/' . $imageName;

        $obj = new \stdClass();
        $obj->src = $url;
        $obj->type = 'image';

        return response()->json(['data' => [
            $obj
        ]]);
    }

    public function editorSave(Request $request, $id)
    {
        $popup = Popup::where('id', $id)->where('user_id', auth()->user()->id)->first();
        $popup->assets = $request->input('gjs-assets');
        $popup->components = $request->input('gjs-components');
        $popup->css = $request->input('gjs-css');
        $popup->html = $request->input('gjs-html');
        $popup->styles = $request->input('gjs-styles');
        $popup->save();

        return response()->json();
    }

    public function view($id)
    {
        $popup = Popup::where('id', $id)->first();
        $html = '';

        if ($popup) {
            $html = view('admin.pages.pop-up-stub', ['popup' => $popup])->toHtml();
        }

        $defaultCssLinks = [
            'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
            'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css',
            '/editor/stylesheets/bootstrap-social.css',
            '/editor/stylesheets/editor.css',
            '/editor/stylesheets/froala_blocks.min.css',
            'https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;500;600;700;800&family=Bangers&family=Concert+One&family=Graduate&family=Harmattan&family=Luckiest+Guy&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Orbitron:wght@400;500;600;700;800;900&family=Oswald:wght@200;300;400;500;600;700&family=Pacifico&family=Sen:wght@400;700;800&display=swap'
        ];

        $defaultJSLinks = [
            'https://code.jquery.com/jquery-3.3.1.slim.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js',
            'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js'
        ];

        return view('admin.popup.view',
            [
                'popup' => $popup,
                'html' => $html,
                'defaultCSSLinks' => $defaultCssLinks,
                'defaultJSLinks' => $defaultJSLinks
            ]);
    }
}
