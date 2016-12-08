<?php

use Migrations\AbstractMigration;

/**
 * Created by PhpStorm.
 * User: halftome
 * Date: 2016. 12. 07.
 * Time: 19:33
 */
class Initial extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('scheduler_jobs');
        $table
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('running', 'boolean', [
                'default' => false,
                'null' => false,
            ])
            ->addColumn('last_run', 'datetime', [
                'default' => null,
                'null' => false,
            ])
            ->addColumn('last_run_finished', 'datetime', [
                'default' => null,
                'null' => false,
            ])
            ->addIndex(['name'], ['unique' => true])
            ->create();
    }
}