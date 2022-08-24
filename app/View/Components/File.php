<?php

namespace App\View\Components;

use Illuminate\View\Component;

class File extends Component
{
    public $placeholder;
    public $name;
    public $value;

    public function __construct($placeholder,$name,$value =null)
    {
        $this->placeholder = $placeholder;
        $this->name = $name;
        $this->value = $value;
    }

    public function render()
    {
        return view('components.file');
    }
}
