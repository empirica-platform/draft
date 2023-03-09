<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 21.10.18
 * Time: 20:41
 */

namespace EmpiricaPlatform\Plot\MovingAverage;






class Sma extends AbstractSingleValue {

	const FUNCTION_NAME='\LupeCode\phpTraderNative\Trader::sma';
	const SUB_CLASS_NAME='EmpiricaPlatform\Plot\MovingAverage\SmaOhlc';


	public function getLabel() {
		return 'SMA'.$this->length;
	}
}