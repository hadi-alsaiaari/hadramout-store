<?php

return [
    [
        'icon' => 'nav-icon fas fa-tachometer-alt',
        'route' => 'dashboard.dashboard',
        'title' => 'Dashboard',
        'active' => 'dashboard.dashboard',
    ],
    [
        'icon' => 'fas fa-tags nav-icon',
        'route' => 'dashboard.categories.index',
        'title' => 'Categories',
        'badge' => 'New',
        'active' => 'dashboard.categories.*',
        'ability' => ['view', 'App\Models\Category'],
    ],
    [
        'icon' => 'fas fa-box nav-icon',
        'route' => 'dashboard.products.index',
        'title' => 'Products',
        'active' => 'dashboard.products.*',
        'ability' => ['view', 'App\Models\Product'],
    ],
    [
        'icon' => 'fas fa-receipt nav-icon',
        'route' => 'dashboard.categories.index',
        'title' => 'Orders',
        'active' => 'dashboard.orders.*',
        'ability' => ['view', 'App\Models\Order'],
    ],
    [
        'icon' => 'fas fa-shield nav-icon',
        'route' => 'dashboard.roles.index',
        'title' => 'Roles',
        'active' => 'dashboard.roles.*',
        'ability' => ['view', 'App\Models\Role'],
    ],
    [
        'icon' => 'fas fa-users nav-icon',
        'route' => 'dashboard.users.index',
        'title' => 'Users',
        'active' => 'dashboard.users.*',
        'ability' => ['view', 'App\Models\User'],
    ],
    [
        'icon' => 'fas fa-users nav-icon',
        'route' => 'dashboard.admins.index',
        'title' => 'Admins',
        'active' => 'dashboard.admins.*',
        'ability' => ['view', 'App\Models\Admin'],
    ],
];
