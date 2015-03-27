<?php namespace Kumuwai\LocalStripe;


class ParameterParserTest extends TestCase
{
    public function testExists()
    {
        $test = new ParameterParser;
    }

    /**
     * @dataProvider getValueList
     */
    public function testItCanPassValidValuesByType($type, $in, $out)
    {
        $test = new ParameterParser;

        $result = $test->parse($type, $in);

        $this->assertEquals($out, $result);
    }

    public function getValueList()
    {
        return array(
            // Test types of objects ------------------------------------------
            // (existing) single value
            ['customer',['account_balance'=>0],['account_balance'=>0]],
            // (existing) multiple values
            ['customer',['account_balance'=>0,'coupon'=>'xx'],['account_balance'=>0,'coupon'=>'xx']],
            // (non-existing) single value
            ['customer',['foo'=>0],[]],
            // existing / non-existing values
            ['customer',['coupon'=>'x','foo'=>0],['coupon'=>'x']],
            // existing nested value
            ['customer',['source'=>['object'=>'card']],['source'=>['object'=>'card']]],
            // existing nested value (dot notation)
            ['customer',['source.object'=>'card'],['source'=>['object'=>'card']]],
            // non-existing nested value
            ['customer',['source'=>['foo'=>'bar']],[]],
            // Any metadata
            ['customer',['metadata.foo'=>'bar'],['metadata'=>['foo'=>'bar']]],
            // Object-specific metadata
            ['customer',['customer.metadata.foo'=>'bar'],['metadata'=>['foo'=>'bar']]],
            // Card data without source
            ['card',['object'=>'x'],['source'=>['object'=>'x']]],


            // Check all documented values  -----------------------------------
            ['card',['source'=>'x'],['source'=>'x']],
            ['card',['source.object'=>'x'],['source'=>['object'=>'x']]],
            ['card',['source.number'=>'x'],['source'=>['number'=>'x']]],
            ['card',['source.exp_month'=>'x'],['source'=>['exp_month'=>'x']]],
            ['card',['source.exp_year'=>'x'],['source'=>['exp_year'=>'x']]],
            ['card',['source.cvc'=>'x'],['source'=>['cvc'=>'x']]],
            ['card',['source.name'=>'x'],['source'=>['name'=>'x']]],
            ['card',['source.address_line1'=>'x'],['source'=>['address_line1'=>'x']]],
            ['card',['source.address_line2'=>'x'],['source'=>['address_line2'=>'x']]],
            ['card',['source.address_city'=>'x'],['source'=>['address_city'=>'x']]],
            ['card',['source.address_zip'=>'x'],['source'=>['address_zip'=>'x']]],
            ['card',['source.address_state'=>'x'],['source'=>['address_state'=>'x']]],
            ['card',['source.address_country'=>'x'],['source'=>['address_country'=>'x']]],
            ['card',['object'=>'x'],['source'=>['object'=>'x']]],
            ['card',['number'=>'x'],['source'=>['number'=>'x']]],
            ['card',['exp_month'=>'x'],['source'=>['exp_month'=>'x']]],
            ['card',['exp_year'=>'x'],['source'=>['exp_year'=>'x']]],
            ['card',['cvc'=>'x'],['source'=>['cvc'=>'x']]],
            ['card',['name'=>'x'],['source'=>['name'=>'x']]],
            ['card',['address_line1'=>'x'],['source'=>['address_line1'=>'x']]],
            ['card',['address_line2'=>'x'],['source'=>['address_line2'=>'x']]],
            ['card',['address_city'=>'x'],['source'=>['address_city'=>'x']]],
            ['card',['address_zip'=>'x'],['source'=>['address_zip'=>'x']]],
            ['card',['address_state'=>'x'],['source'=>['address_state'=>'x']]],
            ['card',['address_country'=>'x'],['source'=>['address_country'=>'x']]],
            ['charge',['amount'=>'x'],['amount'=>'x']],
            ['charge',['currency'=>'x'],['currency'=>'x']],
            ['charge',['customer'=>'x'],['customer'=>'x']],
            ['charge',['description'=>'x'],['description'=>'x']],
            ['charge',['capture'=>'x'],['capture'=>'x']],
            ['charge',['statement_descriptor'=>'x'],['statement_descriptor'=>'x']],
            ['charge',['receipt_email'=>'x'],['receipt_email'=>'x']],
            ['charge',['application_fee'=>'x'],['application_fee'=>'x']],
            ['charge',['shipping'=>'x'],['shipping'=>'x']],
            ['charge',['source'=>'x'],['source'=>'x']],
            ['charge',['source.object'=>'x'],['source'=>['object'=>'x']]],
            ['charge',['source.number'=>'x'],['source'=>['number'=>'x']]],
            ['charge',['source.exp_month'=>'x'],['source'=>['exp_month'=>'x']]],
            ['charge',['source.exp_year'=>'x'],['source'=>['exp_year'=>'x']]],
            ['charge',['source.cvc'=>'x'],['source'=>['cvc'=>'x']]],
            ['charge',['source.name'=>'x'],['source'=>['name'=>'x']]],
            ['charge',['source.address_line1'=>'x'],['source'=>['address_line1'=>'x']]],
            ['charge',['source.address_line2'=>'x'],['source'=>['address_line2'=>'x']]],
            ['charge',['source.address_city'=>'x'],['source'=>['address_city'=>'x']]],
            ['charge',['source.address_zip'=>'x'],['source'=>['address_zip'=>'x']]],
            ['charge',['source.address_state'=>'x'],['source'=>['address_state'=>'x']]],
            ['charge',['source.address_country'=>'x'],['source'=>['address_country'=>'x']]],
            ['customer',['account_balance'=>'x'],['account_balance'=>'x']],
            ['customer',['coupon'=>'x'],['coupon'=>'x']],
            ['customer',['description'=>'x'],['description'=>'x']],
            ['customer',['email'=>'x'],['email'=>'x']],
            ['customer',['metadata'=>'x'],['metadata'=>'x']],
            ['customer',['plan'=>'x'],['plan'=>'x']],
            ['customer',['quantity'=>'x'],['quantity'=>'x']],
            ['customer',['trial_end'=>'x'],['trial_end'=>'x']],
            ['customer',['source'=>'x'],['source'=>'x']],
            ['customer',['source.object'=>'x'],['source'=>['object'=>'x']]],
            ['customer',['source.number'=>'x'],['source'=>['number'=>'x']]],
            ['customer',['source.exp_month'=>'x'],['source'=>['exp_month'=>'x']]],
            ['customer',['source.exp_year'=>'x'],['source'=>['exp_year'=>'x']]],
            ['customer',['source.cvc'=>'x'],['source'=>['cvc'=>'x']]],
            ['customer',['source.name'=>'x'],['source'=>['name'=>'x']]],
            ['customer',['source.address_line1'=>'x'],['source'=>['address_line1'=>'x']]],
            ['customer',['source.address_line2'=>'x'],['source'=>['address_line2'=>'x']]],
            ['customer',['source.address_city'=>'x'],['source'=>['address_city'=>'x']]],
            ['customer',['source.address_zip'=>'x'],['source'=>['address_zip'=>'x']]],
            ['customer',['source.address_state'=>'x'],['source'=>['address_state'=>'x']]],
            ['customer',['source.address_country'=>'x'],['source'=>['address_country'=>'x']]],
        );
    }

}
