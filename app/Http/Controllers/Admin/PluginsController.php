<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plugin;
use App\Models\Utils;
use Exception;
use Illuminate\Support\Facades\Validator;

class PluginsController extends Controller
{
    public function install()
    {
        $name = request('name');
        $unique_id = request('unique_id');
        $custom_js = request('custom_js');
        $custom_css = request('custom_css');
        $plugin_options = request('plugin_options');

        $v = Validator::make(
            [
                'name' => $name,
                'unique_id' => $unique_id
            ],
            [
                'name' => 'required',
                'unique_id' => 'required'
            ]
        );

        if ($v->fails()) {
            session()->flash('error_msg', Utils::messages($v));
            return redirect()->back()->withInput(request()->all());
        }

        try {
            $plugin = new Plugin();
            $plugin->user_id = auth()->user()->id;
            $plugin->name = $name;
            $plugin->unique_id = $unique_id;
            $plugin->custom_js = $custom_js;
            $plugin->custom_css = $custom_css;
            $plugin->plugin_options = $plugin_options;
            $plugin->status = 1;
            $plugin->save();

            session()->flash('success_msg', 'Plugin created successfully');
            return redirect('/admin/plugins/list');
        } catch (Exception $e) {
            session()->flash('error_msg', 'Unable to create plugin');
            return redirect()->back()->withInput(request()->all());
        }
    }

    public function listAll()
    {
        $plugins = Plugin::where('user_id', auth()->user()->id)->get();
        return view('admin.plugins.list', ['plugins' => $plugins]);
    }

    public function edit($id)
    {
        $plugin = Plugin::where('id', $id)->where('user_id', auth()->user()->id)->first();

        if (!$plugin) {
            session()->flash('error_msg', 'Plugin not found');
            return redirect()->back();
        }

        return view('admin.plugins.edit', compact('plugin'));
    }

    public function update($id)
    {
        $plugin = Plugin::where('id', $id)->first();

        $name = request('name');
        $unique_id = request('unique_id');
        $custom_js = request('custom_js');
        $custom_css = request('custom_css');
        $plugin_options = request('plugin_options');

        if (!$plugin) {
            session()->flash('error_msg', 'Plugin not found');
            return redirect()->back();
        }

        $v = Validator::make(
            [
                'name' => $name,
                'unique_id' => $unique_id
            ],
            [
                'name' => 'required',
                'unique_id' => 'required'
            ]
        );

        if ($v->fails()) {
            session()->flash('error_msg', Utils::messages($v));
            return redirect()->back()->withInput(request()->all());
        }

        try {
            $plugin->name = $name;
            $plugin->unique_id = $unique_id;
            $plugin->custom_js = $custom_js;
            $plugin->custom_css = $custom_css;
            $plugin->plugin_options = $plugin_options;
            $plugin->save();

            session()->flash('success_msg', 'Plugin updated successfully');
            return redirect('/admin/plugins/list');
        } catch (Exception $e) {
            session()->flash('success_msg', $e->getMessage());
            return redirect()->back()->withInput(request()->all());
        }
    }

    public function enable($id)
    {
        $plugin = Plugin::where('id', $id)->where('user_id', auth()->user()->id)->first();

        if (!$plugin) {
            session()->flash('error_msg', 'Plugin not found');
            return redirect()->back();
        }

        $plugin->status = 1;
        $plugin->save();

        return back();
    }

    public function disable($id)
    {
        $plugin = Plugin::where('id', $id)->where('user_id', auth()->user()->id)->first();

        if (!$plugin) {
            session()->flash('error_msg', 'Plugin not found');
            return redirect()->back();
        }

        $plugin->status = 0;
        $plugin->save();

        return back();
    }

    public function destroy($id)
    {
        $plugin = Plugin::where('id', $id)->where('user_id', auth()->user()->id)->first();

        if (!$plugin) {
            return response()->json(['error' => 'Plugin not found'], 400);
        }

        $plugin->delete();

        session()->flash('success_msg', 'Plugin deleted successfully');

        return redirect()->back();
    }

}
