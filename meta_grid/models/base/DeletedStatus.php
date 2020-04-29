<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "deleted_status".
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $fk_object_type_id
 * @property string $name
 * @property string $description
 *
 * @property Contact[] $contacts
 * @property ContactGroup[] $contactGroups
 * @property DataDeliveryObject[] $dataDeliveryObjects
 * @property DataTransferProcess[] $dataTransferProcesses
 * @property DbDatabase[] $dbDatabases
 * @property DbTable[] $dbTables
 * @property DbTableField[] $dbTableFields
 * @property ObjectType $fkObjectType
 * @property Keyfigure[] $keyfigures
 * @property Parameter[] $parameters
 * @property Scheduling[] $schedulings
 * @property Sourcesystem[] $sourcesystems
 */
class DeletedStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'deleted_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid'], 'string'],
            [['fk_object_type_id'], 'integer'],
            [['name'], 'string', 'max' => 250],
            [['description'], 'string', 'max' => 4000],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContacts()
    {
        return $this->hasMany(Contact::className(), ['fk_deleted_status_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContactGroups()
    {
        return $this->hasMany(ContactGroup::className(), ['fk_deleted_status_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDataDeliveryObjects()
    {
        return $this->hasMany(DataDeliveryObject::className(), ['fk_deleted_status_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDataTransferProcesses()
    {
        return $this->hasMany(DataTransferProcess::className(), ['fk_deleted_status_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDbDatabases()
    {
        return $this->hasMany(DbDatabase::className(), ['fk_deleted_status_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDbTables()
    {
        return $this->hasMany(DbTable::className(), ['fk_deleted_status_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDbTableFields()
    {
        return $this->hasMany(DbTableField::className(), ['fk_deleted_status_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkObjectType()
    {
        return $this->hasOne(ObjectType::className(), ['id' => 'fk_object_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKeyfigures()
    {
        return $this->hasMany(Keyfigure::className(), ['fk_deleted_status_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParameters()
    {
        return $this->hasMany(Parameter::className(), ['fk_deleted_status_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchedulings()
    {
        return $this->hasMany(Scheduling::className(), ['fk_deleted_status_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSourcesystems()
    {
        return $this->hasMany(Sourcesystem::className(), ['fk_deleted_status_id' => 'id']);
    }
}
