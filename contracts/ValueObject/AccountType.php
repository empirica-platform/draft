<?php

namespace EmpiricaPlatform\Contracts;

interface AccountType extends \Stringable
{
	public const SPOT = 'SPOT';
	public const MARGIN = 'MARGIN';
	public const FUTURES = 'FUTURES';
	public const OPTION = 'OPTION';
}
