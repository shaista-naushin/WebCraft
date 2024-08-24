<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\Page;
use App\Models\Popup;
use App\Models\Settings;
use App\Models\Utils;
use PHPHtmlParser\Dom;

class HomeController extends Controller
{
    public function index()
    {
        $home_page = Utils::setting(Settings::WEB_HOME_PAGE);
        if (!is_null($home_page)) {
            $page = Page::where('id', $home_page)->first();
            if ($page) {
                $popup = null;

                if ($page->popup) {
                    $popup = Popup::where('id', $page->popup)->first();
                }

                if ($popup) {
                    $html = view('admin.pages.pop-up-stub', ['popup' => $popup])->toHtml();
                    $page->html = $html . $page->html;
                }

                $filterIds = [];

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
        }

        return redirect()->to('/dashboard');
    }
}
