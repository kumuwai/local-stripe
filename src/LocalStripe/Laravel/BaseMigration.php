<?php namespace Kumuwai\LocalStripe\Laravel;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;


abstract class BaseMigration extends Migration 
{
    public $schema;
    
    public function __construct()
    {
        if ($this->isLaravelProject()) {
            $this->schema = app('db')->connection()->getSchemaBuilder();
        } else {
            $this->schema = Capsule::schema();
        }
    }

    private function isLaravelProject()
    {
        return is_callable('app')
            && ( ! is_null(app()))
            && is_callable([app('db'), 'connection'])
            && is_callable([app('db')->connection(), 'getSchemaBuilder']);
    }

}
