<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Page model
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property string $pretty_url
 * @property string $image
 * @property integer $category
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class Page extends ActiveRecord
{
    public const CATEGORY = [
        0 => 'Default',
        1 => 'New Category 1',
        2 => 'New Category 2',
        3 => 'New Category 3',
        4 => 'New Category 4',
        5 => 'New Category 5'
    ];


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%page}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['category', 'default', 'value' => 0],
            ['category', 'in', 'range' => [0, 1, 2, 3, 4, 5]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findPagesByOwner($user_id)
    {
        return static::findAll(['user_id' => $user_id]);
    }

    /**
     * Finds pages by pretty url
     *
     * @param string $pretty_url
     * @return static|null
     */
    public static function findByPrettyUrl($pretty_url)
    {
        return static::findOne(['pretty_url' => $pretty_url]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

}
