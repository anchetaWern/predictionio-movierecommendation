<?php
namespace App\Classes;

use predictionio\EventClient;
use predictionio\EngineClient;

class Pio {

	public function eventClient(){

		$pio_accesskey = env('PIO_KEY');
		$pio_eventserver = 'http://127.0.0.1:7070';

		return new EventClient($pio_accesskey, $pio_eventserver);

	}

	public function predictionClient(){
		
		$pio_predictionserver = 'http://127.0.0.1:8192';
		return new EngineClient($pio_predictionserver);

	}

}