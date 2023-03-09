<?php

namespace EmpiricaPlatform\Contracts;

interface OhlcRepository
{
	public function findAll(OrderFindCriteria $criteria): iterable;
	public function setRecvWindow(RecvWindow $window): void;
}
