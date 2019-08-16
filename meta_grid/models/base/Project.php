<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "project".
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $fk_client_id
 * @property string $name
 * @property string $description
 *
 * @property Keyfigure[] $keyfigures
 * @property Client $fkClient
 * @property Attribute[] $attributes
 * @property DbTable[] $dbTables
 * @property DbTableField[] $dbTableFields
 * @property DbTableContext[] $dbTableContexts
 * @property DbDatabase[] $dbDatabases
 * @property Scheduling[] $schedulings
 * @property Parameter[] $parameters
 * @property Glossary[] $glossaries
 * @property DataTransferProcess[] $dataTransferProcesses
 * @property Sourcesystem[] $sourcesystems
 * @property DataDeliveryObject[] $dataDeliveryObjects
 */
class Project extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid'], 'string'],
            [['fk_client_id'], 'integer'],
            [['name'], 'string', 'max' => 250],
            [['description'], 'string', 'max' => 4000]
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
            'fk_client_id' => Yii::t('app', 'Fk Client ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKeyfigures()
    {
        return $this->hasMany(\app\models\Keyfigure::className(), ['fk_project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkClient()
    {
        return $this->hasOne(\app\models\Client::className(), ['id' => 'fk_client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    /*
    public function getAttributes()
    {
        return $this->hasMany(\app\models\Attribute::className(), ['fk_project_id' => 'id']);
    }
    */
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDbTables()
    {
        return $this->hasMany(\app\models\DbTable::className(), ['fk_project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDbTableFields()
    {
        return $this->hasMany(\app\models\DbTableField::className(), ['fk_project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDbTableContexts()
    {
        return $this->hasMany(\app\models\DbTableContext::className(), ['fk_project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDbDatabases()
    {
        return $this->hasMany(\app\models\DbDatabase::className(), ['fk_project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchedulings()
    {
        return $this->hasMany(\app\models\Scheduling::className(), ['fk_project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParameters()
    {
        return $this->hasMany(\app\models\Parameter::className(), ['fk_project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGlossaries()
    {
        return $this->hasMany(\app\models\Glossary::className(), ['fk_project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDataTransferProcesses()
    {
        return $this->hasMany(\app\models\DataTransferProcess::className(), ['fk_project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSourcesystems()
    {
        return $this->hasMany(\app\models\Sourcesystem::className(), ['fk_project_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDataDeliveryObjects()
    {
        return $this->hasMany(\app\models\DataDeliveryObject::className(), ['fk_project_id' => 'id']);
    }
}
