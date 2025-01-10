<?php

namespace App\Livewire;

use Livewire\Component;
use App\Helpers\GoogleMapsHelper;

class CalculateDistance extends Component
{
    public $origin;
    public $destination;
    public $distance;
    public $duration;

    public function calculate()
    {
       
        $result = GoogleMapsHelper::calculateDistance($this->origin, $this->destination);
        
        $this->distance = $result['distance'];
        $this->duration = $result['duration'];
    }
    public function render()
    {
        return view('livewire.calculate-distance');
    }
}
