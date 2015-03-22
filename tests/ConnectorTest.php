<?php namespace Kumuwai\LocalStripe;

use Mockery;


class ConnectorTest extends TestCase
{
    public function setUp()
    {
        $this->test = new Connector;
    }

    public function testExists() {}

    /**
     * @dataProvider getStripeObjectList
     */
    public function testCanGetStripeObjects($selector, $className)
    {
        $this->assertNotNull($this->test->remote($selector));
        $this->assertInstanceOf(
            "Stripe\\$className", 
            $this->test->remote($selector)
        );
    }

    public function getStripeObjectList()
    {
        return array(
            // ['api_resource', 'ApiResource'],
            // ['singleton_api_resource', 'SingletonApiResource'],
            ['account', 'Account'],
            ['api_requestor', 'ApiRequestor'],
            ['api-requestor', 'ApiRequestor'],
            ['ApiRequestor', 'ApiRequestor'],
            ['apiRequestor', 'ApiRequestor'],
            ['application_fee', 'ApplicationFee'],
            ['application_fee_refund', 'ApplicationFeeRefund'],
            ['attached_object', 'AttachedObject'],
            ['balance', 'Balance'],
            ['balance_transaction', 'BalanceTransaction'],
            ['bitcoin_receiver', 'BitcoinReceiver'],
            ['bitcoin_transaction', 'BitcoinTransaction'],
            ['card', 'Card'],
            ['charge', 'Charge'],
            ['collection', 'Collection'],
            ['coupon', 'Coupon'],
            ['customer', 'Customer'],
            ['event', 'Event'],
            ['file_upload', 'FileUpload'],
            ['invoice', 'Invoice'],
            ['invoice_item', 'InvoiceItem'],
            ['object', 'Object'],
            ['plan', 'Plan'],
            ['recipient', 'Recipient'],
            ['refund', 'Refund'],
            ['stripe', 'Stripe'],
            ['subscription', 'Subscription'],
            ['token', 'Token'],
            ['transfer', 'Transfer'],
            ['transfer_reversal', 'TransferReversal'],
        );
    }

    /**
     * @dataProvider getLocalStripeObjectList
     */
    public function testCanGetLocalStripeObjects($selector, $className)
    {
        $this->assertNotNull($this->test->local($selector));
        $this->assertInstanceOf(
            "Kumuwai\\LocalStripe\\Models\\$className", 
            $this->test->local($selector)
        );
    }

    public function getLocalStripeObjectList()
    {
        return array(
            ['address', 'StripeAddress'],
            ['balance-transaction', 'StripeBalanceTransaction'],
            ['card', 'StripeCard'],
            ['charge', 'StripeCharge'],
            ['customer', 'StripeCustomer'],
            ['metadata', 'StripeMetadata'],
        );
    }

    public function testCanSetApiKey()
    {
        $stripe = Mockery::mock('Stripe\Stripe');
        $stripe->shouldReceive('setApiKey')->once()->with('stripe_secret');

        $test = Mockery::mock('Kumuwai\LocalStripe\Connector[remote]');
        $test->shouldReceive('remote')->once()->andReturn($stripe);

        $test->setApiKey('stripe_secret');
    }

}
