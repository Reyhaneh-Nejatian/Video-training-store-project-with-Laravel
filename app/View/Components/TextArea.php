<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TextArea extends Component
{
    public $placeholder;
    public $name;
    public $value;

    /**
     * Create a new component instance.
     *
     * @param $placeholder
     * @param $name
     * @param $value
     */
    public function __construct($placeholder,$name,$value = null)
    {
        $this->placeholder = $placeholder;
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.text-area');
    }
}
