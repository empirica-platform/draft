<?php

namespace EmpiricaPlatform\Contracts;

interface OrderFindCriteria
{
	public function __construct(SymbolPairInterface $symbolPair);
	public function setStartTime(\DateTimeInterface $startTime): self;
	public function setEndTime(\DateTimeInterface $endTime): self;
	public function setLimit(int $limit): self;
	public function makeParams(): array;
}
