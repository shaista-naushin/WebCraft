<?php

namespace Modules\Website\Http\Controllers;

use App\Models\Page;
use App\Models\Settings;
use App\Models\Utils;
use Illuminate\Routing\Controller;

class WebsiteController extends Controller
{
    public function settings()
    {
        $home_page = Utils::setting(Settings::WEB_HOME_PAGE);
        $can_register = Utils::setting(Settings::WEB_USERS_CAN_REGISTER);
        $pages = Page::where('user_id', auth()->user()->id)->get();
        return view("website::index",
            [
                'pages' => $pages,
                'home_page' => $home_page,
                'can_register' => $can_register ? true : false
            ]);
    }

    public function saveSettings()
    {
        Settings::updateOrCreate(
            ['key' => Settings::WEB_HOME_PAGE],
            ['value' => request('home_page'), 'type' => 'module:website']
        );

        Settings::updateOrCreate(
            ['key' => Settings::WEB_USERS_CAN_REGISTER],
            ['value' => request('can_register'), 'type' => 'module:website']
        );

        session()->flash('success_msg', 'Settings saved successfully');
        return redirect()->back();
    }
}
