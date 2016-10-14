<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "bracket_searchPattern".
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $fk_object_type_id
 * @property integer $fk_bracket_id
 * @property string $searchPattern
 *
 * @property Bracket $fkBracket
 * @property ObjectType $fkObjectType
 */
class BracketSearchPattern extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bracket_searchPattern';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid'], 'string'],
            [['fk_object_type_id', 'fk_bracket_id'], 'integer'],
            [['fk_bracket_id', 'searchPattern'], 'required'],
            [['searchPattern'], 'string', 'max' => 500]
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
            'fk_bracket_id' => Yii::t('app', 'Fk Bracket ID'),
            'searchPattern' => Yii::t('app', 'Search Pattern'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkBracket()
    {
        return $this->hasOne(\app\models\Bracket::className(), ['id' => 'fk_bracket_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkObjectType()
    {
        return $this->hasOne(\app\models\ObjectType::className(), ['id' => 'fk_object_type_id']);
    }
}
