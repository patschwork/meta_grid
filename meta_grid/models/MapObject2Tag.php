<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "map_object_2_tag".
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $ref_fk_object_id
 * @property integer $ref_fk_object_type_id
 * @property integer $fk_tag_id
 *
 * @property Tag $fkTag
 * @property ObjectType $refFkObjectType
 */
class MapObject2Tag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'map_object_2_tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid'], 'string'],
            [['ref_fk_object_id', 'ref_fk_object_type_id', 'fk_tag_id'], 'required'],
            [['ref_fk_object_id', 'ref_fk_object_type_id', 'fk_tag_id'], 'integer'],
            [['ref_fk_object_id', 'ref_fk_object_type_id', 'fk_tag_id'], 'unique', 'targetAttribute' => ['ref_fk_object_id', 'ref_fk_object_type_id', 'fk_tag_id'], 'message' => 'The combination of Ref Fk Object ID, Ref Fk Object Type ID and Fk Tag ID has already been taken.'],
            [['fk_tag_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tag::className(), 'targetAttribute' => ['fk_tag_id' => 'id']],
            [['ref_fk_object_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ObjectType::className(), 'targetAttribute' => ['ref_fk_object_type_id' => 'id']],
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
            'ref_fk_object_id' => Yii::t('app', 'Ref Fk Object ID'),
            'ref_fk_object_type_id' => Yii::t('app', 'Ref Fk Object Type ID'),
            'fk_tag_id' => Yii::t('app', 'Fk Tag ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkTag()
    {
        return $this->hasOne(Tag::className(), ['id' => 'fk_tag_id'])->inverseOf('mapObject2Tags');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefFkObjectType()
    {
        return $this->hasOne(ObjectType::className(), ['id' => 'ref_fk_object_type_id'])->inverseOf('mapObject2Tags');
    }
}
