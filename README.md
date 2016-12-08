# An advanced scheduler plugin for CakePHP

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.txt)
[![Total Downloads](https://img.shields.io/packagist/dt/halftome/scheduler.svg?style=flat-square)](https://packagist.org/packages/halftome/scheduler)
[![Latest Stable Version](https://img.shields.io/packagist/v/halftome/scheduler.svg?style=flat-square&label=stable)](https://packagist.org/packages/halftome/scheduler)

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer halftome/scheduler
```
Setup the database with migrations:
```
bin/cake migrations migrate -p Scheduler
```

## Usage
The scheduler will only work if it is invoked, and will only be as precise as the interval it is invoked with.
For an example, here is an example cron job using 1min (the shortest allowed interval on cron) as the interval:
```cron
* * * * * cd /path/to/app && bin/cake Scheduler.Run
```

Here is an example configuration to run 2 tasks:
```php
// For example in your bootstrap.php
Configure::write('Scheduler.jobs', [
    'Newsletters' => [
        'interval' => '2 weeks',
    ],
    'CleanUp' => [
        'interval' => '15 minutes', // every 15min
        'command' => 'CleanUpDatabase clean',
        'extra' => [
            'foo' => 'bar',
        ],
        'timeout' => '15 minutes', // if task has not finished after 15min it will be aborted
    ],
    'QuotaCheck' => [
        'interval' => '6 hours',
    ],
]);
```