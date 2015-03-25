<?php namespace Kumuwai\LocalStripe;

use Illuminate\Support\Collection;
use Closure;


class Fetcher
{   
    protected $connector;

    public function __construct(Connector $connector = Null)
    {
        $this->connector = $connector;
    }

    public function fetch($params = [])
    {
        $customers = $this->loadCustomerRecords($params);
        $charges = $this->loadChargeRecords($params);

        return new Collection(compact('customers','charges'));
    }

    public function fetchCustomerRecords(array $params = [])
    {
        return $this->fetchStripeRecords('customer', $params);
    }

    public function fetchChargeRecords(array $params = [])
    {
        return $this->fetchStripeRecords('charge', $params);
    }

    private function fetchStripeRecords($type, array $params, Closure $closure = Null)
    {
        $records = new Collection;
        do {
            $new = $this->connector->remote($type)->all($params);
            if ($closure) $closure($new);
            $records = $records->merge($new->data);
            $params['starting_after'] = $records->last() ? $records->last()->id : null;
        } while( $new->has_more );

        return $records;
    }

    public function loadCustomerRecords(array $params = [])
    {
        return $this->writeRecordsToLocalDatabase('customer', $params);
    }

    public function loadChargeRecords(array $params = [])
    {
        return $this->writeRecordsToLocalDatabase('charge', $params);
    }

    private function writeRecordsToLocalDatabase($type, array $params = [])
    {
        $return = [];
        $method = 'write' . ucfirst($type) . 'Data';

        $this->fetchStripeRecords($type, $params, function($records) use (&$return, $method) {
            foreach($records->data as $record)
                $return[] = $this->$method($record);
        });
        return $return;
    }    

    private function writeCustomerData($customer)
    {
        return $this->connector->local('customer')->createFromStripe($customer);
    }

    private function writeChargeData($charge)
    {
        $transaction = $this->connector->remote('balance_transaction')->retrieve($charge->balance_transaction);
        $this->connector->local('balance_transaction')->createFromStripe($transaction);
        
        return $this->connector->local('charge')->createFromStripe($charge);
    }

}
