<?php namespace Kumuwai\LocalStripe;


class Pusher
{
    private $connector;
    private $parser;

    public function __construct(Connector $connector, ParameterParser $parser)
    {
        $this->connector = $connector;
        $this->parser = $parser;
    }

    public function createCustomer($params)
    {
        $customerArguments = $this->parser->parse('customer', $params);
        $cardArguments = $this->parser->parse('card', $params);

        $stripeCustomer = $this->connector->remote('customer')
            ->create($customerArguments);
        $stripeCard = $stripeCustomer->sources->create($cardArguments);

        $localCustomer = $this->connector->local('customer')
            ->createFromStripe($stripeCustomer);

        return $localCustomer;
    }

    public function charge($params)
    {
        $chargeArguments = $this->parser->parse('charge', $params);
        $stripeCharge = $this->connector->remote('charge')
            ->create($chargeArguments);
        $localCharge = $this->connector->local('charge')
            ->createFromStripe($stripeCharge);

        return $localCharge;
    }

}
