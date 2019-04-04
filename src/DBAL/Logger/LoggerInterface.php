<?php

namespace VankoSoft\Alexandra\DBAL\Logger;

interface LoggerInterface
{
	public function log( $message, $level, array $data );
}
