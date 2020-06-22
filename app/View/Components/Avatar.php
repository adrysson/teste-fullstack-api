<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\View\Component;

class Avatar extends Component
{
    public $user;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Authenticatable $user)
    {
        $this->user = $user;
    }


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.avatar');
    }
}
