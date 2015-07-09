<?php namespace Kumuwai\LocalStripe\Models;


class StripeBalanceTransaction extends StripeBaseModel
{
    protected $table = 'stripe_balance_transactions';

    public static function createFromStripe($stripe)
    {
        if ($found = self::find($stripe->id))
            return $found;

        self::create([
            'id' => $stripe->id,
            'amount' => $stripe->amount,
            'currency' => $stripe->currency,
            'net' => $stripe->net,
            'fee' => $stripe->fee,
            'charge_id' => $stripe->source,
            'created_at' => $stripe->created,
        ]);

        return self::findOrFail($stripe->id);
    }

    public function charge()
    {
        return $this->belongsTo(
            self::MY_NAMESPACE.'StripeCharge', 
            'charge_id'
        );
    }

    public function refund()
    {
        return $this->belongsTo(
            self::MY_NAMESPACE.'StripeRefund', 
            'charge_id'
        );
    }

}
