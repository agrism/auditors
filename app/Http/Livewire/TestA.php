<?php

namespace App\Http\Livewire;

use Livewire\Component;

class TestA extends Component
{

    public $number = 0;

    public $cId;
    public $color;

    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    public function render()
    {
        return view('livewire.test-a');
    }

    public function mount(){
        $this->cId = uniqid();
    }

}
