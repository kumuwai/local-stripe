<?php namespace Kumuwai\LocalStripe\Models;


class StripeTransfer extends StripeBaseModel
{
    protected $table = 'stripe_transfers';
    protected $dates = ['deposited_at'];

    public static function createFromStripe($stripe)
    {
        if ($found = self::find($stripe->id))
            return $found;

        self::create([
            'id' => $stripe->id,
            'destination_id' => $stripe->destination,
            'amount' => $stripe->amount,
            'currency' => $stripe->currency,
            'status' => $stripe->status,
            'deposited_at' => $stripe->date,
            'created_at' => $stripe->created,
        ]);

        self::createMetadata($stripe);

        return self::findOrFail($stripe->id);
    }


    public function charges()
    {
        return $this->belongsToMany(
            self::MY_NAMESPACE.'StripeCharge',
            'stripe_transfer_charges',
            'transfer_id',
            'charge_id'
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

}
