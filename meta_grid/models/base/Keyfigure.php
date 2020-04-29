<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "keyfigure".
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $fk_object_type_id
 * @property integer $fk_project_id
 * @property string $name
 * @property string $description
 * @property string $formula
 * @property string $aggregation
 * @property string $character
 * @property string $type
 * @property string $unit
 * @property string $value_range
 * @property boolean $cumulation_possible
 * @property integer $fk_deleted_status_id
 *
 * @property DeletedStatus $fkDeletedStatus
 * @property Project $fkProject
 * @property ObjectType $fkObjectType
 */
class Keyfigure extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'keyfigure';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid'], 'string'],
            [['fk_object_type_id', 'fk_project_id', 'fk_deleted_status_id'], 'integer'],
            [['cumulation_possible'], 'boolean'],
            [['name'], 'string', 'max' => 250],
            [['description', 'formula'], 'string', 'max' => 4000],
            [['aggregation', 'character', 'type', 'unit', 'value_range'], 'string', 'max' => 500],
            [['fk_deleted_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => DeletedStatus::className(), 'targetAttribute' => ['fk_deleted_status_id' => 'id']],
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
            'formula' => Yii::t('app', 'Formula'),
            'aggregation' => Yii::t('app', 'Aggregation'),
            'character' => Yii::t('app', 'Character'),
            'type' => Yii::t('app', 'Type'),
            'unit' => Yii::t('app', 'Unit'),
            'value_range' => Yii::t('app', 'Value Range'),
            'cumulation_possible' => Yii::t('app', 'Cumulation Possible'),
            'fk_deleted_status_id' => Yii::t('app', 'Fk Deleted Status ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkDeletedStatus()
    {
        return $this->hasOne(\app\models\DeletedStatus::className(), ['id' => 'fk_deleted_status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkProject()
    {
        return $this->hasOne(\app\models\Project::className(), ['id' => 'fk_project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkObjectType()
    {
        return $this->hasOne(\app\models\ObjectType::className(), ['id' => 'fk_object_type_id']);
    }
}
