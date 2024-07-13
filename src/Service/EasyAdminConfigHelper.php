<?php

namespace App\Service;

class EasyAdminConfigHelper
{
    const DEFAULT_NAMESPACE = 'App\Entity\%s';
    
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }
    
    public function getEntityName(string $entity): string
    {
        return $this->config['entities'][$entity]['class'] ?? sprintf(self::DEFAULT_NAMESPACE, $entity);
    }
}
