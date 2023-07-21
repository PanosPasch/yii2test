<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use common\models\Page;

/**
 * Login form
 */
class CreatePage extends Model
{
    public $id;
    public $title;
    public $text;
    public $pretty_url;
    public $category;
    public $image;
    public $user_id;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // title, text and pretty url are required
            [['title', 'text', 'pretty_url'], 'required'],
            ['category', 'integer'],
            ['category', 'filter', 'filter' => 'intval'],
            ['category', 'default', 'value' => 0],
            //unique
            ['pretty_url', 'unique', 'targetClass' => '\common\models\Page', 'message' => 'This pretty-url is already in use.'],
            //image file rules
            [['image'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg']
        ];
    }

    public function create()
    {
        if (!$this->validate()) {
            return null;
        }
        
        if ($old_page = $this->id)
        {
            $this->image->saveAs('uploads/'.$this->pretty_url.'_'.$this->image->baseName.'.'.$this->image->extension);
            $old_page->title = $this->title;
            $old_page->text = $this->text;
            $old_page->pretty_url = $this->pretty_url;
            $old_page->category = $this->category;
            $old_page->image = $this->pretty_url.'_'.$this->image->baseName.'.'.$this->image->extension;
            return $old_page->save();
        }
        $this->image->saveAs('uploads/'.$this->pretty_url.'_'.$this->image->baseName.'.'.$this->image->extension);
        $page = new Page();
        $page->title = $this->title;
        $page->text = $this->text;
        $page->pretty_url = $this->pretty_url;
        $page->category = $this->category;
        $page->image = $this->pretty_url.'_'.$this->image->baseName.'.'.$this->image->extension;
        $page->user_id = Yii::$app->user->getId();
        
        return $page->save();
    }

    public function list()
    {
        return $pages = Page::findAll(["user_id" => Yii::$app->user->getId()]);
    }

    public function delete($id)
    {
        if ($page = $this->getPageData($id))
        {
            return $page->delete();
        }
        return false;
    }

    public function getPageData($id)
    {
        if (Page::findIdentity($id)->user_id === Yii::$app->user->getId())
        {
            $page = Page::findIdentity($id);
            return $page;
        }
        return false;
    }

}
