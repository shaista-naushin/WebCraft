<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Nwidart\Modules\Facades\Module;
use splitbrain\PHPArchive\Zip;
use Illuminate\Support\Facades\Storage;

class ModulesController extends Controller
{
    public function installModule()
    {
        $path = request()->file('module')->store('modules');
        $p = Storage::disk('local')->path($path);

        $tar = new Zip();
        $tar->open($p);
        $tar->extract(base_path('Modules'));
        session()->flash('success_msg', 'Module installed successfully');
        return redirect()->back();
    }

    public function changeStatus($alias)
    {
        /**
         * @var $module Module
         */
        $module = Module::findByAlias($alias);

        if ($module) {
            if ($module->json()->system_module !== 1) {
                $msg = "";
                if ($module->isEnabled()) {
                    $module->disable();
                    $msg = $module->getName() . ' module has been disabled';
                } else {
                    $module->enable();
                    $msg = $module->getName() . ' module has been enabled';
                }

                session()->flash('success_msg', $msg);
                return redirect()->back();
            }

            session()->flash('error_msg', 'System module cannot be deactivated');
            return redirect()->back();
        } else {
            session()->flash('error_msg', 'Module not found');
            return redirect()->back();
        }
    }

    public function listModules()
    {
        $modules = [];
        $installedModules = Module::all();
        foreach ($installedModules as $module) {
            $modules[] = [
                'name' => $module->getName(),
                'description' => $module->getDescription(),
                'alias' => $module->getStudlyName(),
                'system_module' => $module->json()->system_module,
                'status' => $module->isEnabled(),
                'path' => $module->getPath(),
                'menu' => config("{$module->getStudlyName()}.admin.module_menu")
            ];
        }

        return view('admin.modules.list', compact('modules'));
    }

    function deleteModule($alias)
    {
        $module = Module::findByAlias($alias);

        if ($module) {
            $module->delete();
            session()->flash('success_msg', $module->getName() . ' module has been deleted');
            return redirect()->back();
        } else {
            session()->flash('error_msg', 'Module not found');
            return redirect()->back();
        }
    }
}
