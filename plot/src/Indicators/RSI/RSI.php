<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 11.02.19
 * Time: 22:38
 */
namespace EmpiricaPlatform\Plot\Indicators\RSI;

use EmpiricaPlatform\Plot\MovingAverage\AbstractSingleValue;


class RSI extends AbstractSingleValue {


	const FUNCTION_NAME='LupeCode\phpTraderNative\Trader::rsi';

	const SUB_CLASS_NAME='EmpiricaPlatform\Plot\Indicators\RSI\RSIOhlc';


	public function getLabel() {
		return 'RSI'.$this->length;
	}
}