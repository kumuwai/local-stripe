<?php namespace Kumuwai\LocalStripe\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;


class StripeCharge extends Eloquent
{
    protected $table = 'stripe_charges';
    protected $guarded = [];
    public $timestamps = false;


    public static function createFromStripe($stripe)
    {
        if ($found = self::find($stripe->id))
            return $found;

        self::create([
            'id' => $stripe->id,
            'card_id' => $stripe->source->id,
            'customer_id' => $stripe->source->customer,
            'livemode' => ($stripe->livemode == 'true'),
            'amount' => $stripe->amount,
            'captured' => ($stripe->captured == 'true'),
            'currency' => $stripe->currency,
            'paid' => ($stripe->paid == 'true'),
            'refunded' => ($stripe->refunded == 'true'),
            'status' => $stripe->status,
            'amount_refunded' => $stripe->amount_refunded,
            'description' => $stripe->description,
            'failure_code' => $stripe->failure_code,
            'failure_message' => $stripe->failure_message,
            'receipt_email' => $stripe->receipt_email,
            'receipt_number' => $stripe->receipt_number,
            'created_at' => $stripe->created,
        ]);

        foreach($stripe->metadata->__toArray() as $key=>$value)
            StripeMetadata::create(['stripe_id'=>$stripe->id, 'key'=>$key, 'value'=>$value]);

        StripeCard::createFromStripe($stripe->source);

        return self::findOrFail($stripe->id);
    }

    public function card()
    {
        return $this->belongsTo('Kumuwai\LocalStripe\Models\StripeCard', 'card_id');
    }

    public function customer()
    {
        return $this->belongsTo('Kumuwai\LocalStripe\Models\StripeCustomer', 'customer_id');
    }

    public function balance()
    {
        return $this->hasOne('Kumuwai\LocalStripe\Models\StripeBalanceTransaction', 'charge_id');
    }

    public function metadata()
    {
        return $this->hasMany('Kumuwai\LocalStripe\Models\StripeMetadata', 'stripe_id');
    }

}
