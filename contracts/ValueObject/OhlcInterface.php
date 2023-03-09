<?php

namespace EmpiricaPlatform\Contracts;

use DateTimeInterface;

interface OhlcInterface
{
	public function getSymbolPair(): SymbolPairInterface;
	public function getDateTime(): DateTimeInterface;
	public function getVolume(): VolumeInterface;
	public function getOpen(): PriceInterface;
	public function getHigh(): PriceInterface;
	public function getLow(): PriceInterface;
	public function getClose(): PriceInterface;
}
