<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\Page;
use App\Models\Plugin;
use App\Models\Popup;
use App\Models\Settings;
use App\Models\User;
use App\Models\Utils;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PHPHtmlParser\Dom;
use Illuminate\Support\Facades\URL;

class PagesController extends Controller
{
    public function getAll()
    {
        $pages = Page::where('user_id', auth()->user()->id)->select(['id', 'user_id', 'type', 'name', 'title', 'description', 'keywords', 'redirect_page', 'preview_image', 'status', 'created_at', 'updated_at'])->orderBy('created_at', 'desc')->get();
        return view('admin.pages.list', ['pages' => $pages]);
    }

    public function destroy($id)
    {
        $page = Page::where('id', $id)->where('user_id', auth()->user()->id)->first();

        if (!$page) {
            return response()->json(['error' => 'Page not found'], 400);
        }

        $page->delete();

        session()->flash('success_msg', 'Page deleted successfully');

        return redirect()->back();
    }

    public function create()
    {
        $popups = Popup::where('status', 1)->where('user_id', auth()->user()->id)->get();
        return view('admin.pages.create', ['popups' => $popups]);
    }

    public function edit($id)
    {
        $page = Page::where('id', $id)->where('user_id', auth()->user()->id)->first();

        if (!$page) {
            session()->flash('error_msg', 'Page not found');
            return redirect()->back();
        }

        $popups = Popup::where('status', 1)->where('user_id', auth()->user()->id)->get();

        return view('admin.pages.edit', compact('page', 'popups'));
    }

    public function save()
    {
        $type = request('type');
        $name = request('name');
        $title = request('title');
        $description = request('description');
        $keywords = request('keywords');
        $redirect_page = request('redirect_page');
        $popup = request('popup');

        if (intval($popup) == 0) {
            $popup = null;
        }

        $default_page_preview = URL::to('/') . "/assets/img/default-page.png";

        $v = Validator::make(
            [
                'name' => $name,
            ],
            [
                'name' => 'required'
            ]
        );

        if ($v->fails()) {
            session()->flash('error_msg', Utils::messages($v));
            return redirect()->back()->withInput(request()->all());
        }


        if (request()->hasFile('page_preview')) {
            $rules = array('page_preview' => 'required|image|mimes:jpeg,jpg,png,gif');
            $validator = \Illuminate\Support\Facades\Validator::make(request()->all(), $rules);

            if ($validator->fails()) {
                session()->flash('error_msg', Utils::messages($validator));
                return redirect()->back()->withInput(request()->all());
            }

            $imageName = time() . '.' . request()->page_preview->getClientOriginalExtension();

            request()->page_preview->move(public_path('images'), $imageName);

            $default_page_preview = '/images/' . $imageName;
        }

        try {
            $page = new Page();
            $page->user_id = auth()->user()->id;
            $page->type = $type;
            $page->name = $name;
            $page->title = $title;
            $page->description = $description;
            $page->keywords = $keywords;
            $page->redirect_page = $redirect_page;
            $page->preview_image = $default_page_preview;
            $page->popup = $popup;

            if (request()->has('page_selected') && !is_null(request()->input('page_selected'))) {
                $replicatePage = Page::where('id', request()->input('page_selected'))->first();
                if ($replicatePage) {
                    $page->assets = $replicatePage->assets;
                    $page->components = $replicatePage->components;
                    $page->css = $replicatePage->css;
                    $page->html = $replicatePage->html;
                    $page->styles = $replicatePage->styles;
                }
            }

            $page->status = 1;
            $page->save();

            session()->flash('success_msg', 'Page created successfully');
            return redirect('/pages/list');
        } catch (Exception $e) {
            session()->flash('error_msg', $e->getMessage());
            return redirect()->back()->withInput(request()->all());
        }
    }

    public function update($id)
    {
        $page = Page::where('id', $id)->first();

        if (!$page) {
            session()->flash('error_msg', 'Page not found');
            return redirect()->back();
        }

        $name = request('name');
        $title = request('title');
        $description = request('description');
        $keywords = request('keywords');
        $redirect_page = request('redirect_page');
        $default_page_preview = request('default_page_preview');
        $popup = request('popup');

        if ($popup == 0) {
            $popup = null;
        }

        $v = Validator::make(
            [
                'name' => $name
            ],
            [
                'name' => 'required'
            ]
        );

        if ($v->fails()) {
            session()->flash('error_msg', Utils::messages($v));
            return redirect()->back()->withInput(request()->all());
        }

        if (request()->hasFile('page_preview')) {
            $rules = array('page_preview' => 'required|image|mimes:jpeg,jpg,png,gif');
            $validator = \Illuminate\Support\Facades\Validator::make(request()->all(), $rules);

            if ($validator->fails()) {
                session()->flash('error_msg', Utils::messages($validator));
                return redirect()->back()->withInput(request()->all());
            }

            $imageName = time() . '.' . request()->page_preview->getClientOriginalExtension();

            request()->page_preview->move(public_path('images'), $imageName);

            $default_page_preview = '/images/' . $imageName;
        }

        try {
            $page->name = $name;
            $page->title = $title;

            if (request()->has('type')) {
                $page->type = request()->input('type');
            }

            $page->description = $description;
            $page->keywords = $keywords;
            $page->redirect_page = $redirect_page;
            $page->preview_image = $default_page_preview;
            $page->popup = $popup;
            $page->save();

            session()->flash('success_msg', 'Page updated successfully');
            return redirect('/pages/list');
        } catch (Exception $e) {
            session()->flash('error_msg', $e->getMessage());
            return redirect()->back()->withInput(request()->all());
        }
    }

