<?php
namespace VankoSoft\Alexandra\ODM\Hydrator;

use VankoSoft\Alexandra\ODM\Hydrator\HydratorInterface;

abstract class Hydrator implements HydratorInterface
{
    const DATASTAX_HYDRATOR = 0x0101;
    const ARRAY_HYDRATOR    = 0x0102;
    
    protected $config;
    protected $uow;
    
    public function __construct( $em )
    {
        $this->config   = $em->getSchema();
        $this->uow      = $em->getUnitOfWork();
    }
}

