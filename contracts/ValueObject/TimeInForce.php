<?php

namespace EmpiricaPlatform\Contracts;

interface TimeInForce extends \Stringable
{
	public const GOOD_TILL_CANCELED = 'GTC';
	public const IMMEDIATE_OR_CANCEL = 'IOC';
	public const FILL_OR_KILL = 'FOK';
	public const GOOD_TILL_DATE = 'GTD';

	public function getParam(): string;
	public function getValue(): string;
}
