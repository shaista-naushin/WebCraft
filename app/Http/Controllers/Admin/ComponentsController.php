<?php

namespace App\Http\Controllers\Admin;

use App\Models\Component;
use App\Http\Controllers\Controller;
use App\Models\Utils;
use Exception;
use Illuminate\Support\Facades\Validator;
use splitbrain\PHPArchive\Zip;
use Illuminate\Support\Facades\Storage;

class ComponentsController extends Controller
{

    public function create()
    {
        $name = request('name');
        $category = request('category');
        $custom_css = request('custom_css');
        $custom_js = request('custom_js');
        $default_component_preview = '';
        $default_preview = '';

        $v = Validator::make(
            [
                'name' => $name,
                'category' => $category,
                'component_image' => request('icon')
            ],
            [
                'name' => 'required',
                'category' => 'required',
                'component_image' => 'required|image|mimes:jpeg,jpg,png,gif,svg'
            ]
        );

        if ($v->fails()) {
            session()->flash('error_msg', Utils::messages($v));
            return redirect()->back()->withInput(request()->all());
        }

        if (request()->hasFile('icon')) {
            $imageName = strtolower(time() . '.' . request()->icon->getClientOriginalExtension());
            request()->icon->move(public_path('images'), $imageName);
            $default_component_preview = '/images/' . $imageName;
        }

        if (request()->hasFile('preview_image')) {
            $rules = array('preview_image' => 'required|image|mimes:jpeg,jpg,png,gif');
            $validator = Validator::make(request()->all(), $rules);

            if ($validator->fails()) {
                session()->flash('error_msg', Utils::messages($validator));
                return redirect()->back()->withInput(request()->all());
            }

            $imageName = time() . '.' . strtolower(request()->preview_image->getClientOriginalExtension());

            request()->preview_image->move(public_path('images'), $imageName);

            $default_preview = '/images/' . $imageName;
        }

        try {

            $unique_id = time();
            $component_js = view('admin.components.stub', [
                'unique_id' => $unique_id,
                'component_img' => $default_component_preview,
                'preview_img' => strlen($default_preview) > 0 ? $default_preview : $default_component_preview,
                'category' => $category,
                'name' => $name,
                'html' => request('html'),
            ]);

            $component = new Component();
            $component->type = 'component';
            $component->category = request('category');
            $component->user_id = auth()->user()->id;
            $component->unique_id = $unique_id;
            $component->name = $name;
            $component->js_code = $component_js;
            $component->html = request('html');
            $component->custom_css = $custom_css;
            $component->custom_js = $custom_js;
            $component->component_img = $default_component_preview;
            $component->preview_img = $default_preview;
            $component->status = 1;
            $component->save();

            session()->flash('success_msg', 'Component created successfully');
            return redirect('/admin/components/list');
        } catch (Exception $e) {
            session()->flash('error_msg', 'Unable to create component');
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
        $unique_id = time();
        $html = '';
        $custom_css = '';
        $custom_js = '';
        $icon = '';
        $preview_img = '';

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

        if (file_exists(public_path('component_temp/' . $fileInfo[0]->getPath() . '/html.txt'))) {
            $html = file_get_contents(public_path('component_temp/' . $fileInfo[0]->getPath() . '/html.txt'));
        }

        if (file_exists(public_path('component_temp/' . $fileInfo[0]->getPath() . '/css.txt'))) {
            $custom_css = file_get_contents(public_path('component_temp/' . $fileInfo[0]->getPath() . '/css.txt'));
        }

        if (file_exists(public_path('component_temp/' . $fileInfo[0]->getPath() . '/js.txt'))) {
            $custom_js = file_get_contents(public_path('component_temp/' . $fileInfo[0]->getPath() . '/js.txt'));
        }

        if (file_exists(public_path('component_temp/' . $fileInfo[0]->getPath() . '/' . $config['icon']))) {
            $filename = strtolower(time() . $config['icon']);
            $icon = '/component_previews/' . $filename;
            rename(public_path('component_temp/' . $fileInfo[0]->getPath() . '/' . $config['icon']), public_path('component_previews/' . $filename));
        }

        if (file_exists(public_path('component_temp/' . $fileInfo[0]->getPath() . '/' . $config['preview']))) {
            $filename = strtolower(time() . $config['preview']);
            $preview_img = '/component_previews/' . $filename;
            rename(public_path('component_temp/' . $fileInfo[0]->getPath() . '/' . $config['preview']), public_path('component_previews/' . $filename));
        }

        $component_js = view('admin.components.stub', [
            'unique_id' => $unique_id,
            'component_img' => $icon,
            'preview_img' => strlen($preview_img) > 0 ? $preview_img : $icon,
            'category' => $config['category'],
            'name' => $config['name'],
            'html' => $html,
        ]);

        try {
            $component = new Component();
            $component->type = 'component';
            $component->user_id = auth()->user()->id;
            $component->name = $config['name'];
            $component->category = $config['category'];
            $component->unique_id = $unique_id;
            $component->js_code = $component_js;
            $component->html = $html;
            $component->custom_css = $custom_css;
            $component->custom_js = $custom_js;
            $component->component_img = $icon;
            $component->preview_img = strlen($preview_img) > 0 ? $preview_img : $icon;
            $component->status = 1;
            $component->save();

            session()->flash('success_msg', 'Component installed successfully');
            return redirect('/admin/components/list');
        } catch (Exception $e) {
            session()->flash('error_msg', 'Unable to install component, contact developer');
            return redirect()->back()->withInput(request()->all());
        }
    }

    public function listAll()
    {
        $components = Component::where('user_id', auth()->user()->id)->where('type', 'component')->get();
        return view('admin.components.list', ['components' => $components]);
    }

    public function edit($id)
    {
        $component = Component::where('id', $id)->where('user_id', auth()->user()->id)->first();

        if (!$component) {
            session()->flash('error_msg', 'Component not found');
            return redirect()->back();
        }

        return view('admin.components.edit', compact('component'));
    }

    public function update($id)
    {
        $component = Component::where('id', $id)->first();

        $name = request('name');
        $category = request('category');
        $custom_css = request('custom_css');
        $custom_js = request('custom_js');
        $html = request('html');
        $default_component_preview = request('default_component_image');
        $default_preview = request('default_preview_image');

        if (!$component) {
            session()->flash('error_msg', 'Component not found');
            return redirect()->back();
        }

        $v = Validator::make(
            [
                'name' => $name,
                'category' => $category
            ],
            [
                'name' => 'required',
                'category' => 'required'
            ]
        );

        if ($v->fails()) {
            session()->flash('error_msg', Utils::messages($v));
            return redirect()->back()->withInput(request()->all());
        }

        if (request()->hasFile('icon')) {
            $imageName = strtolower(time() . '.' . request()->icon->getClientOriginalExtension());
            request()->icon->move(public_path('images'), $imageName);
            $default_component_preview = '/images/' . $imageName;
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

        $component_js = view('admin.components.stub', [
            'unique_id' => $component->unique_id,
            'component_img' => $default_component_preview,
            'preview_img' => strlen($default_preview) > 0 ? $default_preview : $default_component_preview,
            'category' => $category,
            'name' => $name,
            'html' => $html,
        ]);

        try {
            $component->category = request('category');
            $component->name = $name;
            $component->js_code = $component_js;
            $component->html = $html;
            $component->custom_css = $custom_css;
            $component->custom_js = $custom_js;
            $component->component_img = $default_component_preview;
            $component->preview_img = $default_preview;
            $component->save();

            session()->flash('success_msg', 'Component updated successfully');
            return redirect('/admin/components/list');
        } catch (Exception $e) {
            session()->flash('success_msg', $e->getMessage());
            return redirect()->back()->withInput(request()->all());
        }
    }

    public function enable($id)
    {
        $component = Component::where('id', $id)->where('user_id', auth()->user()->id)->first();

        if (!$component) {
            session()->flash('error_msg', 'Component not found');
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
            session()->flash('error_msg', 'Component not found');
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
            return response()->json(['error' => 'Component not found'], 400);
        }

        $component->delete();

        session()->flash('success_msg', 'Component deleted successfully');

        return redirect()->back();
    }

}
