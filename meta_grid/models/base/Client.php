<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "client".
 *
 * @property integer $id
 * @property string $uuid
 * @property string $name
 * @property string $description
 * @property integer $fk_object_persistence_method_id
 * @property integer $fk_datamanagement_process_id
 *
 * @property DatamanagementProcess $fkDatamanagementProcess
 * @property ObjectPersistenceMethod $fkObjectPersistenceMethod
 * @property Contact[] $contacts
 * @property ContactGroup[] $contactGroups
 * @property Project[] $projects
 */
class Client extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'client';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid'], 'string'],
            [['fk_object_persistence_method_id', 'fk_datamanagement_process_id'], 'integer'],
            [['name'], 'string', 'max' => 250],
            [['description'], 'string', 'max' => 4000],
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
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContacts()
    {
        return $this->hasMany(\app\models\Contact::className(), ['fk_client_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContactGroups()
    {
        return $this->hasMany(\app\models\ContactGroup::className(), ['fk_client_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjects()
    {
        return $this->hasMany(\app\models\Project::className(), ['fk_client_id' => 'id']);
    }
}
