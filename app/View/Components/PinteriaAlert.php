<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PinteriaAlert extends Component
{
    public function __construct(
        public string $type = 'info',
        public bool $dismissible = false,
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.pinteria-alert');
    }
}
