<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "object_type".
 *
 * @property integer $id
 * @property string $uuid
 * @property string $name
 *
 * @property Attribute[] $attributes
 * @property Bracket[] $brackets
 * @property Bracket[] $brackets0
 * @property BracketSearchPattern[] $bracketSearchPatterns
 * @property CleanupQueue[] $cleanupQueues
 * @property Contact[] $contacts
 * @property ContactGroup[] $contactGroups
 * @property DataDeliveryObject[] $dataDeliveryObjects
 * @property DataDeliveryObjectOLD[] $dataDeliveryObjectOLDs
 * @property DataTransferProcess[] $dataTransferProcesses
 * @property DatamanagementProcess[] $datamanagementProcesses
 * @property DbDatabase[] $dbDatabases
 * @property DbTable[] $dbTables
 * @property DbTableContext[] $dbTableContexts
 * @property DbTableField[] $dbTableFields
 * @property DeletedStatus[] $deletedStatuses
 * @property Glossary[] $glossaries
 * @property Keyfigure[] $keyfigures
 * @property MapObject2Object[] $mapObject2Objects
 * @property MapObject2Object[] $mapObject2Objects0
 * @property MappingQualifier[] $mappingQualifiers
 * @property ObjectComment[] $objectComments
 * @property ObjectComment[] $objectComments0
 * @property ObjectPersistenceMethod[] $objectPersistenceMethods
 * @property Parameter[] $parameters
 * @property PerspectiveFilter[] $perspectiveFilters
 * @property PerspectiveFilter[] $perspectiveFilters0
 * @property Scheduling[] $schedulings
 * @property Sourcesystem[] $sourcesystems
 * @property Url[] $urls
 */
class ObjectType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'object_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid'], 'string'],
            [['name'], 'string', 'max' => 250],
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
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttributes_meta_grid_table()
    {
        return $this->hasMany(\app\models\Attribute::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrackets()
    {
        return $this->hasMany(\app\models\Bracket::className(), ['fk_object_type_id_as_searchFilter' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrackets0()
    {
        return $this->hasMany(\app\models\Bracket::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBracketSearchPatterns()
    {
        return $this->hasMany(\app\models\BracketSearchPattern::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCleanupQueues()
    {
        return $this->hasMany(\app\models\CleanupQueue::className(), ['ref_fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContacts()
    {
        return $this->hasMany(\app\models\Contact::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContactGroups()
    {
        return $this->hasMany(\app\models\ContactGroup::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDataDeliveryObjects()
    {
        return $this->hasMany(\app\models\DataDeliveryObject::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDataDeliveryObjectOLDs()
    {
        return $this->hasMany(\app\models\DataDeliveryObjectOLD::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDataTransferProcesses()
    {
        return $this->hasMany(\app\models\DataTransferProcess::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDatamanagementProcesses()
    {
        return $this->hasMany(\app\models\DatamanagementProcess::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDbDatabases()
    {
        return $this->hasMany(\app\models\DbDatabase::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDbTables()
    {
        return $this->hasMany(\app\models\DbTable::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDbTableContexts()
    {
        return $this->hasMany(\app\models\DbTableContext::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDbTableFields()
    {
        return $this->hasMany(\app\models\DbTableField::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedStatuses()
    {
        return $this->hasMany(\app\models\DeletedStatus::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGlossaries()
    {
        return $this->hasMany(\app\models\Glossary::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKeyfigures()
    {
        return $this->hasMany(\app\models\Keyfigure::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMapObject2Objects()
    {
        return $this->hasMany(\app\models\MapObject2Object::className(), ['ref_fk_object_type_id_2' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMapObject2Objects0()
    {
        return $this->hasMany(\app\models\MapObject2Object::className(), ['ref_fk_object_type_id_1' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMappingQualifiers()
    {
        return $this->hasMany(\app\models\MappingQualifier::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getObjectComments()
    {
        return $this->hasMany(\app\models\ObjectComment::className(), ['ref_fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getObjectComments0()
    {
        return $this->hasMany(\app\models\ObjectComment::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getObjectPersistenceMethods()
    {
        return $this->hasMany(\app\models\ObjectPersistenceMethod::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParameters()
    {
        return $this->hasMany(\app\models\Parameter::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerspectiveFilters()
    {
        return $this->hasMany(\app\models\PerspectiveFilter::className(), ['ref_fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerspectiveFilters0()
    {
        return $this->hasMany(\app\models\PerspectiveFilter::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchedulings()
    {
        return $this->hasMany(\app\models\Scheduling::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSourcesystems()
    {
        return $this->hasMany(\app\models\Sourcesystem::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUrls()
    {
        return $this->hasMany(\app\models\Url::className(), ['fk_object_type_id' => 'id']);
    }
}
