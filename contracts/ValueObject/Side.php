<?php

namespace EmpiricaPlatform\Contracts;

interface Side extends \Stringable
{
	public const LONG = 'LONG';
	public const SHORT = 'SHORT';

	public function getParam(): string;
	public function getValue(): string;
}
