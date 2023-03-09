<?php

namespace EmpiricaPlatform\Contracts;

interface Position
{
	public function __construct(
        Identifier          $identifier,
        SymbolPairInterface $symbolPair,
        Side                $side,
        Volume              $volume,
        PriceInterface      $entryPrice,
        PriceInterface      $markPrice,
        PriceInterface      $unRealizedProfit
	);
	public function getIdentifier(): Identifier;
	public function getSymbolPair(): SymbolPairInterface;
	public function getSide(): Side;
	public function getVolume(): Volume;
	public function getEntryPrice(): PriceInterface;
	public function getMarkPrice(): PriceInterface;
	public function getUnRealizedPnL(): PriceInterface;
}
