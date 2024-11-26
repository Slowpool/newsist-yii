<?php
return [
    [
        'pattern' => '/',
        'route' => 'news/home',
        'encodeParams' => false,
    ],
    [
        'pattern' => 'list-of-news',
        'route' => 'news/home',
        'encodeParams' => false,
    ],
    [
        'pattern' => 'create-a-new-news-item',
        'route' => 'news/create',
        'encodeParams' => false,
    ],
    [
        'pattern' => 'a-look-at-a-specific-news-item/<news_item_id:\d+>',
        'route' => 'news/newsitem', // TODO how to specify news id restriction
        'encodeParams' => false
    ]
];
