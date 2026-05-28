<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PinteriaButton extends Component
{
    public function __construct(
        public string $variant = 'primary',
        public string $type = 'button',
        public string $href = '',
        public bool $disabled = false,
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.pinteria-button');
    }
}
