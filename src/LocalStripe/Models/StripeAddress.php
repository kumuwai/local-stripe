<?php namespace Kumuwai\LocalStripe\Models;


class StripeAddress extends StripeBaseModel
{

    protected $table = 'stripe_addresses';

    /**
     * Create an address from a Stripe Card record
     */
    public static function createFromStripe($stripe)
    {
        $params = [
            'address_city' => $stripe->address_city,
            'address_country' => $stripe->address_country,
            'address_line1' => $stripe->address_line1,
            'address_line2' => $stripe->address_line2,
            'address_state' => $stripe->address_state,
            'address_zip' => $stripe->address_zip,
            'country' => $stripe->country,
        ];

        if (self::hasEmptyParameterList($params))
            return;

        $params['id'] = 'addr_' . uniqid();
        $params['stripe_id'] = $stripe->id;

        return self::create($params);
    }

    private static function hasEmptyParameterList($params)
    {
        foreach($params as $param)
            if ($param)
                return false;

        return true;
    }

    public function card()
    {
        return $this->belongsTo(
            self::MY_NAMESPACE.'StripeCard', 
            'stripe_id', 
            'id'
        );
    }
    
}
