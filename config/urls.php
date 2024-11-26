<?php
return [
    [
        'pattern' => 'create-a-new-news-item',
        'route' => 'news/create',
        'encodeParams' => false, // TODO what does this do?
    ],
    [
        'pattern' => 'a-look-at-a-specific-news-item/<news_item_id:\d+>',
        'route' => 'news', // TODO how to specify news id restriction
        'encodeParams' => true,
    ]
];
