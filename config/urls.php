<?php
return [
    [
        'pattern' => '/create-a-new-news-item',
        'route' => '/news/create'
        // 'encodeParams' => false, // TODO what does this do?
    ],
    [
        'pattern' => '/a-look-at-a-specific-news-item',
        'route' => '/news/<news.*>' // TODO how to specify news id restriction
    ]
];
