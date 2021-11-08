<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "import_stage_db_table".
 *
 * @property integer $id
 * @property string $client_name
 * @property string $project_name
 * @property string $db_table_name
 * @property string $db_table_description
 * @property string $db_table_field_name
 * @property string $db_table_field_datatype
 * @property string $db_table_field_description
 * @property string $db_table_type_name
 * @property string $db_table_context_name
 * @property string $db_table_context_prefix
 * @property boolean $isPrimaryKeyField
 * @property boolean $isForeignKeyField
 * @property string $foreignKey_table_name
 * @property string $foreignKey_table_field_name
 * @property integer $_import_state
 * @property string $_import_date
 * @property boolean $is_BusinessKey
 * @property boolean $is_GDPR_relevant
 * @property string $location
 * @property string $database_or_catalog
 * @property string $schema
 * @property integer $fk_project_id
 * @property integer $fk_db_database_id
 * @property string $column_default_value
 * @property boolean $column_cant_be_null
 * @property string $additional_field_1
 * @property string $additional_field_2
 * @property string $additional_field_3
 * @property string $additional_field_4
 * @property string $additional_field_5
 * @property string $additional_field_6
 * @property string $additional_field_7
 * @property string $additional_field_8
 * @property string $additional_field_9
 */
class ImportStageDbTable extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'import_stage_db_table';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_name', 'project_name', 'db_table_name', 'db_table_description', 'db_table_field_name', 'db_table_field_datatype', 'db_table_field_description', 'db_table_type_name', 'db_table_context_name', 'db_table_context_prefix', 'foreignKey_table_name', 'foreignKey_table_field_name', '_import_date', 'location'], 'string'],
            [['isPrimaryKeyField', 'isForeignKeyField', 'is_BusinessKey', 'is_GDPR_relevant', 'column_cant_be_null'], 'boolean'],
            [['_import_state', 'fk_project_id', 'fk_db_database_id'], 'integer'],
            [['database_or_catalog', 'column_default_value'], 'string', 'max' => 1000],
            [['schema', 'additional_field_1', 'additional_field_2', 'additional_field_3', 'additional_field_4', 'additional_field_5', 'additional_field_6', 'additional_field_7', 'additional_field_8', 'additional_field_9'], 'string', 'max' => 4000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'client_name' => Yii::t('app', 'Client Name'),
            'project_name' => Yii::t('app', 'Project Name'),
            'db_table_name' => Yii::t('app', 'Db Table Name'),
            'db_table_description' => Yii::t('app', 'Db Table Description'),
            'db_table_field_name' => Yii::t('app', 'Db Table Field Name'),
            'db_table_field_datatype' => Yii::t('app', 'Db Table Field Datatype'),
            'db_table_field_description' => Yii::t('app', 'Db Table Field Description'),
            'db_table_type_name' => Yii::t('app', 'Db Table Type Name'),
            'db_table_context_name' => Yii::t('app', 'Db Table Context Name'),
            'db_table_context_prefix' => Yii::t('app', 'Db Table Context Prefix'),
            'isPrimaryKeyField' => Yii::t('app', 'Is Primary Key Field'),
            'isForeignKeyField' => Yii::t('app', 'Is Foreign Key Field'),
            'foreignKey_table_name' => Yii::t('app', 'Foreign Key Table Name'),
            'foreignKey_table_field_name' => Yii::t('app', 'Foreign Key Table Field Name'),
            '_import_state' => Yii::t('app', 'Import State'),
            '_import_date' => Yii::t('app', 'Import Date'),
            'is_BusinessKey' => Yii::t('app', 'Is  Business Key'),
            'is_GDPR_relevant' => Yii::t('app', 'Is  Gdpr Relevant'),
            'location' => Yii::t('app', 'Location'),
            'database_or_catalog' => Yii::t('app', 'Database Or Catalog'),
            'schema' => Yii::t('app', 'Schema'),
            'fk_project_id' => Yii::t('app', 'Fk Project ID'),
            'fk_db_database_id' => Yii::t('app', 'Fk Db Database ID'),
            'column_default_value' => Yii::t('app', 'Column Default Value'),
            'column_cant_be_null' => Yii::t('app', 'Column Cant Be Null'),
            'additional_field_1' => Yii::t('app', 'Additional Field 1'),
            'additional_field_2' => Yii::t('app', 'Additional Field 2'),
            'additional_field_3' => Yii::t('app', 'Additional Field 3'),
            'additional_field_4' => Yii::t('app', 'Additional Field 4'),
            'additional_field_5' => Yii::t('app', 'Additional Field 5'),
            'additional_field_6' => Yii::t('app', 'Additional Field 6'),
            'additional_field_7' => Yii::t('app', 'Additional Field 7'),
            'additional_field_8' => Yii::t('app', 'Additional Field 8'),
            'additional_field_9' => Yii::t('app', 'Additional Field 9'),
        ];
    }
}
