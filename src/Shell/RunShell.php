<?php

namespace Scheduler\Shell;

use Cake\Console\Shell;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

class RunShell extends Shell
{
    private $jobs;
    private $table;

    public function main()
    {
        $this->jobs = Configure::read('Scheduler.jobs') ?: [];
        $this->table = TableRegistry::get('SchedulerJobs');

    }
}