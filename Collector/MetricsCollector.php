<?php

namespace Flagbit\Bundle\MetricsBundle\Collector;

use Beberlei\Metrics\Collector\Collector;
use Flagbit\Bundle\MetricsBundle\Collector\Factory\CollectorCollectionFactory;
use Flagbit\Bundle\MetricsBundle\Provider\MetricsProviderInterface;

class MetricsCollector
{
    /**
     * @var CollectorCollectionFactory
     */
    private $factory;

    /**
     * @var array
     */
    private $providers = array();

    /**
     * @param CollectorCollectionFactory $factory
     */
    public function __construct(CollectorCollectionFactory $factory)
    {
        $this->factory = $factory;
    }

    public function collectMetrics()
    {
        foreach ($this->providers as $provider) {
            $provider['provider']->collectMetrics($provider['collector']);
        }
    }

    /**
     * @param MetricsProviderInterface $provider
     * @param Collector[]              $collectors
     */
    public function addMetricsProvider(MetricsProviderInterface $provider, array $collectors)
    {
        $collectorsCollection = $this->factory->create();
        foreach ($collectors as $collector) {
            $collectorsCollection->addCollector($collector);
        }

        $this->providers[] = array(
            'provider' => $provider,
            'collector' => $collectorsCollection,
        );
    }
} 