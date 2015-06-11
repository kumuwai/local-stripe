<?php namespace Kumuwai\LocalStripe\Models;


class StripeRefund extends StripeBaseModel
{
    protected $table = 'stripe_refunds';

    public static function createFromStripe($stripe)
    {
        if ($found = self::find($stripe->id))
            return $found;

        self::create([
            'id' => $stripe->id,
            'amount' => $stripe->amount,
            'currency' => $stripe->currency,
            'transaction_id' => $stripe->balance_transaction,
            'charge_id' => $stripe->charge,
            'receipt_number' => $stripe->receipt_number,
            'reason' => $stripe->reason,
            'created_at' => $stripe->created,
        ]);

        self::createMetadata($stripe);

        return self::findOrFail($stripe->id);
    }

    public function charge()
    {
        return $this->belongsTo(
            self::MY_NAMESPACE.'StripeCharge', 
            'charge_id'
        );
    }

    public function balance()
    {
        return $this->hasOne(
            self::MY_NAMESPACE.'StripeBalanceTransaction', 
            'charge_id'
        );
    }

    public function metadata()
    {
        return $this->hasMany(
            self::MY_NAMESPACE.'StripeMetadata', 
            'stripe_id'
        );
    }

}
