<?php

namespace Encore;

use League\Container\Container as BaseContainer;
use League\Container\Definition\DefinitionAggregateInterface;
use League\Container\Inflector\InflectorAggregateInterface;
use League\Container\ReflectionContainer;
use League\Container\ServiceProvider\ServiceProviderAggregateInterface;

class Container extends BaseContainer
{
    /**
     * Automatically resolve objects not explicitally
     * bound.
     *
     * @var boolean
     */
    protected $autoWiring = true;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        DefinitionAggregateInterface      $definitions = null,
        ServiceProviderAggregateInterface $providers = null,
        InflectorAggregateInterface       $inflectors = null
    ) {
        parent::__construct($definitions, $providers, $inflectors);

        if ($this->autoWiring) {
            $this->delegate(
                (new ReflectionContainer)->cacheResolutions()
            );
        }
    }
}