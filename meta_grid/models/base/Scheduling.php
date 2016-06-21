<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "scheduling".
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $fk_object_type_id
 * @property integer $fk_project_id
 * @property string $name
 * @property string $description
 * @property integer $fk_tool_id
 * @property string $scheduling_series
 *
 * @property Tool $fkTool
 * @property Project $fkProject
 * @property ObjectType $fkObjectType
 */
class Scheduling extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'scheduling';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid', 'name', 'description', 'scheduling_series'], 'string'],
            [['fk_object_type_id', 'fk_project_id', 'fk_tool_id'], 'integer']
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
            'fk_tool_id' => Yii::t('app', 'Fk Tool ID'),
            'scheduling_series' => Yii::t('app', 'Scheduling Series'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkTool()
    {
        return $this->hasOne(\app\models\Tool::className(), ['id' => 'fk_tool_id']);
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
