<?php

namespace Gri3li\BinanceExecutor\Spot;

use DateTimeInterface;
use EmpiricaPlatform\BinanceApi\FindParamsInterface;
use Gri3li\BinanceApi\Stuff\Identifier;
use EmpiricaPlatform\Contracts\FindCriteriaInterface;
use EmpiricaPlatform\Contracts\IdentifierInterface;
use EmpiricaPlatform\Contracts\SymbolPairInterface;

// критерии нужно мапить так же как валуеобжекты
// избавиться от критерия, передавать параметры на прямую
class OrderFind implements FindCriteriaInterface, FindParamsInterface
{
	private SymbolPairInterface $symbolPair;
	private ?IdentifierInterface $identifier = null;
	private ?DateTimeInterface $startTime = null;
	private ?DateTimeInterface $endTime = null;
	private int $limit = 500;

	public function __construct(SymbolPairInterface $symbolPair)
	{
		$this->symbolPair = $symbolPair;
	}

	public function setIdentifier(IdentifierInterface $identifier): self
	{
		$this->identifier = $identifier;

		return $this;
	}

	public function setStartTime(DateTimeInterface $startTime): self
	{
		$this->startTime = $startTime;

		return $this;
	}

	public function setEndTime(DateTimeInterface $endTime): self
	{
		$this->endTime = $endTime;

		return $this;
	}

	public function setLimit(int $limit): self
	{
		if ($limit >= 1000) {
			throw new \Exception(); //TODO improve exception structure
		}
		$this->limit = $limit;

		return $this;
	}

	public function makeParams(): array
	{
		$params = [
			'symbol' => $this->symbolPair->getParam(),
		];
		if ($this->identifier && $this->identifier->getId()) {
			$params['orderId'] = $this->identifier->getId();
		}
		if ($this->startTime) {
			$params['startTime'] = $this->startTime; // optional, LONG
		}
		if ($this->endTime) {
			$params['endTime'] = $this->endTime; // optional, LONG
		}
		if ($this->limit) {
			$params['limit'] = $this->limit; // optional, LONG
		}

		return $params;
	}
}
