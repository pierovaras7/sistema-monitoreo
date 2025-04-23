<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class DetalleAula extends Component
{
    public $aulaId;

    public function mount($aulaId)
    {
        $this->aulaId = $aulaId;
    }
    
    public function render()
    {
        return view('livewire.admin.detalle-aula');
    }
}
