<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 07.03.19
 * Time: 23:13
 */
namespace EmpiricaPlatform\Plot\HistoryData\Volume;


use EmpiricaPlatform\Plot\HistoryData\Ohlc;

class VolumeAvgOhlc {

	private $ohlc;

	private $avgValue;

	/**
	 * @var VolumeAvg
	 */
	private $parent;

	const UNCOUNTABLE=true;

	private function __construct(Ohlc $ohlc,$avgValue,VolumeAvg $volumeAvg) {
		$this->ohlc=$ohlc;
		$this->avgValue=$avgValue;
		$this->parent=$volumeAvg;
	}


	public static function create( Ohlc $ohlc,$avgValue,VolumeAvg $volumeAvg){
		return new static($ohlc,$avgValue,$volumeAvg);
	}

	/**
	 * @return Ohlc
	 */
	public function getOhlc(): Ohlc {
		return $this->ohlc;
	}

	/**
	 * @return mixed
	 */
	public function getAvgValue() {
		return $this->avgValue;
	}




}