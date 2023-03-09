<?php

namespace EmpiricaPlatform\Contracts;

interface Ask
{
	public function __construct(SymbolPairInterface $symbolPair, PriceInterface $price, Volume $volume);
	public function getSymbolPair(): SymbolPairInterface;
	public function getPrice(): PriceInterface;
	public function getVolume(): Volume;
}
