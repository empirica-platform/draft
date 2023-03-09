<?php

namespace EmpiricaPlatform\Contracts;

interface Identifier
{
	public function __construct(?string $clientId = null, ?string $id = null);
	public function getClientId(): ?string;
	public function getId(): ?string;
}
