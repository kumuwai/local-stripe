<?php namespace Kumuwai\LocalStripe;

use Illuminate\Support\Arr;


class ParameterParser
{
    private $helper;

    public function __construct($helper = Null)
    {
        $this->helper = $helper ?: new Arr;
    }

    public function parse($type, array $items)
    {
        $flattened = $this->helper->dot($items);
        $filtered = $this->helper->only($flattened, $this->validElements[$type]);
        $full = array_merge($filtered, $this->getMetadata($type, $flattened));

        return $this->expand($full);
    }

    private function getMetadata($type, $original)
    {
        $candidates = $this->findStringInArrayKeys('/metadata/', $original);

        $return = [];
        foreach($candidates as $candidate=>$value) {
            if ($key = $this->getMetadataKeyForType($candidate, $type))
                $return[$key] = $value;
        }

        return $return;
    }

    private function findStringInArrayKeys($pattern, $input, $flags = 0) 
    {
        return array_intersect_key( 
            $input, 
            array_flip(
                preg_grep($pattern, array_keys($input), $flags)
            )
        );
    }

    private function getMetadataKeyForType($candidate, $type)
    {
        if (strpos($candidate, 'metadata') === 0)
            return $candidate;

        if (strpos($candidate, $type) === 0)
            return substr($candidate, strpos($candidate, 'metadata'));
    }

    private function expand(array $items)
    {
        $return = [];

        foreach ($items as $key => $value)
            $this->helper->set($return, $key, $value);

        return $return;        
    }

    protected $validElements = [
        'card' => [
            'source',
            'source.object',
            'source.number',
            'source.exp_month',
            'source.exp_year',
            'source.cvc',
            'source.name',
            'source.address_line1',
            'source.address_line2',
            'source.address_city',
            'source.address_zip',
            'source.address_state',
            'source.address_country',
        ],
        'charge' => [
            'amount',
            'currency',
            'customer',
            'source',
            'description',
            'capture',
            'statement_descriptor',
            'receipt_email',
            'application_fee',
            'shipping',
            'source.object',
            'source.number',
            'source.exp_month',
            'source.exp_year',
            'source.cvc',
            'source.name',
            'source.address_line1',
            'source.address_line2',
            'source.address_city',
            'source.address_zip',
            'source.address_state',
            'source.address_country',
        ],
        'customer' => [
            'account_balance',
            'coupon',
            'description',
            'email',
            'plan',
            'quantity',
            'trial_end',
            'source',
            'source.object',
            'source.number',
            'source.exp_month',
            'source.exp_year',
            'source.cvc',
            'source.name',
            'source.address_line1',
            'source.address_line2',
            'source.address_city',
            'source.address_zip',
            'source.address_state',
            'source.address_country',
        ],
    ];

}
