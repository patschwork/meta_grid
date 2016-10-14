<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "bracket".
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
            [['description'], 'string', 'max' => 500]
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
        return $this->hasOne(\app\models\ObjectType::className(), ['id' => 'fk_object_type_id_as_searchFilter']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkAttribute()
    {
        return $this->hasOne(\app\models\Attribute::className(), ['id' => 'fk_attribute_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkProject()
    {
        return $this->hasOne(\app\models\Project::className(), ['id' => 'fk_project_id']);
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
    public function getBracketSearchPatterns()
    {
        return $this->hasMany(\app\models\BracketSearchPattern::className(), ['fk_bracket_id' => 'id']);
    }
}
