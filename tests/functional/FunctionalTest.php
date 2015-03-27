<?php namespace Kumuwai\LocalStripe;

use Dotenv;


/**
 * This test works on data in conjunction with stripe.com.
 * In order to run it, you must have a STRIPE_SECRET 
 * environment variable set. It is not run under CI
 *  
 * @group functional
 */
class FunctionalTest extends TestCase
{
    private $stripe;
    private $connector;


    public function testSubmitAndReadStripeData()
    {
        $this->write('setting up local stripe');
        $this->stripe = $this->setupLocalStripe();
        // $this->write('deleting existing customers');
        // $this->deleteAllCustomers();

        $this->write('getting fake customer records');
        $new1 = $this->getFakeCustomers(3);
        $this->write('submitting data to Stripe');
        $this->directlySubmitCustomersWithChargesToStripe($new1);

        $this->write('getting fake customer records');
        $new2 = $this->getFakeCustomers(2);
        $this->write('pushing customer records to Stripe');
        $this->pushCustomerRecords($new2);

        $this->write('Verifying that data can be fetched from Stripe');
        $this->verifyThatDataCanBeFetched($new1);
        $this->verifyThatDataCanBeFetched($new2);
    }

    private function write($string) {
        fwrite(STDERR, $string . PHP_EOL);
    }    

    private function setupLocalStripe()
    {
        Dotenv::load(__DIR__.'/../../');

        $this->connector = new Connector;
        $pusher = new Pusher($this->connector, new ParameterParser);
        $fetcher = new Fetcher($this->connector);
        $this->connector->setApiKey(getenv('STRIPE_SECRET'));
        $stripe = new LocalStripe($this->connector, $pusher, $fetcher);
        return $stripe;
    }

    private function deleteAllCustomers()
    {
        $customers = $this->connector->remote('customer')->all();
        foreach($customers as $customer) {
            $this->write("deleting {$customer->id} ({$customer->email})");
            $customer->delete();
        }
        $this->write('all customers deleted');
    }

    private function getFakeCustomers($instances)
    {
        $customers = [];
        foreach(range(1,$instances) as $item) {   
            $customer = $this->getFakeCustomerToStripe();
            $this->write("created customer {$customer['email']}");
            $customers[] = [
                'customer' => $customer,
                'card' => $this->getFakeCardToStripe(),
                'charge' => $this->getFakeChargeToStripe(),
            ];
        }

        return $customers;
    }

    private function directlySubmitCustomersWithChargesToStripe($customerData)
    {
        foreach($customerData as $component) {
            $data = $component['customer'];
            $new = $this->connector->remote('customer')->create($data);
            $new->sources->create($component['card']);
            $component['charge']['customer'] = $new->id;
            $this->connector->remote('charge')->create($component['charge']);
        }
    }

    private function pushCustomerRecords($customers)
    {
        foreach($customers as $customer) {
            $data = $this->array_flatten($customer);
            $result = $this->stripe->chargeCustomer($data);
        }
    }

    private function array_flatten(array $array)
    {
        $return = [];
        foreach($array as $key=>$value) {
            if (is_array($value))
                $return = array_merge($return, $this->array_flatten($value));
            else
                $return[$key] = $value;
        }
        return $return;
    }

    private function verifyThatDataCanBeFetched($new)
    {
        $fetched = $this->stripe->fetch();

        foreach($new as $item) {
            $this->write("verifying customer {$item['customer']['email']} is stored locally...");
            $customer = $this->connector->local('customer')
                ->where('email', $item['customer']['email'])->first();
            $this->assertNotNull($customer);
            $this->write("customer {$customer->id} found");
        }
    }

}
