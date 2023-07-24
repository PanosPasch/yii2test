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
            ['id', 'integer'],
            ['id', 'filter', 'filter' => 'intval'],
            // title, text and pretty url are required
            [['title', 'text', 'pretty_url'], 'required'],
            ['category', 'integer'],
            ['category', 'filter', 'filter' => 'intval'],
            ['category', 'default', 'value' => 0],
            //unique
            ['pretty_url', 'unique', 'targetClass' => '\common\models\Page', 'message' => 'This pretty-url is already in use.'],
            ['pretty_url', 'validatePrettyUrl'],
            //image file rules
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg']
        ];
    }

    public function validatePrettyUrl($attribute, $params, $validator){
        $pretty_url_regex = "/^[a-z-]{1,}$/i";
        
        if (!preg_match($pretty_url_regex, $this->$attribute))
        {
            $this->addError($attribute, 'Pretty URL must only contain lowercase letters and hyphens.');
        }
    }

    public function create()
    {

        if (!$this->validate()) {
            return null;
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

    public function edit()
    {

        $old_page = $this->getPageData($this->id);

        $old_page->title = $this->title;
        $old_page->text = $this->text;
        $old_page->pretty_url = $this->pretty_url;
        $old_page->category = $this->category;
        
        if (!$old_page->validate())
        {
            return null;
        }

        return $old_page->save();
        
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
