<?php

namespace EmpiricaPlatform\Contracts;

interface Account
{
	public function __construct(
        AccountExchange $exchange,
        AccountType     $type,
        SymbolEnum      $symbol,
        Volume          $volume
	);
	public function getExchange(): AccountExchange;
	public function getType(): AccountType;
	public function getSymbol(): SymbolEnum;
	public function getVolume(): Volume;
}
