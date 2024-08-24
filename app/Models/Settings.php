<?php

namespace App\Models;

use dotzero\Googl;
use dotzero\GooglException;
use GabrielKaputa\Bitly\Bitly;
use Illuminate\Database\Eloquent\Model;


class Settings extends Model
{
    const GET_RESPONSE_CLIENT_ID = "get_response_client_id";
    const GET_RESPONSE_CLIENT_SECRET = "get_response_client_secret";

    const AWEBER_CLIENT_ID = "aweber_client_id";
    const AWEBER_CLIENT_SECRET = "aweber_client_secret";

    const MAILING_SERVER = "mailing_server";
    const MAILING_DOMAIN = "mailing_domain";

    const MAILING_FROM_EMAIL = "mailing_from";
    const MAILING_FROM_NAME = "mailing_name";
    const MAILING_REPLY_TO_EMAIL = "mailing_reply_to";

    const MAILING_SMTP_USERNAME = "mailing_smtp_username";
    const MAILING_SMTP_PASSWORD = "mailing_smtp_password";

    const MAILGUN_SECRET = "mailgun_secret";

    const BITLY_ACCESS_TOKEN = "bitly_access_token";

    const SITE_URL = "site_url";
    const SITE_TITLE = "site_title";
    const SITE_DESCRIPTION = "site_description";
    const SITE_TERMS = "site_terms";
    const SITE_KEYWORDS = "site_keywords";
    const SITE_GOOGLE_DOMAIN_VERIFY = "site_google_domain_verify";
    const SITE_BING_DOMAIN_VERIFY = "site_bing_domain_verify";
    const SITE_GOOGLE_ANALYTICS = "site_google_analytics";
    const SITE_LOGO = "site_logo";
    const SITE_FAVICON = "site_favicon";
    const SITE_LOCALE = "site_locale";
    const DEVELOPER_MODE = "developer_mode";

    const WEB_HOME_PAGE = "web_home_page";
    const WEB_USERS_CAN_REGISTER = "web_users_can_register";

    public $table = "settings";

    protected $fillable = ['key', 'value', 'type', 'text_value'];

//    public static function short($url, $shortener)
//    {
//        try {
//            if ($shortener == self::URL_SHORTENER_BITLY) {
//                $bitly = Bitly::withGenericAccessToken(setting(self::BITLY_ACCESS_TOKEN));
//                return $bitly->shortenUrl($url);
//            } else {
//                $googl = new Googl(setting(self::GOOGLE_SHORTENER_API_KEY));
//                return $googl->shorten($url);
//            }
//        } catch (\Exception $e) {
//            return $url;
//        }
//    }
}
