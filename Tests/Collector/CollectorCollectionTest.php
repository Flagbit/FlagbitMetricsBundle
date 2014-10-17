<?php

use Flagbit\Bundle\MetricsBundle\Collector\CollectorCollection;

class CollectorCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CollectorCollection
     */
    private $collectorCollection;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $collector;

    protected function setUp()
    {
        $this->collector = $this->getMock('Beberlei\Metrics\Collector\Collector');
        $this->collectorCollection = new CollectorCollection();
        $this->collectorCollection->addCollector($this->collector);
    }

    public function testIncrement()
    {
        $metricName = 'foo';

        $this->collector
            ->expects($this->once())
            ->method('increment')
            ->with($metricName);

        $this->collectorCollection->increment($metricName);
    }

    public function testDecrement()
    {
        $metricName = 'foo';

        $this->collector
            ->expects($this->once())
            ->method('decrement')
            ->with($metricName);

        $this->collectorCollection->decrement($metricName);
    }

    public function testTiming()
    {
        $metricName = 'foo';
        $metricValue = 'bar';

        $this->collector
            ->expects($this->once())
            ->method('timing')
            ->with($metricName, $metricValue);

        $this->collectorCollection->timing($metricName, $metricValue);
    }

    public function testMeasure()
    {
        $metricName = 'foo';
        $metricValue = 'bar';

        $this->collector
            ->expects($this->once())
            ->method('measure')
            ->with($metricName, $metricValue);

        $this->collectorCollection->measure($metricName, $metricValue);
    }
}