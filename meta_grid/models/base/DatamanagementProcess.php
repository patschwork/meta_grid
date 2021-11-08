<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "datamanagement_process".
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $fk_object_type_id
 * @property string $name
 * @property string $description
 * @property string $tool
 * @property string $tool_version
 * @property string $routine
 *
 * @property Attribute[] $attributes
 * @property Bracket[] $brackets
 * @property BracketSearchPattern[] $bracketSearchPatterns
 * @property Client[] $clients
 * @property Contact[] $contacts
 * @property ContactGroup[] $contactGroups
 * @property DataDeliveryObject[] $dataDeliveryObjects
 * @property DataDeliveryType[] $dataDeliveryTypes
 * @property DataTransferProcess[] $dataTransferProcesses
 * @property DataTransferType[] $dataTransferTypes
 * @property ObjectType $fkObjectType
 * @property DbDatabase[] $dbDatabases
 * @property DbTable[] $dbTables
 * @property DbTableContext[] $dbTableContexts
 * @property DbTableField[] $dbTableFields
 * @property DbTableType[] $dbTableTypes
 * @property DeletedStatus[] $deletedStatuses
 * @property ExportFileDbTableParams[] $exportFileDbTableParams
 * @property ExportFileDbTableQueue[] $exportFileDbTableQueues
 * @property ExportFileDbTableResult[] $exportFileDbTableResults
 * @property Glossary[] $glossaries
 * @property Keyfigure[] $keyfigures
 * @property MapObject2Object[] $mapObject2Objects
 * @property MappingQualifier[] $mappingQualifiers
 * @property ObjectComment[] $objectComments
 * @property ObjectDependsOn[] $objectDependsOns
 * @property Parameter[] $parameters
 * @property PerspectiveFilter[] $perspectiveFilters
 * @property Project[] $projects
 * @property Scheduling[] $schedulings
 * @property Sourcesystem[] $sourcesystems
 * @property Tool[] $tools
 * @property ToolType[] $toolTypes
 * @property Url[] $urls
 */
class DatamanagementProcess extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'datamanagement_process';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid'], 'string'],
            [['fk_object_type_id'], 'integer'],
            [['name', 'tool_version'], 'string', 'max' => 250],
            [['description'], 'string', 'max' => 4000],
            [['tool', 'routine'], 'string', 'max' => 500],
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
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'tool' => Yii::t('app', 'Tool'),
            'tool_version' => Yii::t('app', 'Tool Version'),
            'routine' => Yii::t('app', 'Routine'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttributes_meta_grid_table()
    {
        return $this->hasMany(\app\models\Attribute::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrackets()
    {
        return $this->hasMany(\app\models\Bracket::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBracketSearchPatterns()
    {
        return $this->hasMany(\app\models\BracketSearchPattern::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClients()
    {
        return $this->hasMany(\app\models\Client::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContacts()
    {
        return $this->hasMany(\app\models\Contact::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContactGroups()
    {
        return $this->hasMany(\app\models\ContactGroup::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDataDeliveryObjects()
    {
        return $this->hasMany(\app\models\DataDeliveryObject::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDataDeliveryTypes()
    {
        return $this->hasMany(\app\models\DataDeliveryType::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDataTransferProcesses()
    {
        return $this->hasMany(\app\models\DataTransferProcess::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDataTransferTypes()
    {
        return $this->hasMany(\app\models\DataTransferType::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkObjectType()
    {
        return $this->hasOne(\app\models\ObjectType::className(), ['id' => 'fk_object_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDbDatabases()
    {
        return $this->hasMany(\app\models\DbDatabase::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDbTables()
    {
        return $this->hasMany(\app\models\DbTable::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDbTableContexts()
    {
        return $this->hasMany(\app\models\DbTableContext::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDbTableFields()
    {
        return $this->hasMany(\app\models\DbTableField::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDbTableTypes()
    {
        return $this->hasMany(\app\models\DbTableType::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedStatuses()
    {
        return $this->hasMany(\app\models\DeletedStatus::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExportFileDbTableParams()
    {
        return $this->hasMany(\app\models\ExportFileDbTableParams::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExportFileDbTableQueues()
    {
        return $this->hasMany(\app\models\ExportFileDbTableQueue::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExportFileDbTableResults()
    {
        return $this->hasMany(\app\models\ExportFileDbTableResult::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGlossaries()
    {
        return $this->hasMany(\app\models\Glossary::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKeyfigures()
    {
        return $this->hasMany(\app\models\Keyfigure::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMapObject2Objects()
    {
        return $this->hasMany(\app\models\MapObject2Object::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMappingQualifiers()
    {
        return $this->hasMany(\app\models\MappingQualifier::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getObjectComments()
    {
        return $this->hasMany(\app\models\ObjectComment::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getObjectDependsOns()
    {
        return $this->hasMany(\app\models\ObjectDependsOn::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParameters()
    {
        return $this->hasMany(\app\models\Parameter::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerspectiveFilters()
    {
        return $this->hasMany(\app\models\PerspectiveFilter::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjects()
    {
        return $this->hasMany(\app\models\Project::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchedulings()
    {
        return $this->hasMany(\app\models\Scheduling::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSourcesystems()
    {
        return $this->hasMany(\app\models\Sourcesystem::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTools()
    {
        return $this->hasMany(\app\models\Tool::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToolTypes()
    {
        return $this->hasMany(\app\models\ToolType::className(), ['fk_datamanagement_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUrls()
    {
        return $this->hasMany(\app\models\Url::className(), ['fk_datamanagement_process_id' => 'id']);
    }
}
