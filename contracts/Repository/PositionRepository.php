<?php

namespace EmpiricaPlatform\Contracts;

interface PositionRepository
{
	public function getByIdentifier(Identifier $identifier, SymbolPairInterface $symbolPair): Position;
	public function findAll(PositionFindCriteria $criteria): iterable;
	public function setRecvWindow(RecvWindow $window): void;
}
