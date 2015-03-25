<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stripe Data</title>
</head>
<body>

<?php
require_once dirname(__DIR__) . '/public/bootstrap.php';
Dotenv::load(__DIR__.'/../');

use Kumuwai\LocalStripe\Connector;
use Kumuwai\LocalStripe\Fetcher;
use Kumuwai\LocalStripe\Models\StripeCard;
use Kumuwai\LocalStripe\Models\StripeCharge;
use Kumuwai\LocalStripe\Models\StripeMetadata;
use Kumuwai\LocalStripe\Models\StripeBalanceTransaction;

$connector = new Connector;
$connector->setApiKey(getenv('STRIPE_SECRET'));
$fetcher = new Fetcher($connector);

$found = $fetcher->fetch();

echo('<h1>Customers</h1>');
foreach($found['customers'] as $customer) {
    echo ("<h3>Customer: {$customer->id}</h3>");
    var_dump($customer->toArray());
    foreach(['cards','charges','metadata'] as $data) {
        echo("<h3>{$data}:</h3>");
        foreach($customer->$data as $item)
            var_dump($item->toArray());
    }
}

// echo('<h1>Charges</h1>');
// foreach($found['charges'] as $charge)
//     var_dump($charge->toArray());

// echo('<h1>Charges (from db)</h1>');
// $charges = StripeCharge::all();
// foreach($charges as $charge)
//     var_dump($charge->toArray());

// echo('<h1>Cards (from db)</h1>');
// $cards = StripeCard::all();
// foreach($cards as $card)
//     var_dump($card->toArray());

// echo('<h1>Metadata (from db)</h1>');
// $meta = StripeMetadata::all();
// foreach($meta as $item)
//     var_dump($item->toArray());

// echo('<h1>Balance Transactions (from db)</h1>');
// $tr = StripeBalanceTransaction::all();
// foreach($tr as $item)
//     var_dump($item->toArray());

?>
    
</body>
</html>

