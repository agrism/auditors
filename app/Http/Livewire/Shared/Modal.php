<?php

namespace App\Http\Livewire\Shared;

use Livewire\Component;

class Modal extends Component
{
    public $modalId;
    public $title = 'modal';
    public $slot;
    public $action;


    public function render()
    {
        return view('livewire.shared.modal');
    }

    public function submit(){
        $this->emit('parentAction', '123');
    }

    public function mount(){
        //
    }

}
