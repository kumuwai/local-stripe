<?php namespace Kumuwai\LocalStripe;

use Faker\Factory as Faker;
use Illuminate\Support\Arr;


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
        echo 'setting up local stripe' . PHP_EOL;
        $this->stripe = $this->setupLocalStripe();
        echo 'getting fake customer records' . PHP_EOL;
        $new1 = $this->getFakeCustomers(3);
        echo 'submitting data to Stripe' . PHP_EOL;
        $this->directlySubmitCustomersWithChargesToStripe($new1);
        echo 'getting fake customer records' . PHP_EOL;
        $new2 = $this->getFakeCustomers(2);
        echo 'pushing customer records to Stripe' . PHP_EOL;
        $this->pushCustomers($new2);
        echo 'Verifying that data can be fetched from Stripe' . PHP_EOL;
        $this->verifyThatDataCanBeFetched($new1);
    }

    private function setupLocalStripe()
    {
        $this->connector = new Connector;
        $pusher = new Pusher($this->connector);
        $fetcher = new Fetcher($this->connector);
        $this->connector->setApiKey(getenv('STRIPE_SECRET'));
        $stripe = new LocalStripe($this->connector, $pusher, $fetcher);
        return $stripe;
    }

    private function getFakeCustomers($instances)
    {
        $customers = [];
        foreach(range(1,$instances) as $item)
            $customers[] = $this->getFakeCustomerToStripe();

        return $customers;
    }

    private function directlySubmitCustomersWithChargesToStripe($customers)
    {
        foreach($customers as $customer) {
            $data = Arr::except($customer->toArray(), ['sources','charges']);
            $new = $this->connector->remote('customer')->create($data);
            $new->sources->create(['source' => $customer['sources']->toArray()]);
            $customer['charges']['customer'] = $new->id;
            $this->connector->remote('charge')->create($customer['charges']->toArray());
        }
    }

    private function pushCustomers($customers)
    {
        // foreach($customers as $customer) {
        //     $results = $this->stripe->push($data);
        // }
    }

    private function verifyThatDataCanBeFetched($new)
    {
        $fetched = $this->stripe->fetch();

        foreach($new as $item) {
            $customer = $this->connector->local('customer')->where('email', $item['email'])->first();
            $this->assertNotNull($customer);
        }
    }

}
