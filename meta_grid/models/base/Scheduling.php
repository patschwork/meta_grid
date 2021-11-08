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
 * @property integer $fk_deleted_status_id
 * @property integer $fk_object_persistence_method_id
 * @property integer $fk_datamanagement_process_id
 * @property string $source_definition
 * @property string $source_definition_language
 * @property string $source_comment
 *
 * @property DatamanagementProcess $fkDatamanagementProcess
 * @property ObjectPersistenceMethod $fkObjectPersistenceMethod
 * @property DeletedStatus $fkDeletedStatus
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
            [['uuid', 'name', 'description', 'scheduling_series', 'source_definition', 'source_comment'], 'string'],
            [['fk_object_type_id', 'fk_project_id', 'fk_tool_id', 'fk_deleted_status_id', 'fk_object_persistence_method_id', 'fk_datamanagement_process_id'], 'integer'],
            [['source_definition_language'], 'string', 'max' => 250],
            [['fk_datamanagement_process_id'], 'exist', 'skipOnError' => true, 'targetClass' => DatamanagementProcess::className(), 'targetAttribute' => ['fk_datamanagement_process_id' => 'id']],
            [['fk_object_persistence_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => ObjectPersistenceMethod::className(), 'targetAttribute' => ['fk_object_persistence_method_id' => 'id']],
            [['fk_deleted_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => DeletedStatus::className(), 'targetAttribute' => ['fk_deleted_status_id' => 'id']],
            [['fk_tool_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tool::className(), 'targetAttribute' => ['fk_tool_id' => 'id']],
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
            'fk_tool_id' => Yii::t('app', 'Fk Tool ID'),
            'scheduling_series' => Yii::t('app', 'Scheduling Series'),
            'fk_deleted_status_id' => Yii::t('app', 'Fk Deleted Status ID'),
            'fk_object_persistence_method_id' => Yii::t('app', 'Fk Object Persistence Method ID'),
            'fk_datamanagement_process_id' => Yii::t('app', 'Fk Datamanagement Process ID'),
            'source_definition' => Yii::t('app', 'Source Definition'),
            'source_definition_language' => Yii::t('app', 'Source Definition Language'),
            'source_comment' => Yii::t('app', 'Source Comment'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkDatamanagementProcess()
    {
        return $this->hasOne(\app\models\DatamanagementProcess::className(), ['id' => 'fk_datamanagement_process_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkObjectPersistenceMethod()
    {
        return $this->hasOne(\app\models\ObjectPersistenceMethod::className(), ['id' => 'fk_object_persistence_method_id']);
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
