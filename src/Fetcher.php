<?php namespace Kumuwai\LocalStripe;


class Fetcher
{   
    protected $connector;


    public function __construct(Connector $connector = Null)
    {
        $this->connector = $connector;
    }

    public function fetch($params = [])
    {
        // $this->loadNewCustomers($params);
        // $this->loadNewCharges($params);
    }

}
