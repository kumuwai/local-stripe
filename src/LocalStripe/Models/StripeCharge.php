<?php namespace Kumuwai\LocalStripe\Models;


class StripeCharge extends StripeBaseModel
{
    protected $table = 'stripe_charges';

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

        self::createMetadata($stripe);

        StripeCard::createFromStripe($stripe->source);

        return self::findOrFail($stripe->id);
    }

    public function card()
    {
        return $this->belongsTo(
            self::MY_NAMESPACE . 'StripeCard', 
            'card_id'
        );
    }

    public function customer()
    {
        return $this->belongsTo(
            self::MY_NAMESPACE . 'StripeCustomer', 
            'customer_id'
        );
    }

    public function balance()
    {
        return $this->hasOne(
            self::MY_NAMESPACE . 'StripeBalanceTransaction', 
            'charge_id'
        );
    }

    public function refunds()
    {
        return $this->hasMany(
            self::MY_NAMESPACE . 'StripeRefund', 
            'charge_id'
        );
    }

    public function transfers()
    {
        return $this->belongsToMany(
            self::MY_NAMESPACE . 'StripeTransfer',
            'stripe_transfer_charges',
            'charge_id',
            'transfer_id'
        )->withPivot([
            'transfer_id',
            'charge_id',
            'transaction_id',
            'amount',
            'currency',
            'net',
            'fee',
            'available_at',
            'created_at',
        ]);
    }

    public function metadata()
    {
        return $this->hasMany(
            self::MY_NAMESPACE . 'StripeMetadata', 
            'stripe_id'
        );
    }

}
