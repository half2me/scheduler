<?php

namespace Scheduler\Shell;

use Cake\Console\Shell;
use Cake\Core\Configure;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;

class RunShell extends Shell
{
    private $jobs;
    private $table;

    private $defaults = [
        'jobTimeout' => '+15 minutes',
    ];

    public function main()
    {
        $this->jobs = Configure::read('Scheduler.jobs') ?: [];
        $this->table = TableRegistry::get('SchedulerJobs');

        if (!empty($this->jobs)) {
            foreach ($this->jobs as $name => $job) {
                $entity = null;
                $this->table->connection()->transactional(function () use ($name, $job, &$entity) {
                    $entity = $this->table->find()
                        ->where(['SchedulerJobs.name' => $name])
                        ->first();

                    if ($entity) {
                        // Check for timeouts
                        if ($entity->running) {
                            $timeout = @$job['timeout'] ?: $this->defaults['jobTimeout'];
                            if ($entity->last_run->wasWithinLast($timeout)) {
                                // Task is running! Quit!
                                $entity = null;
                                return;
                            }
                        }

                        // Determine if job needs to be run
                        if ($entity->last_run->wasWithinLast(@$job['interval'])) {
                            $entity = null;
                            return;
                        }
                    } else { // Create the entity if it is not in the db
                        $entity = $this->table->newEntity();
                    }

                    $entity->running = true;
                    $entity->last_run = Time::now();

                    $this->table->save($entity);
                });

                if ($entity) {
                    try {
                        $this->dispatchShell([
                            'command' => @$job['className'] ?: $name,
                            'extra' => @$job['extra'] ?: [],
                        ]);
                    } catch (\Exception $e) {
                        //TODO: log this?
                    }

                    $entity->running = false;
                    $entity->last_run_finished = Time::now();
                    $this->table->save($entity);
                }
            }
        }
    }
}