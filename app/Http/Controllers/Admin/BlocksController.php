<?php

namespace App\Http\Controllers\Admin;

use App\Models\Component;
use App\Http\Controllers\Controller;
use App\Models\Utils;
use Exception;
use Illuminate\Support\Facades\Validator;
use splitbrain\PHPArchive\Zip;
use Illuminate\Support\Facades\Storage;

class BlocksController extends Controller
{
    public function create()
    {
        $name = request('name');
        $unique_id = request('unique_id');
        $component_js = request('component_js');
        $settings_js = request('settings_js');
        $custom_css = request('custom_css');
        $custom_js = request('custom_js');
        $default_preview = "/assets/img/default-component.png";

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

        if (request()->hasFile('preview_image')) {
            $rules = array('preview_image' => 'required|image|mimes:jpeg,jpg,png,gif');
            $validator = Validator::make(request()->all(), $rules);

            if ($validator->fails()) {
                session()->flash('error_msg', Utils::messages($validator));
                return redirect()->back()->withInput(request()->all());
            }

            $imageName = strtolower(time() . '.' . request()->preview_image->getClientOriginalExtension());

            request()->preview_image->move(public_path('images'), $imageName);

            $default_preview = '/images/' . $imageName;
        }

        try {
            $component = new Component();
            $component->type = 'block';
            $component->user_id = auth()->user()->id;
            $component->unique_id = $unique_id;
            $component->name = $name;
            $component->js_code = $component_js;
            $component->selected_code = $settings_js;
            $component->custom_css = $custom_css;
            $component->custom_js = $custom_js;
            $component->component_img = $default_preview;
            $component->preview_img = $default_preview;
            $component->status = 1;
            $component->save();

            session()->flash('success_msg', 'Block created successfully');
            return redirect('/admin/blocks/list');
        } catch (Exception $e) {
            session()->flash('error_msg', 'Unable to create block');
            return redirect()->back()->withInput(request()->all());
        }
    }

    public function install()
    {
        $path = request()->file('component')->store('modules');
        $p = Storage::disk('local')->path($path);

        $tar = new Zip();
        $tar->open($p);
        $fileInfo = $tar->extract(public_path('component_temp'));

        $config = [];
        $component_js = '';
        $component_setting = '';
        $component_custom_css = '';
        $component_custom_js = '';
        $preview_image = "/assets/img/default-component.png";

        if (file_exists(public_path('component_temp/' . $fileInfo[0]->getPath() . '/config.php'))) {
            $config = include public_path('component_temp/' . $fileInfo[0]->getPath() . '/config.php');

            if (!isset($config['icon'])) {
                session()->flash('error_msg', 'Icon is missing in config file. Unable to install');
                return redirect()->back()->withInput(request()->all());
            }
        } else {
            session()->flash('error_msg', 'Config file is missing in component. Unable to install');
            return redirect()->back()->withInput(request()->all());
        }

        if (Component::where('unique_id', $config['unique_id'])->count() > 0) {
            session()->flash('error_msg', 'Component with the following unique id is already available');
            return redirect()->back()->withInput(request()->all());
        }

        if (file_exists(public_path('component_temp/' . $fileInfo[0]->getPath() . '/component_js.txt'))) {
            $component_js = file_get_contents(public_path('component_temp/' . $fileInfo[0]->getPath() . '/component_js.txt'));
        }

        if (file_exists(public_path('component_temp/' . $fileInfo[0]->getPath() . '/component_settings.txt'))) {
            $component_setting = file_get_contents(public_path('component_temp/' . $fileInfo[0]->getPath() . '/component_settings.txt'));
        }

        if (file_exists(public_path('component_temp/' . $fileInfo[0]->getPath() . '/component_custom_css.txt'))) {
            $component_custom_css = file_get_contents(public_path('component_temp/' . $fileInfo[0]->getPath() . '/component_css.txt'));
        }

        if (file_exists(public_path('component_temp/' . $fileInfo[0]->getPath() . '/component_custom_js.txt'))) {
            $component_custom_js = file_get_contents(public_path('component_temp/' . $fileInfo[0]->getPath() . '/component_js.txt'));
        }

        if (file_exists(public_path('component_temp/' . $fileInfo[0]->getPath() . '/' . $config['icon']))) {
            $filename = time() . $config['icon'];
            $preview_image = 'component_previews/' . $filename;
            rename(public_path('component_temp/' . $fileInfo[0]->getPath() . '/' . $config['icon']), public_path('component_previews/' . $filename));
        }

        try {
            $component = new Component();
            $component->type = 'block';
            $component->user_id = auth()->user()->id;
            $component->name = $config['name'];
            $component->unique_id = $config['unique_id'];
            $component->js_code = $component_js;
            $component->selected_code = $component_setting;
            $component->custom_css = $component_custom_css;
            $component->custom_js = $component_custom_js;
            $component->component_img = $preview_image;
            $component->preview_img = $preview_image;
            $component->status = 1;
            $component->save();

            session()->flash('success_msg', 'Block installed successfully');
            return redirect('/admin/blocks/list');
        } catch (Exception $e) {
            session()->flash('error_msg', 'Unable to install block, contact developer');
            return redirect()->back()->withInput(request()->all());
        }
    }

