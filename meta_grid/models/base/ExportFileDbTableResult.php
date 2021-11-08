<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "export_file_db_table_result".
 *
 * @property integer $id
 * @property string $uuid
* @property integer $fk_object_type_id
 * @property integer $fk_project_id
 * @property integer $fk_client_id
 * @property string $project_name
 * @property string $client_name
 * @property string $name
 * @property string $description
 * @property string $location
 * @property integer $fk_db_table_context_id
 * @property string $db_table_context_name
 * @property integer $fk_db_table_type_id
 * @property string $db_table_type_name
 * @property integer $fk_deleted_status_id
 * @property string $deleted_status_name
 * @property string $databaseInfoFromLocation
 * @property string $comments
 * @property string $mappings
 * @property string $session
 * @property integer $_auto_id
 * @property string $_created_datetime
 * @property integer $fk_object_persistence_method_id
 * @property integer $fk_datamanagement_process_id
 *
 * @property DatamanagementProcess $fkDatamanagementProcess
 * @property ObjectPersistenceMethod $fkObjectPersistenceMethod
 */
class ExportFileDbTableResult extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'export_file_db_table_result';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'fk_object_type_id', 'fk_project_id', 'fk_client_id', 'fk_db_table_context_id', 'fk_db_table_type_id', 'fk_deleted_status_id', 'fk_object_persistence_method_id', 'fk_datamanagement_process_id'], 'integer'],
            [['uuid', 'location', 'databaseInfoFromLocation', 'comments', 'mappings'], 'string'],
            [['_created_datetime'], 'safe'],
            [['project_name', 'client_name', 'name', 'db_table_context_name', 'db_table_type_name', 'deleted_status_name'], 'string', 'max' => 250],
            [['description'], 'string', 'max' => 4000],
            [['session'], 'string', 'max' => 200],
            [['fk_datamanagement_process_id'], 'exist', 'skipOnError' => true, 'targetClass' => DatamanagementProcess::className(), 'targetAttribute' => ['fk_datamanagement_process_id' => 'id']],
            [['fk_object_persistence_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => ObjectPersistenceMethod::className(), 'targetAttribute' => ['fk_object_persistence_method_id' => 'id']],
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
            'fk_client_id' => Yii::t('app', 'Fk Client ID'),
            'project_name' => Yii::t('app', 'Project Name'),
            'client_name' => Yii::t('app', 'Client Name'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'location' => Yii::t('app', 'Location'),
            'fk_db_table_context_id' => Yii::t('app', 'Fk Db Table Context ID'),
            'db_table_context_name' => Yii::t('app', 'Db Table Context Name'),
            'fk_db_table_type_id' => Yii::t('app', 'Fk Db Table Type ID'),
            'db_table_type_name' => Yii::t('app', 'Db Table Type Name'),
            'fk_deleted_status_id' => Yii::t('app', 'Fk Deleted Status ID'),
            'deleted_status_name' => Yii::t('app', 'Deleted Status Name'),
            'databaseInfoFromLocation' => Yii::t('app', 'Database Info From Location'),
            'comments' => Yii::t('app', 'Comments'),
            'mappings' => Yii::t('app', 'Mappings'),
            'session' => Yii::t('app', 'Session'),
            '_auto_id' => Yii::t('app', 'Auto ID'),
            '_created_datetime' => Yii::t('app', 'Created Datetime'),
            'fk_object_persistence_method_id' => Yii::t('app', 'Fk Object Persistence Method ID'),
            'fk_datamanagement_process_id' => Yii::t('app', 'Fk Datamanagement Process ID'),
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
}
