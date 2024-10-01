<?php

namespace App\View\Components;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class Nav extends Component
{
    public $items;

    public $active;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($context = 'side')
    {
        $nav_items = include base_path('data/nav.php');

        $this->items = $this->prepareItems($nav_items);

        $this->active = Route::currentRouteName();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.nav');
    }

    protected function prepareItems($items)
    {
        $user = Auth::user();
        foreach ($items as $key => $item) {

            if (
                isset($item['ability'][0], $item['ability'][1])
                && !$user->can($item['ability'][0], $item['ability'][1])
            ) {
                unset($items[$key]);
            }
        }
        return $items;
    }
}
