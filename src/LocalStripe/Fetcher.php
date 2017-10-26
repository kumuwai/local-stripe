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
        $transfers = $this->loadTransferRecords($params);

        return new Collection(compact('customers','charges','transfers'));
    }

    public function fetchCustomerRecords(array $params = [])
    {
        return $this->fetchStripeRecords('customer', $params);
    }

    public function fetchChargeRecords(array $params = [])
    {
        return $this->fetchStripeRecords('charge', $params);
    }

    public function fetchTransferRecords(array $params = [])
    {
        return $this->fetchStripeRecords('transfer', $params);
    }

    // TODO: Test
    public function fetchTransferChargeRecords(array $params = [])
    {
        return $this->fetchStripeRecords('transfer_charge', $params);
    }

    private function fetchStripeRecords($type, array $params, Closure $closure = Null)
    {
        $records = new Collection;
        do {
            $new = $this->connector->remote($type)->all($params);
            if ($closure) {
                $closure($new);
            }
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

    public function loadTransferRecords(array $params = [])
    {
        return $this->writeRecordsToLocalDatabase('transfer', $params);
    }

    private function writeRecordsToLocalDatabase($type, array $params = [])
    {
        $return = [];
        $method = 'write' . ucfirst($type) . 'Data';

        $this->fetchStripeRecords($type, $params, function($records) use (&$return, $method) {
            foreach($records->data as $record)
                try {
                    $return[] = $this->$method($record);
                } catch (\Exception $e) {
                    \Log::error($e->getMessage());
                }
        });
        return $return;
    }

    private function writeCustomerData($customer)
    {
        return $this->connector->local('customer')->createFromStripe($customer);
    }

    private function writeChargeData($charge)
    {
        if ($charge->balance_transaction) {
            $transaction = $this->connector->remote('balance_transaction')->retrieve($charge->balance_transaction);
            $this->connector->local('balance_transaction')->createFromStripe($transaction);
        }

        // TODO: Test me!
        if ($charge->refunds) {
            foreach($charge->refunds as $refund) {
                $this->connector->local('refund')->createFromStripe($refund);
            }
        }

        return $this->connector->local('charge')->createFromStripe($charge);
    }

    private function writeRefundData($refund)
    {
        $refund = $this->connector->remote('balance_transaction')->retrieve($charge->balance_transaction);
        $this->connector->local('balance_transaction')->createFromStripe($transaction);
    }

    private function writeTransferData($transfer)
    {
        $transfer = $this->connector->local('transfer')
            ->createFromStripe($transfer);

        $this->loadTransferChargeData($transfer);

        return $transfer;
    }

    private function loadTransferChargeData($transfer)
    {
        $charges = $this->fetchStripeRecords('balance_transaction',[
            'transfer' => $transfer->id,
            'limit' => 1000,
        ]);

        foreach($charges as $charge) {
            $this->connector->local('transfer_charge')
                ->createFromStripe($charge, $transfer->id);
        }
    }

}
