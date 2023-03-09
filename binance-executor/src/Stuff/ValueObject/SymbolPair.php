<?php

namespace Gri3li\BinanceApi\Stuff\ValueObject;

use EmpiricaPlatform\Contracts\SymbolEnum;
use EmpiricaPlatform\Contracts\SymbolPairInterface;

class SymbolPair implements SymbolPairInterface
{
	private SymbolEnum $base;
	private SymbolEnum $quote;

	public function __construct(SymbolEnum $base, SymbolEnum $quote)
	{
		$this->base = $base;
		$this->quote = $quote;
	}

	public function getBase(): SymbolEnum
	{
		return $this->base;
	}

	public function getQuote(): SymbolEnum
	{
		return $this->quote;
	}

	public function getParam(): string
	{
		return $this->base->getParam();
	}

	public function getValue(): string
	{
		return $this->base->getValue() . '/' . $this->quote->getValue();
	}

	public function __toString(): string
	{
		return $this->getValue();
	}
}
