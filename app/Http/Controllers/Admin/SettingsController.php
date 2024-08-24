<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Models\Utils;

class SettingsController extends Controller
{
    public function getSite()
    {
        $json = [];
        $settings = Settings::where('type', 'site')->get();

        foreach ($settings as $setting) {
            if ($setting->key == Settings::SITE_GOOGLE_ANALYTICS
                || $setting->key == Settings::SITE_DESCRIPTION
                || $setting->key == Settings::SITE_TERMS
                || $setting->key == Settings::SITE_GOOGLE_DOMAIN_VERIFY
                || $setting->key == Settings::SITE_BING_DOMAIN_VERIFY
                || $setting->key == Settings::SITE_KEYWORDS) {
                $json[$setting->key] = $setting->text_value;
            } else {
                $json[$setting->key] = $setting->value;
            }
        }

        return view('admin.settings.site', ['settings' => $json]);
    }

    public function updateSite()
    {
        Settings::updateOrCreate(
            ['key' => Settings::SITE_URL],
            ['value' => request('url'), 'type' => 'site']
        );

        Settings::updateOrCreate(
            ['key' => Settings::SITE_TITLE],
            ['value' => request('title'), 'type' => 'site']
        );

        Settings::updateOrCreate(
            ['key' => Settings::SITE_DESCRIPTION],
            ['text_value' => request('description'), 'type' => 'site']
        );

        Settings::updateOrCreate(
            ['key' => Settings::SITE_TERMS],
            ['text_value' => request('terms'), 'type' => 'site']
        );

        Settings::updateOrCreate(
            ['key' => Settings::SITE_KEYWORDS],
            ['text_value' => request('keywords'), 'type' => 'site']
        );

        Settings::updateOrCreate(
            ['key' => Settings::SITE_GOOGLE_DOMAIN_VERIFY],
            ['text_value' => request('googleDomainVerify'), 'type' => 'site']
        );

        Settings::updateOrCreate(
            ['key' => Settings::SITE_BING_DOMAIN_VERIFY],
            ['text_value' => request('bingDomainVerify'), 'type' => 'site']
        );

        Settings::updateOrCreate(
            ['key' => Settings::SITE_GOOGLE_ANALYTICS],
            ['text_value' => request('googleAnalytics'), 'type' => 'site']
        );

        Settings::updateOrCreate(
            ['key' => Settings::SITE_LOCALE],
            ['value' => request('locale'), 'type' => 'site']
        );

        Settings::updateOrCreate(
            ['key' => Settings::BITLY_ACCESS_TOKEN],
            ['value' => request('bitlyAccessToken'), 'type' => 'site']
        );

        Settings::updateOrCreate(
            ['key' => Settings::GET_RESPONSE_CLIENT_ID],
            ['value' => request('getResponseClientId'), 'type' => 'site']
        );

        Settings::updateOrCreate(
            ['key' => Settings::GET_RESPONSE_CLIENT_SECRET],
            ['value' => request('getResponseClientSecret'), 'type' => 'site']
        );

        Settings::updateOrCreate(
            ['key' => Settings::AWEBER_CLIENT_ID],
            ['value' => request('aweberClientId'), 'type' => 'site']
        );

        Settings::updateOrCreate(
            ['key' => Settings::AWEBER_CLIENT_SECRET],
            ['value' => request('aweberClientSecret'), 'type' => 'site']
        );

        $logo_url = request('logo');
        $favicon_url = request('favicon');

        if (request()->hasFile('logoFile')) {
            $rules = array('logoFile' => 'required|image|mimes:jpeg,jpg,png,gif,svg');
            $validator = \Illuminate\Support\Facades\Validator::make(request()->all(), $rules);

            if ($validator->fails()) {
                session()->flash('error_msg', Utils::messages($validator));
                return redirect()->back()->withInput(request()->all());
            }

            $imageName = time() . '.' . request()->logoFile->getClientOriginalExtension();

            request()->logoFile->move(public_path('images'), $imageName);

            $logo_url = '/images/' . $imageName;
        }

        if (request()->hasFile('faviconFile')) {
            $rules = array('faviconFile' => 'required|image|mimes:jpeg,jpg,png,gif,svg');
            $validator = \Illuminate\Support\Facades\Validator::make(request()->all(), $rules);

            if ($validator->fails()) {
                session()->flash('error_msg', Utils::messages($validator));
                return redirect()->back()->withInput(request()->all());
            }

            $imageName = time() . '.' . request()->faviconFile->getClientOriginalExtension();

            request()->faviconFile->move(public_path('images'), $imageName);

            $favicon_url = '/images/' . $imageName;
        }

        Settings::updateOrCreate(
            ['key' => Settings::SITE_LOGO],
            ['value' => $logo_url, 'type' => 'site']
        );

        Settings::updateOrCreate(
            ['key' => Settings::SITE_FAVICON],
            ['value' => $favicon_url, 'type' => 'site']
        );

        Settings::updateOrCreate(
            ['key' => Settings::SITE_LOCALE],
            ['value' => request('locale'), 'type' => 'site']
        );

        Settings::updateOrCreate(
            ['key' => Settings::DEVELOPER_MODE],
            ['value' => request()->has('developer_mode') ? 1:0, 'type' => 'site']
        );

        $json = [];
        $settings = Settings::where('type', 'site')->get();

        foreach ($settings as $setting) {
            if ($setting->key == Settings::SITE_GOOGLE_ANALYTICS
                || $setting->key == Settings::SITE_DESCRIPTION
                || $setting->key == Settings::SITE_GOOGLE_DOMAIN_VERIFY
                || $setting->key == Settings::SITE_BING_DOMAIN_VERIFY
                || $setting->key == Settings::SITE_KEYWORDS) {
                $json[$setting->key] = $setting->text_value;
            } else {
                $json[$setting->key] = $setting->value;
            }
        }

        session()->flash('success_msg', 'Settings updated successfully');
        return redirect()->back();
    }

