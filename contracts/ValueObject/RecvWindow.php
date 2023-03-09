<?php

namespace EmpiricaPlatform\Contracts;

interface RecvWindow extends \Stringable
{
	public function getParam(): string;
	public function getValue(): string;
}
