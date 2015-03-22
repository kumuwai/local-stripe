<?php namespace Kumuwai\LocalStripe\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;


class StripeCard extends Eloquent
{
    public function customer()
    {
        return $this->belongsTo('Kumuwai\LocalStripe\Models\StripeCustomer','customer_id');
    }

    public function address()
    {
        return $this->belongsTo('Kumuwai\LocalStripe\Models\StripeAddress','address_id');
    }

    public function charges()
    {
        return $this->hasMany('Kumuwai\LocalStripe\Models\StripeCharge','card_id');
    }

    public function metadata()
    {
        return $this->hasMany('Kumuwai\LocalStripe\Models\StripeMetadata','stripe_id');
    }

}
