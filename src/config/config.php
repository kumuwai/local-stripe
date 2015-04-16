<?php

return array(

    'keys' => [
        'secret' => getenv('STRIPE_SECRET'),
        'public' => getenv('STRIPE_PUBLIC'),
    ],

);
