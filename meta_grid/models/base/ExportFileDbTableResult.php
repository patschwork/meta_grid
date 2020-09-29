<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "export_file_db_table_result".
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $fk_object_type_id
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
            [['id', 'fk_object_type_id', 'fk_project_id', 'fk_client_id', 'fk_db_table_context_id', 'fk_db_table_type_id', 'fk_deleted_status_id'], 'integer'],
            [['uuid', 'location', 'databaseInfoFromLocation', 'comments', 'mappings'], 'string'],
            [['project_name', 'client_name', 'name', 'db_table_context_name', 'db_table_type_name', 'deleted_status_name'], 'string', 'max' => 250],
            [['description'], 'string', 'max' => 4000],
            [['session'], 'string', 'max' => 200],
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
        ];
    }
}
