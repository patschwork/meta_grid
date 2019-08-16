<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "object_comment".
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $fk_object_type_id
 * @property integer $ref_fk_object_id
 * @property integer $ref_fk_object_type_id
 * @property string $comment
 * @property string $created_at_datetime
 *
 * @property ObjectType $refFkObjectType
 * @property ObjectType $fkObjectType
 */
class Objectcomment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'object_comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid', 'comment', 'created_at_datetime'], 'string'],
            [['fk_object_type_id', 'ref_fk_object_id', 'ref_fk_object_type_id'], 'integer']
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
            'ref_fk_object_id' => Yii::t('app', 'Ref Fk Object ID'),
            'ref_fk_object_type_id' => Yii::t('app', 'Ref Fk Object Type ID'),
            'comment' => Yii::t('app', 'Comment'),
            'created_at_datetime' => Yii::t('app', 'Created At Datetime'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefFkObjectType()
    {
        return $this->hasOne(ObjectType::className(), ['id' => 'ref_fk_object_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkObjectType()
    {
        return $this->hasOne(ObjectType::className(), ['id' => 'fk_object_type_id']);
    }
}
