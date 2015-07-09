<?php namespace Kumuwai\LocalStripe\Models;

use Illuminate\Database\Eloquent\ModelNotFoundException;


class StripeTransferCharge extends StripeBaseModel
{
    protected $table = 'stripe_transfer_charges';
    protected $dates = ['available_at'];

    public static function createFromStripe($stripe, $transfer_id)
    {
        if ($found = self::find($transfer_id.$stripe->source))
            return $found;

        self::create([
            'id' => $transfer_id.$stripe->source,
            'transfer_id' => $transfer_id,
            'charge_id' => $stripe->source,
            'transaction_id' => $stripe->id,
            'amount' => $stripe->amount,
            'currency' => $stripe->currency,
            'net' => $stripe->net,
            'fee' => $stripe->fee,
            'available_at' => $stripe->available_on,
            'created_at' => $stripe->created,
        ]);

        return self::findOrFail($transfer_id.$stripe->source);
    }

}
