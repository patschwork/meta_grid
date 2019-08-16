<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "perspective_filter".
 *
 * @property integer $id
 * @property string $uuid
 * @property string $fk_language_id
 * @property integer $fk_object_type_id
 * @property string $filter_attribute_name
 * @property string $filter_value
 * @property integer $ref_fk_object_type_id
 *
 * @property ObjectType $refFkObjectType
 * @property ObjectType $fkObjectType
 */
class PerspectiveFilter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'perspective_filter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid'], 'string'],
            [['fk_object_type_id', 'ref_fk_object_type_id'], 'integer'],
            [['fk_language_id'], 'string', 'max' => 32],
            [['filter_attribute_name', 'filter_value'], 'string', 'max' => 150],
            [['ref_fk_object_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ObjectType::className(), 'targetAttribute' => ['ref_fk_object_type_id' => 'id']],
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
            'fk_language_id' => Yii::t('app', 'Fk Language ID'),
            'fk_object_type_id' => Yii::t('app', 'Fk Object Type ID'),
            'filter_attribute_name' => Yii::t('app', 'Filter Attribute Name'),
            'filter_value' => Yii::t('app', 'Filter Value'),
            'ref_fk_object_type_id' => Yii::t('app', 'Ref Fk Object Type ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefFkObjectType()
    {
        return $this->hasOne(ObjectType::className(), ['id' => 'ref_fk_object_type_id'])->inverseOf('perspectiveFilters');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkObjectType()
    {
        return $this->hasOne(ObjectType::className(), ['id' => 'fk_object_type_id'])->inverseOf('perspectiveFilters0');
    }
}
