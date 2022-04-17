<?php

namespace App\Http\Livewire;

use Livewire\Component;

class TestRoot extends Component
{

    public $activeComponent = null;

    public $listeners = [
        'addNumber' => 'numberAdded',
    ];

    public $total = 0;

    public function render()
    {
        return view('livewire.test-root')->layout('layouts.app-test');;
    }

    public function setActiveA()
    {
        $this->activeComponent = 'A';
    }

    public function setActiveB()
    {
        $this->activeComponent = 'B';
    }

    public function numberAdded($number)
    {
        $this->total += $number;
    }
}
