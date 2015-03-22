<?php namespace Kumuwai\LocalStripe\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;


class StripeAddress extends Eloquent
{
    public function card()
    {
        return $this->hasOne('Kumuwai\LocalStripe\Models\StripeCard', 'address_id');
    }
    
}
