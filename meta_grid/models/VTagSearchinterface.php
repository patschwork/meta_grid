<?php

namespace app\models;

use Yii;

class VTagSearchinterface extends \app\models\Tag
{
     /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tag';
    }
    public static function primaryKey()
    {
        return array('id');
    }
}
