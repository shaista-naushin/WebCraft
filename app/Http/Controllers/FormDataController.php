<?php

namespace App\Http\Controllers;

use App\Models\FormData;
use App\Models\Page;

class FormDataController extends Controller
{
    public function getAll()
    {
        $pageIds = Page::where('user_id', auth()->user()->id)->pluck('id');
        $formData = [];

        if (count($pageIds) > 0) {
            $formData = FormData::whereIn('page_id', $pageIds)->with('page')->orderBy('created_at', 'desc')->get();
        }

        return view('admin.form-data.list', ['formData' => $formData]);
    }

    public function destroy($id)
    {
        $pageIds = Page::where('user_id', auth()->user()->id)->pluck('id');
        $data = FormData::whereIn('page_id', $pageIds)->where('id', $id)->first();

        if (!$data) {
            return response()->json(['error' => 'Data not found'], 400);
        }

        $data->delete();

        session()->flash('success_msg', 'Data deleted successfully');

        return redirect()->back();
    }
}
