<?php

namespace app\models\view_models;

use yii\base\Model;
use Yii;
use yii\web\UploadedFile;
use yii\rest\Serializer;

class NewNewsItemModel extends Model
{
    public $title;
    public $content;
    public $tags;

    // TODO must be several files
    /** @var UploadedFile */
    public $files;

    public function rules()
    {
        return [
            // TODO add validation? i wanted tags validation via special validator for both create and search 
            // ['content', ['compareValue' => Yii::getAlias('@max_news_item_content_length')]]
            ['title', 'required'],
            [['files'], 'file', 'extensions' => 'jpg, jpeg, mp3, mp4, txt', 'maxFiles' => 5]
        ];
    }

    // public function test() {
    //     $serializer = new Serializer();
    //     $serializer->fileatime()
    // }

    // public function upload() {
    //     if ($this->validate()) {
    //         foreach($this->files as $file) {
    //             $file->saveAs()
    //         }
    //     }
    // }

    public function formName() {
        return '';
    }
}
