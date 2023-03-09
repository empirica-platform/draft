<?php

namespace EmpiricaPlatform\Contracts;

interface Order
{
	public function __construct(
        Side                $side,
        SymbolPairInterface $symbolPair,
        Volume              $volume,
        OrderStatus         $status,
        Identifier          $identifier
	);
	public function getSide(): Side;
	public function getSymbolPair(): SymbolPairInterface;
	public function getVolume(): Volume;
	public function getStatus(): OrderStatus;
	public function getIdentifier(): Identifier;
}
