<?php

namespace Gri3li\BinanceApi\Stuff\ValueObject\OrderType;

use EmpiricaPlatform\Contracts\OrderTypeInterface;
use EmpiricaPlatform\Contracts\PriceInterface;
use EmpiricaPlatform\Contracts\TimeInForceInterface;

class Limit implements OrderTypeInterface
{
	private PriceInterface $price;
	private TimeInForceInterface $timeInForce;

	public function __construct(PriceInterface $price, TimeInForceInterface $timeInForce)
	{
		$this->price = $price;
		$this->timeInForce = $timeInForce;
	}

	public function getParams(): array
	{
		return [
			'type' => 'LIMIT', // required, ENUM(LIMIT, MARKET, STOP_LOSS, STOP_LOSS_LIMIT, TAKE_PROFIT, TAKE_PROFIT_LIMIT, LIMIT_MAKER)
			'timeInForce' => (string) $this->timeInForce, // optional, ENUM(GTC, IOC, FOK)
			'price' => $this->price, // required, DECIMAL
		];
	}
}
