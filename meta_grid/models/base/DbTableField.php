<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "db_table_field".
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $fk_object_type_id
 * @property integer $fk_project_id
 * @property string $name
 * @property string $description
 * @property integer $fk_db_table_id
 * @property string $datatype
 * @property string $bulk_load_checksum
 * @property integer $fk_deleted_status_id
 * @property boolean $is_PrimaryKey
 * @property boolean $is_BusinessKey
 * @property boolean $is_GDPR_relevant
 * @property integer $fk_object_persistence_method_id
 * @property integer $fk_datamanagement_process_id
 * @property string $source_definition
 * @property string $source_comment
 *
 * @property DatamanagementProcess $fkDatamanagementProcess
 * @property ObjectPersistenceMethod $fkObjectPersistenceMethod
 * @property DeletedStatus $fkDeletedStatus
 * @property DbTable $fkDbTable
 * @property Project $fkProject
 * @property ObjectType $fkObjectType
 */
class DbTableField extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'db_table_field';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid', 'source_definition', 'source_comment'], 'string'],
            [['fk_object_type_id', 'fk_project_id', 'fk_db_table_id', 'fk_deleted_status_id', 'fk_object_persistence_method_id', 'fk_datamanagement_process_id'], 'integer'],
            [['is_PrimaryKey', 'is_BusinessKey', 'is_GDPR_relevant'], 'boolean'],
            [['name', 'datatype'], 'string', 'max' => 250],
            [['description'], 'string', 'max' => 500],
            [['bulk_load_checksum'], 'string', 'max' => 200],
            [['fk_datamanagement_process_id'], 'exist', 'skipOnError' => true, 'targetClass' => DatamanagementProcess::className(), 'targetAttribute' => ['fk_datamanagement_process_id' => 'id']],
            [['fk_object_persistence_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => ObjectPersistenceMethod::className(), 'targetAttribute' => ['fk_object_persistence_method_id' => 'id']],
            [['fk_deleted_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => DeletedStatus::className(), 'targetAttribute' => ['fk_deleted_status_id' => 'id']],
            [['fk_db_table_id'], 'exist', 'skipOnError' => true, 'targetClass' => DbTable::className(), 'targetAttribute' => ['fk_db_table_id' => 'id']],
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
            'fk_db_table_id' => Yii::t('app', 'Fk Db Table ID'),
            'datatype' => Yii::t('app', 'Datatype'),
            'bulk_load_checksum' => Yii::t('app', 'Bulk Load Checksum'),
            'fk_deleted_status_id' => Yii::t('app', 'Fk Deleted Status ID'),
            'is_PrimaryKey' => Yii::t('app', 'Is  Primary Key'),
            'is_BusinessKey' => Yii::t('app', 'Is  Business Key'),
            'is_GDPR_relevant' => Yii::t('app', 'Is  Gdpr Relevant'),
            'fk_object_persistence_method_id' => Yii::t('app', 'Fk Object Persistence Method ID'),
            'fk_datamanagement_process_id' => Yii::t('app', 'Fk Datamanagement Process ID'),
            'source_definition' => Yii::t('app', 'Source Definition'),
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
    public function getFkDbTable()
    {
        return $this->hasOne(\app\models\DbTable::className(), ['id' => 'fk_db_table_id']);
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
