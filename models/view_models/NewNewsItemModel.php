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
    /** @var UploadedFile name is plural but actually it's single */
    public $files;

    public function rules()
    {
        return [
            // TODO add validation? i wanted tags validation via special validator for both create and search 
            // ['content', ['compareValue' => Yii::getAlias('@max_news_item_content_length')]]
            ['title', 'required'],
            [['title', 'content', 'tags'], 'safe'],
            [['content'], 'validateContent'],
            [['files'], 'file', 'extensions' => 'jpg, jpeg, mp3, mp4, png', 'maxFiles' => 5]
        ];
    }

    public function validateContent($attribute, $params, $validator)
    {
        // $file_labels_count = preg_match_all("\s![a-z]+\.[a-z]+\s", $this->content);
        // TODO uncomment for several files
        // $attached_files_count = sizeof($this->files);
        // the sign < due to you could past one file in several places.
        // TODO check unique file_labels
        // if ($file_labels_count < $attached_files_count) {
        //     $this->addError($attribute, "The count of file labels isn\'t equal to count of attached files. File labels: $file_labels_count. Attached files: $attached_files_count");
        // }
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

    public function formName()
    {
        return '';
    }

    public function handleUpload()
    {
        if (!$this->load(Yii::$app->request->post()))
            return false;
        $this->files = UploadedFile::getInstance($this, 'files');
        if (!$this->validate())
            return false;
        return true;
    }

    public function saveFiles() {
        $dir = Yii::getAlias('@uploads') . "/$this->id";
        mkdir($dir);
        // TODO add foreach
        $this->files->saveAs("$dir/" . $this->files->name);
    }
}
