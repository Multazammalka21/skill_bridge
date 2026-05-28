<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PinteriaCard extends Component
{
    public function __construct(
        public string $title = '',
        public string $icon = '',
        public string $variant = 'default',
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.pinteria-card');
    }
}
