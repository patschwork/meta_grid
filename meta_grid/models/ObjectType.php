<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "object_type".
 *
 * @property integer $id
 * @property string $uuid
 * @property string $name
 *
 * @property Keyfigure[] $keyfigures
 * @property Sourcesystem[] $sourcesystems
 * @property Report[] $reports
 * @property MapObject2Object[] $mapObject2Objects
 * @property Attribute[] $attributes
 * @property DbTable[] $dbTables
 * @property DbTableField[] $dbTableFields
 * @property DbTableContext[] $dbTableContexts
 * @property DbDatabase[] $dbDatabases
 * @property Scheduling[] $schedulings
 * @property Parameter[] $parameters
 * @property ObjectComment[] $objectComments
 * @property Glossary[] $glossaries
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
            [['name'], 'string', 'max' => 250]
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
    public function getKeyfigures()
    {
        return $this->hasMany(Keyfigure::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSourcesystems()
    {
        return $this->hasMany(Sourcesystem::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReports()
    {
        return $this->hasMany(Report::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMapObject2Objects()
    {
        return $this->hasMany(MapObject2Object::className(), ['ref_fk_object_type_id_1' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttributes()
    {
        return $this->hasMany(Attribute::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDbTables()
    {
        return $this->hasMany(DbTable::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDbTableFields()
    {
        return $this->hasMany(DbTableField::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDbTableContexts()
    {
        return $this->hasMany(DbTableContext::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDbDatabases()
    {
        return $this->hasMany(DbDatabase::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchedulings()
    {
        return $this->hasMany(Scheduling::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParameters()
    {
        return $this->hasMany(Parameter::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getObjectComments()
    {
        return $this->hasMany(ObjectComment::className(), ['fk_object_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGlossaries()
    {
        return $this->hasMany(Glossary::className(), ['fk_object_type_id' => 'id']);
    }
}
