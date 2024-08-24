<?php

namespace App\Helpers;

use Nwidart\Modules\Facades\Module;

class Menu
{
    public static function getModulesMenu()
    {
        $items = [];
        $installedModules = Module::all();
        foreach ($installedModules as $module) {
            if ($module->isEnabled()) {
                $name = strtolower($module->getName());
                $menu = config("{$name}.admin.menu");
                if(is_array($menu)){
                    $items = array_merge($items, $menu);
                }
            }
        }

        return $items;
    }

    public static function getModulesUserMenu()
    {
        $items = [];
        $installedModules = Module::all();
        foreach ($installedModules as $module) {
            if ($module->isEnabled()) {
                $name = strtolower($module->getName());
                $menu = config("{$name}.user.menu");
                if(is_array($menu)){
                    $items = array_merge($items, $menu);
                }
            }
        }

        return $items;
    }
}