    public function enable($id)
    {
        $page = Page::where('id', $id)->where('user_id', auth()->user()->id)->first();

        if (!$page) {
            session()->flash('error_msg', 'Page not found');
            return redirect()->back();
        }

        $page->status = 1;
        $page->save();

        return back();
    }

    public function disable($id)
    {
        $page = Page::where('id', $id)->where('user_id', auth()->user()->id)->first();

        if (!$page) {
            session()->flash('error_msg', 'Page not found');
            return redirect()->back();
        }

        $page->status = 0;
        $page->save();

        return back();
    }

    public function duplicate($id)
    {
        $page = Page::where('id', $id)->where('user_id', auth()->user()->id)->first();

        if (!$page) {
            session()->flash('error_msg', 'Page not found');
            return redirect()->back();
        }

        $newPage = $page->replicate()->fill([
            'name' => $page->name . ' - copy'
        ]);

        $newPage->save();

        return back();
    }

    public function editor($pageId)
    {
        $page = Page::where('id', $pageId)->where('user_id', auth()->user()->id)->first();

        if ($page) {
            return view('admin.pages.editor', ['page' => $page]);
        }

        session()->flash('error_msg', 'Page not found');
        return redirect()->to('/pages/list');
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

    public function editorFrame($pageId)
    {
        $adminIds = User::where('role', 'admin')->pluck('id');
        $page = Page::where('id', $pageId)->where('user_id', auth()->user()->id)->first();
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
                array_push($defaultCssLinks, url('/pages/editor/css/' . $component->id));
            }

            if (strlen($component->custom_js) > 0) {
                array_push($defaultJSLinks, url('/pages/editor/js/' . $component->id));
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

        $popups = Popup::where('status', 1)->where('user_id', auth()->user()->id)->get();

        return view('admin.pages.editor-frame',
            [
                'page' => $page,
                'components' => $components,
                'defaultCSSLinks' => $defaultCssLinks,
                'defaultJSLinks' => $defaultJSLinks,
                'plugins' => $plugins,
                'pluginsCSSLinks' => $pluginsCssLinks,
                'pluginsJSLinks' => $pluginsJSLinks,
                'popups' => $popups,
                'aweberLists' => $aweberLists,
                'getResponseCampaigns' => $getResponseCampaigns
            ]);
    }

    public function editorLoadPage($pageId)
    {
        $page = Page::where('id', $pageId)->where('user_id', auth()->user()->id)->first();
        return response()->json([
            'gjs-assets' => $page->assets,
            'gjs-components' => $page->components,
            'gjs-css' => $page->css,
            'gjs-html' => $page->html,
            'gjs-styles' => $page->styles
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

    public function editorSavePage(Request $request, $pageId)
    {
        $page = Page::where('id', $pageId)->where('user_id', auth()->user()->id)->first();
        $page->assets = $request->input('gjs-assets');
        $page->components = $request->input('gjs-components');
        $page->css = $request->input('gjs-css');
        $page->html = $request->input('gjs-html');
        $page->styles = $request->input('gjs-styles');
        $page->save();

        return response()->json();
    }

    public function viewPage($pageId)
    {
        $page = Page::where('id', $pageId)->first();

        $popup = null;

        if ($page->popup) {
            $popup = Popup::where('id', $page->popup)->first();
        }

        if ($popup) {
            $html = view('admin.pages.pop-up-stub', ['popup' => $popup])->toHtml();
            $page->html = $html . $page->html;
        }

        $filterIds = [];

        if (strlen($page->html) === 0) {
            return view('admin.pages.view',
                [
                    'page' => $page,
                    'popup' => $popup,
                    'components' => [],
                    'defaultCSSLinks' => [],
                    'defaultJSLinks' => []
                ]);
        }

        $dom = new Dom();
        $dom->loadStr($page->html);
        $elements = $dom->find('*[data-id]');

        foreach ($elements as $element) {
            array_push($filterIds, $element->tag->getAttribute('data-id')->getValue());
        }

        $popupIds = [];
        $popupElements = $dom->find('*[popup-list]');

        foreach ($popupElements as $popupElement) {
            array_push($popupIds, $popupElement->tag->getAttribute('popup-list')->getValue());
        }

        if (sizeof($popupIds) > 0) {
            $fetchedPopups = Popup::whereIn('id', $popupIds)->get();
            foreach ($fetchedPopups as $ps) {
                if ($ps) {
                    $html = view('admin.pages.pop-up-stub', ['popup' => $ps])->toHtml();
                    $page->html = $html . $page->html;
                }
            }
        }

        $components = Component::where('user_id', $page->user_id)->whereIn('unique_id', $filterIds)->get();

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
                array_push($defaultCssLinks, url('/pages/editor/css/' . $component->id));
            }

            if (strlen($component->custom_js) > 0) {
                array_push($defaultJSLinks, url('/pages/editor/js/' . $component->id));
            }
        }

        return view('admin.pages.view',
            [
                'page' => $page,
                'popup' => $popup,
                'components' => $components,
                'defaultCSSLinks' => $defaultCssLinks,
                'defaultJSLinks' => $defaultJSLinks
            ]);
    }

    public function available($type)
    {
        $allAdmins = User::where('role', 'admin')->where('activated', 1)->get()->pluck('id');
        $pages = Page::whereIn('user_id', $allAdmins)->where('type', $type)->get();
        $html = view('admin.pages.available', ['pages' => $pages])->toHtml();
        return response()->json(['data' => $html]);
    }
}
