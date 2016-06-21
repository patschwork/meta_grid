<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "tool_type".
 *
 * @property integer $id
 * @property string $uuid
 * @property string $name
 * @property string $description
 *
 * @property Tool[] $tools
 */
class ToolType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tool_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid'], 'string'],
            [['name'], 'string', 'max' => 250],
            [['description'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'uuid' => Yii::t('app', 'Uuid'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTools()
    {
        return $this->hasMany(\app\models\Tool::className(), ['fk_tool_type_id' => 'id']);
    }
}
