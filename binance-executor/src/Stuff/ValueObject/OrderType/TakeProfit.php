<?php

namespace Gri3li\BinanceApi\Stuff\ValueObject\OrderType;

use EmpiricaPlatform\Contracts\OrderTypeInterface;
use EmpiricaPlatform\Contracts\PriceInterface;

class TakeProfit implements OrderTypeInterface
{
	private PriceInterface $stopPrice;

	public function __construct(PriceInterface $stopPrice)
	{
		$this->stopPrice = $stopPrice;
	}

	public function getParams(): array
	{
		return [
			'type' => 'TAKE_PROFIT', // required, ENUM(LIMIT, MARKET, STOP_LOSS, STOP_LOSS_LIMIT, TAKE_PROFIT, TAKE_PROFIT_LIMIT, LIMIT_MAKER)
			'stopPrice' => $this->stopPrice, // required, DECIMAL
		];
	}
}
