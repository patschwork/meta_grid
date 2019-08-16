<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "bracket".
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $fk_object_type_id
 * @property integer $fk_project_id
 * @property string $name
 * @property string $description
 * @property integer $fk_attribute_id
 * @property integer $fk_object_type_id_as_searchFilter
 *
 * @property ObjectType $fkObjectTypeIdAsSearchFilter
 * @property Attribute $fkAttribute
 * @property Project $fkProject
 * @property ObjectType $fkObjectType
 * @property BracketSearchPattern[] $bracketSearchPatterns
 */
class Bracket extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bracket';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid'], 'string'],
            [['fk_object_type_id', 'fk_project_id', 'fk_attribute_id', 'fk_object_type_id_as_searchFilter'], 'integer'],
            [['name'], 'string', 'max' => 250],
            [['description'], 'string', 'max' => 4000],
            [['fk_object_type_id_as_searchFilter'], 'exist', 'skipOnError' => true, 'targetClass' => ObjectType::className(), 'targetAttribute' => ['fk_object_type_id_as_searchFilter' => 'id']],
            [['fk_attribute_id'], 'exist', 'skipOnError' => true, 'targetClass' => Attribute::className(), 'targetAttribute' => ['fk_attribute_id' => 'id']],
            [['fk_project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['fk_project_id' => 'id']],
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
            'fk_project_id' => Yii::t('app', 'Fk Project ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'fk_attribute_id' => Yii::t('app', 'Fk Attribute ID'),
            'fk_object_type_id_as_searchFilter' => Yii::t('app', 'Fk Object Type Id As Search Filter'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkObjectTypeIdAsSearchFilter()
    {
        return $this->hasOne(ObjectType::className(), ['id' => 'fk_object_type_id_as_searchFilter']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkAttribute()
    {
        return $this->hasOne(Attribute::className(), ['id' => 'fk_attribute_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'fk_project_id']);
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
    public function getBracketSearchPatterns()
    {
        return $this->hasMany(BracketSearchPattern::className(), ['fk_bracket_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return BracketQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BracketQuery(get_called_class());
    }
}
