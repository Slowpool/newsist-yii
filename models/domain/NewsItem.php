<?php

namespace app\models\newsitem;

use DateTime;

class NewsItem {
    public int $id;
    public string $title;
    public string $content;
    public DateTime $postedAt;
    // relationships
    public int $author_id;
    public

}

?>