<?php

namespace EmpiricaPlatform\Contracts;

interface SymbolPairInterface extends \Stringable
{
	public function getBase(): SymbolEnum;
	public function getQuote(): SymbolEnum;
}