    public function listAll()
    {
        $components = Component::where('user_id', auth()->user()->id)->where('type', 'block')->get();
        return view('admin.blocks.list', ['components' => $components]);
    }

    public function edit($id)
    {
        $component = Component::where('id', $id)->where('user_id', auth()->user()->id)->first();

        if (!$component) {
            session()->flash('error_msg', 'Block not found');
            return redirect()->back();
        }

        return view('admin.blocks.edit', compact('component'));
    }

    public function update($id)
    {
        $component = Component::where('id', $id)->first();

        $name = request('name');
        $unique_id = request('unique_id');
        $component_js = request('component_js');
        $settings_js = request('settings_js');
        $custom_css = request('custom_css');
        $custom_js = request('custom_js');
        $default_preview = request('default_preview_image');

        if (!$component) {
            session()->flash('error_msg', 'Component not found');
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

        if (request()->hasFile('preview_image')) {
            $rules = array('preview_image' => 'required|image|mimes:jpeg,jpg,png,gif');
            $validator = Validator::make(request()->all(), $rules);

            if ($validator->fails()) {
                session()->flash('error_msg', Utils::messages($validator));
                return redirect()->back()->withInput(request()->all());
            }

            $imageName = strtolower(time() . '.' . request()->preview_image->getClientOriginalExtension());

            request()->preview_image->move(public_path('images'), $imageName);

            $default_preview = '/images/' . $imageName;
        }

        try {
            $component->name = $name;
            $component->unique_id = $unique_id;
            $component->js_code = $component_js;
            $component->selected_code = $settings_js;
            $component->custom_css = $custom_css;
            $component->custom_js = $custom_js;
            $component->preview_img = $default_preview;
            $component->component_img = $default_preview;
            $component->save();

            session()->flash('success_msg', 'Block updated successfully');
            return redirect('/admin/blocks/list');
        } catch (Exception $e) {
            session()->flash('success_msg', $e->getMessage());
            return redirect()->back()->withInput(request()->all());
        }
    }

    public function enable($id)
    {
        $component = Component::where('id', $id)->where('user_id', auth()->user()->id)->first();

        if (!$component) {
            session()->flash('error_msg', 'Block not found');
            return redirect()->back();
        }

        $component->status = 1;
        $component->save();

        return back();
    }

    public function disable($id)
    {
        $component = Component::where('id', $id)->where('user_id', auth()->user()->id)->first();

        if (!$component) {
            session()->flash('error_msg', 'Block not found');
            return redirect()->back();
        }

        $component->status = 0;
        $component->save();

        return back();
    }

    public function destroy($id)
    {
        $component = Component::where('id', $id)->where('user_id', auth()->user()->id)->first();

        if (!$component) {
            return response()->json(['error' => 'Block not found'], 400);
        }

        $component->delete();

        session()->flash('success_msg', 'Block deleted successfully');

        return redirect()->back();
    }

}
