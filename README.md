# An advanced scheduler plugin for CakePHP

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer halftome/scheduler
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
        'interval' => 'PT15M',
    ],
    'CleanUp' => [
        'interval' => 'PT15M', // every 15min
        'className' => 'CleanUpDatabase',
        'method' => 'clean',
        'args' => [
            'regular',
            'db',
        ],
        'dependsOn' => [
            'Newsletters', // if these other tasks are running, wait for them to finish first
        ],
        'parallel' => true, // multiple instances of this task can be run simultaneously
    ]
]);
```