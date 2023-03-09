<?php

namespace EmpiricaPlatform\Contracts;

interface AccountRepository
{
	public function deposit(Account $to): void;
	public function withdraw(Account $from): void;
	public function transfer(Account $from, Account $to): void;
	public function findAll(AccountFindCriteria $criteria): iterable;
	public function setRecvWindow(RecvWindow $window): void;
}
