<?php

namespace App\Http\Controllers;

use App\Models\FormData;
use App\Models\Page;
use App\Models\Settings;
use App\Models\Utils;
use Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'admin') {
            return $this->adminDashboard();
        }

        if (auth()->user()->role === 'user') {
            return $this->userDashboard();
        }
    }

    public function userDashboard()
    {
        $day_start = Carbon\Carbon::now()->subtract('days', 7)->startOfDay();
        $day_end = Carbon\Carbon::now()->endOfDay();

        $pagesChart = [];
        $formDataChart = [];

        $pageIds = Page::where('user_id', auth()->user()->id)->pluck('id');


        for ($i = 1; $i <= 12; $i++) {
            $month_start = Carbon\Carbon::now()->startOfYear()->month($i)->startOfMonth();
            $month_end = Carbon\Carbon::now()->startOfYear()->month($i)->endOfMonth();

            $monthCount = Page::where('user_id', auth()->user()->id)->where('created_at', '>=', $month_start)->where('created_at', '<=', $month_end)->count();
            $formDataCount = FormData::whereIn('page_id', $pageIds)->where('created_at', '>=', $month_start)->where('created_at', '<=', $month_end)->count();
            array_push($pagesChart, $monthCount);
            array_push($formDataChart, $formDataCount);
        }

        $totalPages = Page::where('user_id', auth()->user()->id)->count();
        $pagesThisWeek = Page::where('user_id', auth()->user()->id)->where('created_at', '>=', $day_start)->where('created_at', '<=', $day_end)->count();
        $totalFormData = FormData::whereIn('page_id', $pageIds)->count();
        $formDataThisWeek = FormData::whereIn('page_id', $pageIds)->where('created_at', '>=', $day_start)->where('created_at', '<=', $day_end)->count();

        $pagesWeekChange = 0;
        $formDataWeekChange = 0;

        if ($totalPages > 0) {
            $pagesWeekChange = ($pagesThisWeek / $totalPages) * 100;
        }

        if ($totalFormData > 0) {
            $formDataWeekChange = ($formDataThisWeek / $totalFormData) * 100;
        }

        return view('user.dashboard',
            [
                'totalPages' => $totalPages,
                'pagesThisWeek' => $pagesThisWeek,
                'totalFormData' => $totalFormData,
                'formDataThisWeek' => $formDataThisWeek,
                'pagesWeekChange' => $pagesWeekChange,
                'formDataWeekChange' => $formDataWeekChange,
                'pagesChart' => $pagesChart,
                'formDataChart' => $formDataChart,
            ]);
    }

    public function adminDashboard()
    {
        $day_start = Carbon\Carbon::now()->subtract('days', 7)->startOfDay();
        $day_end = Carbon\Carbon::now()->endOfDay();

        $pagesChart = [];
        $formDataChart = [];

        $pageIds = Page::where('user_id', auth()->user()->id)->pluck('id');


        for ($i = 1; $i <= 12; $i++) {
            $month_start = Carbon\Carbon::now()->startOfYear()->month($i)->startOfMonth();
            $month_end = Carbon\Carbon::now()->startOfYear()->month($i)->endOfMonth();

            $monthCount = Page::where('user_id', auth()->user()->id)->where('created_at', '>=', $month_start)->where('created_at', '<=', $month_end)->count();
            $formDataCount = FormData::whereIn('page_id', $pageIds)->where('created_at', '>=', $month_start)->where('created_at', '<=', $month_end)->count();
            array_push($pagesChart, $monthCount);
            array_push($formDataChart, $formDataCount);
        }

        $totalPages = Page::where('user_id', auth()->user()->id)->count();
        $pagesThisWeek = Page::where('user_id', auth()->user()->id)->where('created_at', '>=', $day_start)->where('created_at', '<=', $day_end)->count();
        $totalFormData = FormData::whereIn('page_id', $pageIds)->count();
        $formDataThisWeek = FormData::whereIn('page_id', $pageIds)->where('created_at', '>=', $day_start)->where('created_at', '<=', $day_end)->count();

        $pagesWeekChange = 0;
        $formDataWeekChange = 0;

        if ($totalPages > 0) {
            $pagesWeekChange = ($pagesThisWeek / $totalPages) * 100;
        }

        if ($totalFormData > 0) {
            $formDataWeekChange = ($formDataThisWeek / $totalFormData) * 100;
        }

        return view('admin.dashboard',
            [
                'totalPages' => $totalPages,
                'pagesThisWeek' => $pagesThisWeek,
                'totalFormData' => $totalFormData,
                'formDataThisWeek' => $formDataThisWeek,
                'pagesWeekChange' => $pagesWeekChange,
                'formDataWeekChange' => $formDataWeekChange,
                'pagesChart' => $pagesChart,
                'formDataChart' => $formDataChart,
            ]);
    }

    public function terms()
    {
        $terms = Utils::setting(Settings::SITE_TERMS);
        return view('terms', ['terms' => $terms]);
    }
}
