<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "url".
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $fk_object_type_id
 * @property integer $fk_project_id
 * @property string $name
 * @property string $description
 * @property string $url
 *
 * @property Project $fkProject
 * @property ObjectType $fkObjectType
 */
class Url extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'url';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid'], 'string'],
            [['fk_object_type_id', 'fk_project_id'], 'integer'],
            [['name'], 'string', 'max' => 250],
            [['description', 'url'], 'string', 'max' => 4000],
            [['fk_project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['fk_project_id' => 'id']],
            [['fk_object_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ObjectType::className(), 'targetAttribute' => ['fk_object_type_id' => 'id']],
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
            'fk_object_type_id' => Yii::t('app', 'Fk Object Type ID'),
            'fk_project_id' => Yii::t('app', 'Fk Project ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'url' => Yii::t('app', 'Url'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'fk_project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkObjectType()
    {
        return $this->hasOne(ObjectType::className(), ['id' => 'fk_object_type_id']);
    }
}
