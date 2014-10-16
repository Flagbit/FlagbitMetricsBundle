<?php

use Flagbit\Bundle\MetricsBundle\Collector\MetricsCollector;

class MetricsCollectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $factory;

    /**
     * @var MetricsCollector
     */
    private $metricsProvider;

    protected function setUp()
    {
        $this->factory = $this->getMock('Flagbit\Bundle\MetricsBundle\Collector\Factory\CollectorCollectionFactory');
        $this->metricsProvider = new MetricsCollector($this->factory);
    }

    public function testAddMetricsProvider()
    {
        $collector = $this->getMock('Beberlei\Metrics\Collector\Collector');
        $provider = $this->getMock('Flagbit\Bundle\MetricsBundle\Collector\MetricsProviderInterface');

        $this->getCollectorCollection($collector);

        $this->metricsProvider->addMetricsProvider($provider, array($collector));
    }

    public function testMetricsProvider()
    {
        $collector = $this->getMock('Beberlei\Metrics\Collector\Collector');
        $provider = $this->getMock('Flagbit\Bundle\MetricsBundle\Collector\MetricsProviderInterface');
        $collectorCollection = $this->getCollectorCollection($collector);

        $provider
            ->expects($this->once())
            ->method('collectMetrics')
            ->with($collectorCollection);

        $this->metricsProvider->addMetricsProvider($provider, array($collector));
        $this->metricsProvider->collectMetrics();
    }

    private function getCollectorCollection($collector)
    {
        $collectorCollection = $this->getMock('Flagbit\Bundle\MetricsBundle\Collector\CollectorCollection');

        $this->factory
            ->expects($this->once())
            ->method('create')
            ->will($this->returnValue($collectorCollection));

        $collectorCollection
            ->expects($this->once())
            ->method('addCollector')
            ->with($collector);

        return $collectorCollection;
    }
}