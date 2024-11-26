<?php
// Q why to use new namespace for each model? wouldn't it be better to use just app\models
// a: to be found by autoloader.
// q: what is it
namespace app\models\OLD_domain;

use DateTime;

class NewsItem
{
    public int $id;
    public string $title;
    public string $content;
    public DateTime $postedAt;
    public int $number_of_likes;
    // relationships
    public int $author_id;
    // TODO many-to-many
    public $tags;

    public function __construct(int $id, string $title, string $content, DateTime $postedAt) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->postedAt = $postedAt;
        $this->number_of_likes = 0;
    }
}
