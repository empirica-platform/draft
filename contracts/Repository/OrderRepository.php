<?php

namespace EmpiricaPlatform\Contracts;

interface OrderRepository
{
	public function getByIdentifier(Identifier $identifier, SymbolPairInterface $symbolPair): Order;
	public function add(Order $order, OrderType $type): void;
	public function cancel(Order $order): void;
	public function findAll(OrderFindCriteria $criteria): iterable;
	public function setRecvWindow(RecvWindow $window): void;
}
