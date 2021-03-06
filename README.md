# FlagbitMetricsBundle [![Build Status](https://travis-ci.org/Flagbit/FlagbitMetricsBundle.svg?branch=master)](https://travis-ci.org/Flagbit/FlagbitMetricsBundle) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Flagbit/FlagbitMetricsBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Flagbit/FlagbitMetricsBundle/?branch=master) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/073d1c3c-d8d5-4abf-93f5-b2107b33cea8/mini.png)](https://insight.sensiolabs.com/projects/073d1c3c-d8d5-4abf-93f5-b2107b33cea8)

## About

The FlagbitMetricsBundle provides easy integration for the [metrics collector library](https://github.com/beberlei/metrics) 
of Bejamin Eberlei into Symfony2.

## Installation

### Using Composer

Installation with composer:

```bash
composer require flagbit/metrics-bundle
```

### Register the bundle

```php
<?php

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Flagbit\Bundle\MetricsBundle\FlagbitMetricsBundle(),
            // ...
        );
    }
}
```

## Usage

Don't forget that this bundle has a dependency on a Metrics library, which you should first integrate and configure.
More information can be found [here](https://github.com/beberlei/metrics).

For example, just imagine you want to measure some stats over you application.

### Create your MetricProvider

```php
<?php

namespace Flagbit\ExampleBundle\MetricProvider;

use Flagbit\Bundle\MetricsBundle\Collector\CollectorInterface;
use Flagbit\Bundle\MetricsBundle\Provider\ProviderInterface;

class Provider implements ProviderInterface
{
    public function collectMetrics(CollectorInterface $collector)
    {
        $value = random_int(1,9);
        $collector->measure('foo.bar', $value);
    }
}
```

### Create your Service

Once you have created your metric provider class, let's go to create the service. In order the metric collector service 
automatically to collect all the metrics of your metric provider service, you just need to use the "metrics.provider" 
service tag and select so many collectors as you want.

#### YAML

```yml
services:
    Flagbit\ExampleBundle\MetricProvider\Provider:
        tags:
            - { name: metrics.provider, collector: statsd }
            - { name: metrics.provider, collector: librato }
```
#### XML

```xml
<?xml version="1.0" encoding="UTF-8" ?>
<container xmlns="http://symfony.com/schema/dic/services"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/services
        http://symfony.com/schema/dic/services/services-1.0.xsd"
>
    <services>
        <service id="Flagbit\ExampleBundle\MetricProvider\Provider">
            <tag name="metrics.provider" collector="statsd" />
            <tag name="metrics.provider" collector="librato" />
        </service>
    </services>
</container>
```

## Collect your Metrics

You can collect all metrics by yourself and after flush them to your metric servers or use the command that does 
it for you instead.

```php
<?php

// Collects the metrics of all your tagged services...
$container->get('flagbit_metrics.provider_invoker')->collectMetrics();
// ... and flush them
$container->get('flagbit_metrics.provider_invoker')->onTerminate();
```

It is recommended to inject the services into yours instead of using directly the container.

### Command

```bash
$ php bin/console flagbit:metrics:flush
```

