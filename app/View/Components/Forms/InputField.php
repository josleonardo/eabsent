<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InputField extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $label,
        public string $name,
        public string $id,
        public string $type = 'text',
        public string $placeholder = '',
        public bool $isRequired = false,
        public bool $isDisabled = false,
        public $value = null,
    ) {
        $this->value = $type == 'password' ? null : ($value !== null ? old($name, $value) : old($name));
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.input-field');
    }
}
