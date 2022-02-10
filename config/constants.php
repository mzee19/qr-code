<?php

return [
    'payment_methods' => [
        'PAYPAL' => 1,
        'MOLLIE' => 2,
        'ADMIN'  => 3,
        'VOUCHER_PROMOTION' => 4
    ],
    'currency' => [
        'name' => 'Euro',
        'code' => 'Eur',
        'symbol' => '€',
    ],
    'file_size' => '8192',
    'file_size_word' => '8MB',
    'video_file_size' => '102400',
    'product_immunity_url' => 'https://www.productimmunity.com',
    'ned_link_url' => 'https://www.ned.link',  //'http://www.ned.link:8000',//'https://nedlink.arhamsoft.org'
    'move_immunity_url' => 'https://www.moveimmunity.com',
    'transfer_immunity_url' => 'https://www.transferimmunity.com',
    'aikq_url' => 'https://vm24.qdns1.com',
    'inbox_de_url' => 'https://inbox.vm37.qdns1.com',
    'overmail_url' => 'https://overmail.vm37.qdns1.com',
    'maili_de_url' => 'https://maili.vm37.qdns1.com',
    'oddo_url' => 'https://www.timmunity.com',
    'email_marketing' => 'https://emarketing.arhamsoft.info',
    'shape_types' => [
        1 => 'Body Shape',
        2 => 'Eye Frame Shape',
        3 => 'Eye Ball Shape',
        4 => 'Qr Code Frame',
    ],
    'content_types' => [
        1 => 'Dynamic',
        2 => 'Static',
        3 => 'Both',
    ],
    'content_field_types' => [
        'text' => 'Text',
        'number' => 'Number',
        'email' => 'Email',
        'password' => 'Password',
        'textarea' => 'Textarea',
        'select' => 'Select',
        'radio' => 'Radio',
        'checkbox' => 'Checkbox',
        'color' => 'Color',
        'date' => 'Date',
        'tel' => 'Tel',
        'url' => 'Url',
        'file' => 'File',
    ]
];

?>