    public function getMailing()
    {
        $json = [];
        $settings = Settings::where('type', 'mailing')->get();

        foreach ($settings as $setting) {
            $json[$setting->key] = $setting->value;
        }

        //dd($json);

        return view('admin.settings.mailing', ['settings' => $json]);
    }

    public function updateMailing()
    {
        Settings::updateOrCreate(
            ['key' => Settings::MAILING_SERVER],
            ['value' => request('mailingServer'), 'type' => 'mailing']
        );

        Settings::updateOrCreate(
            ['key' => Settings::MAILING_DOMAIN],
            ['value' => request('mailingDomain'), 'type' => 'mailing']
        );

        Settings::updateOrCreate(
            ['key' => Settings::MAILING_SMTP_USERNAME],
            ['value' => request('smtpUsername'), 'type' => 'mailing']
        );

        Settings::updateOrCreate(
            ['key' => Settings::MAILING_SMTP_PASSWORD],
            ['value' => request('smtpPassword'), 'type' => 'mailing']
        );

        Settings::updateOrCreate(
            ['key' => Settings::MAILING_FROM_NAME],
            ['value' => request('mailingFromName'), 'type' => 'mailing']
        );

        Settings::updateOrCreate(
            ['key' => Settings::MAILING_FROM_EMAIL],
            ['value' => request('mailingFromEmail'), 'type' => 'mailing']
        );

        Settings::updateOrCreate(
            ['key' => Settings::MAILING_REPLY_TO_EMAIL],
            ['value' => request('mailingReplyTo'), 'type' => 'mailing']
        );

        Settings::updateOrCreate(
            ['key' => Settings::MAILGUN_SECRET],
            ['value' => request('mailgunSecret'), 'type' => 'mailing']
        );

        session()->flash('success_msg', 'Settings updated successfully');
        return redirect()->back();
    }
}

