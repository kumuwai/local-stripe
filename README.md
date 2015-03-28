LocalStripe
============
[![Build Status](https://img.shields.io/travis/kumuwai/local-stripe/master.svg)](https://travis-ci.org/kumuwai/local-stripe)
[![Coverage Status](https://coveralls.io/repos/kumuwai/local-stripe/badge.png?branch=master)](https://coveralls.io/r/kumuwai/local-stripe)
[![Quality Score](https://img.shields.io/scrutinizer/g/kumuwai/local-stripe.svg)](https://scrutinizer-ci.com/g/kumuwai/local-stripe)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE.md)

This package will download and store your data from Stripe, so you can analyze it and use it in your projects. It can also be used to send payments to Stripe while also saving a local copy. Stripe can accept payments from many different sites; this package will help you get and analyze this data. 


Installation
--------------
This is not yet available as a package on packagist, so if you'd like to install it via composer, you'll need to use a vcs repository. Add this to your composer.json file:

    "require": {
        "kumuwai/local-stripe": "dev-master"
    },
    "repositories": [
        {
            "type": "vcs",
            "url":  "https://github.com/kumuwai/local-stripe.git"
        }
    ],

You will also need to register the service provider in app/config/app.php:

    'Kumuwai\LocalStripe\LocalStripeServiceProvider',

The service provider does not automatically include an alias to the facade. If you would like to use the facade, add an alias to it:

    'LocalStripe'  => 'Kumuwai\LocalStripe\LocalStripeFacade',


Usage
------

```php
$stripe = new LocalStripe;
$stripe->fetch($params);            // Fetch records from the Stripe server; return local objects
$stripe->charge($params);           // Make a simple charge (no customer object, or already-created customer)
$stripe->chargeCustomer($params);   // Charge a customer
$stripe->create($params);           // Create a customer record. Do not charge them at this time.
```

$params is an associative array that can include any Stripe parameter. The parameter will be entered into any object in which it is applicable. To limit that to a specific object, you can use dot notation. 

For fine-grain access to Stripe objects, you can use:

```php
$stripe->remote('customer')         // returns a Stripe\Customer object
$stripe->local('customer')          // returns a LocalStripe\StripeCustomer object
```

Use snake_case to access stripe objects with multiple words in the name. These are all equivalent:

```php
$stripe->remote('application_fee') 
$stripe->remote('application-fee') 
$stripe->remote('ApplicationFee') 
$stripe->remote('applicationFee') 
```

### Examples

Loading all data from Stripe:

```php
$stripe->fetch();
```

Loading data from Stripe since a given time:

```php
$stripe->fetch([
    'created' => ['gte'=> timestamp]
]);
```

Charging some amount to a new customer (given a stripe.js token):

```php
$client = // some local client record
$new = $stripe->chargeCustomer([
    'source' => 'tok_xxx',
    'email' => $client->email,
    'customer.description' => $client->name,
    'customer.metadata' => ['client_id'=>$client->id],
    'name' => $client->name,
    'address_line1' => $client->address->street,
    'address_city' => $client->address->city,
    'address_state' => $client->address->state,
    'address_zip' => $client->address->zip,
    'amount' => 1000,
    'currency' => 'usd',
    'charge.description' => 'charge #1234',
    'charge.statement_descriptor' => 'Company charge #1234',
]);
$client->stripe_customer_id = $new->customer->id;
```

You can use the built-in php server to see what data is on your Stripe test account. To run the server,

    php -S 127.0.0.1:8080 public/index.php


Additional Documentation
-------------------------
Please see the [wiki](https://github.com/kumuwai/local-stripe/wiki) for the latest documentation on this project.

Components include:

    LocalStripe - general container for other classes
    StripeFetcher - fetch data from Stripe
    StripePusher - push data to Stripe, and store it locally

Models:

    StripeBalanceTransaction
    StripeCard
    StripeCharge
    StripeCustomer
    StripeMetadata



TODO
----
  * Post charges to a customer source via intermediate 'stripe_pending_charges'
    * We could still charge a customer when not connected to the internet
    * The charge would be applied when service was reestablished
  * Sync api 
  