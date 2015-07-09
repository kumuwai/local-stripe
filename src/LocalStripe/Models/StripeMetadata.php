<?php namespace Kumuwai\LocalStripe\Models;


class StripeMetadata extends StripeBaseModel
{
    protected $table = 'stripe_metadata';


    public static function create(array $attributes = [])
    {
        $params = array_merge($attributes,['id'=>'meta_'.uniqid()]);

        return parent::create($params);
    }

    public function customer()
    {
        return $this->belongsTo(
            self::MY_NAMESPACE.'StripeCustomer', 
            'stripe_id'
        );
    }

    public function card()
    {
        return $this->belongsTo(
            self::MY_NAMESPACE.'StripeCard', 
            'stripe_id'
        );
    }

    public function charge()
    {
        return $this->belongsTo(
            self::MY_NAMESPACE.'StripeCharge', 
            'stripe_id'
        );
    }

}
