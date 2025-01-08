<?php
return [
    '/' => 'news/home',
    'a-list-of-news' => 'news/home',

    'login' => 'site/login',
    'registration' => 'site/registration-form',
    'send-registration-form' => 'site/registration-send',
    'logout' => 'site/logout',
    
    'fill-in-a-new-news-item' => 'news/a-new-news-item-form',
    'a-look-at-a-specific-news-item/<news_item_id:\d+>' => 'news/news-item',
    'like-news-item' => 'news/like-news-item',
];
