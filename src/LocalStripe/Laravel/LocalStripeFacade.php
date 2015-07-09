<?php namespace Kumuwai\LocalStripe\Laravel;

use Illuminate\Support\Facades\Facade;

 
class LocalStripeFacade extends Facade 
{
    protected static function getFacadeAccessor() { return 'local-stripe'; }
}
