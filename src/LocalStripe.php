<?php namespace Kumuwai\LocalStripe;


class LocalStripe
{
    private $connector;
    private $pusher;
    private $fetcher;

    public function __construct(
        Connector $connector = Null, Pusher $pusher = Null, Fetcher $fetcher = Null
    ){
        $this->connector = $connector;
        $this->pusher = $pusher;
        $this->fetcher = $fetcher;
    }

    public function remote($stripeObjectName)
    {
        return $this->connector->remote($stripeObjectName);
    }

    public function local($localObjectName)
    {
        return $this->connector->local($localObjectName);
    }

    public function fetch(array $params = [])
    {
        return $this->fetcher->fetch($params);
    }

    public function create(array $params = [])
    {
        return $this->pusher->createCustomer($params);
    }

    public function chargeCustomer(array $params = [])
    {
        $customer = $this->pusher->createCustomer($params);
        return $this->pusher->charge(
            array_merge(['source'=>$customer->id],$params)
        );
    }

    public function charge(array $params = [])
    {
        return $this->pusher->charge($params);
    }

    public function getConnector()
    {
        return $this->connector;
    }
    
    public function getPusher()
    {
        return $this->pusher;
    }

    public function getFetcher()
    {
        return $this->fetcher;
    }

}

