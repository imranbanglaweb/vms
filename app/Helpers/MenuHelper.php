<?php

use Illuminate\Support\Facades\Route;

if (! function_exists('isActiveRoute')) {
    function isActiveRoute($routeName)
    {
        return request()->routeIs($routeName) ? 'active' : '';
    }
}

if (! function_exists('isActiveUrl')) {
    function isActiveUrl($routeName)
    {
        return request()->routeIs($routeName) ? 'sub-nav-active' : '';
    }
}

if (! function_exists('isMenuOpen')) {
    function isMenuOpen($children)
    {
        foreach ($children as $child) {
            if (request()->routeIs($child->menu_url)) {
                return 'nav-expanded nav-active';
            }

            $subChildren = DB::table('menus')->where('menu_parent', $child->id)->get();

            foreach ($subChildren as $sub) {
                if (request()->routeIs($sub->menu_url)) {
                    return 'nav-expanded nav-active';
                }
            }
        }

        return '';
    }
}
