<?php
namespace App\Services;

use App\Models\Menu;

class MenuBuilder {
    public static function build($user) {
        $menus = Menu::with('children')->roots()->get();

        return $menus->map(function ($item) use ($user) {
            return self::filterItem($item, $user);
        })->filter()->values()->toArray();
    }

    protected static function filterItem($item, $user)
    {
        $hasRole = !$item->role || $user?->hasRole($item->role);
        $hasPermission = !$item->permission || $user?->can($item->permission);

        if (!($hasRole || $hasPermission)) {
            return null;
        }

        return [
            'title' => $item->title,
            'href' => $item->href,
            'icon' => $item->icon,
            'role' => $item->role,
            'permission' => $item->permission,
            'children' => $item->children->map(fn ($child) => self::filterItem($child, $user))->filter()->values(),
        ];
    }
}
