<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "export_file_db_table_field_result".
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $fk_object_type_id
 * @property integer $fk_client_id
 * @property string $project_name
 * @property string $client_name
 * @property integer $fk_project_id
 * @property string $name
 * @property string $description
 * @property integer $fk_db_table_id
 * @property string $datatype
 * @property string $bulk_load_checksum
 * @property boolean $is_PrimaryKey
 * @property boolean $is_BusinessKey
 * @property boolean $is_GDPR_relevant
 * @property string $databaseInfoFromLocation
 * @property string $db_table_name
 * @property integer $fk_deleted_status_id
 * @property string $deleted_status_name
 * @property string $comments
 * @property string $mappings
 * @property string $session
 * @property integer $_auto_id
 * @property string $_created_datetime
 */
class ExportFileDbTableFieldResult extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'export_file_db_table_field_result';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'fk_object_type_id', 'fk_client_id', 'fk_project_id', 'fk_db_table_id', 'fk_deleted_status_id'], 'integer'],
            [['uuid', 'databaseInfoFromLocation', 'comments', 'mappings'], 'string'],
            [['is_PrimaryKey', 'is_BusinessKey', 'is_GDPR_relevant'], 'boolean'],
            [['_created_datetime'], 'safe'],
            [['project_name', 'client_name', 'name', 'datatype', 'db_table_name', 'deleted_status_name'], 'string', 'max' => 250],
            [['description'], 'string', 'max' => 4000],
            [['bulk_load_checksum', 'session'], 'string', 'max' => 200],
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
            'fk_client_id' => Yii::t('app', 'Fk Client ID'),
            'project_name' => Yii::t('app', 'Project Name'),
            'client_name' => Yii::t('app', 'Client Name'),
            'fk_project_id' => Yii::t('app', 'Fk Project ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'fk_db_table_id' => Yii::t('app', 'Fk Db Table ID'),
            'datatype' => Yii::t('app', 'Datatype'),
            'bulk_load_checksum' => Yii::t('app', 'Bulk Load Checksum'),
            'is_PrimaryKey' => Yii::t('app', 'Is  Primary Key'),
            'is_BusinessKey' => Yii::t('app', 'Is  Business Key'),
            'is_GDPR_relevant' => Yii::t('app', 'Is  Gdpr Relevant'),
            'databaseInfoFromLocation' => Yii::t('app', 'Database Info From Location'),
            'db_table_name' => Yii::t('app', 'Db Table Name'),
            'fk_deleted_status_id' => Yii::t('app', 'Fk Deleted Status ID'),
            'deleted_status_name' => Yii::t('app', 'Deleted Status Name'),
            'comments' => Yii::t('app', 'Comments'),
            'mappings' => Yii::t('app', 'Mappings'),
            'session' => Yii::t('app', 'Session'),
            '_auto_id' => Yii::t('app', 'Auto ID'),
            '_created_datetime' => Yii::t('app', 'Created Datetime'),
        ];
    }
}
