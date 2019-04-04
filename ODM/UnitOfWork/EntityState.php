<?php

namespace VankoSoft\Alexandra\ODM\UnitOfWork;

class EntityState
{
	const NOT_PERSISTED	= 0x01;
	const PERSISTED   	= 0x02;
	const UPDATED   	= 0x03;
	const REMOVED 		= 0x04;
}
