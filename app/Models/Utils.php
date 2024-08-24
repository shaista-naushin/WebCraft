<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Schema;

class Utils
{
    static function messages($v)
    {
        return implode('', $v->messages()->all('<li style="margin-left:10px;">:message</li>'));
    }

    static function generateRandomFromTableColumn($table, $col)
    {

        $rand = Utils::random_string(32);

        if (sizeof(DB::table($table)->where($col, $rand)->get()) > 0) {
            return Utils::generateRandomFromTableColumn($table, $col);
        } else {
            return $rand;
        }
    }

    static function random_string($length)
    {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
    }

    static function settingCan($key)
    {
        $value = Utils::setting($key);
        if ($value && strlen($value) > 0) {
            return intval($value);
        }

        return false;
    }

    static function setting($key)
    {
        if (Schema::hasTable('settings')) {
            $setting = Settings::where('key', $key)->first();

            if (!$setting) {
                return "";
            }

            if ($setting->key == Settings::SITE_GOOGLE_ANALYTICS
                || $setting->key == Settings::SITE_DESCRIPTION
                || $setting->key == Settings::SITE_TERMS
                || $setting->key == Settings::SITE_GOOGLE_DOMAIN_VERIFY
                || $setting->key == Settings::SITE_BING_DOMAIN_VERIFY
                || $setting->key == Settings::SITE_KEYWORDS) {
                return (!empty($setting) && !is_null($setting->text_value)) ? $setting->text_value : "";
            } else {
                return (!empty($setting) && !is_null($setting->value)) ? $setting->value : "";
            }
        } else {
            return "";
        }
    }
}
