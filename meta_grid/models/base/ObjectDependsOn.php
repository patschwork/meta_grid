<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "object_depends_on".
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $ref_fk_object_id_parent
 * @property integer $ref_fk_object_type_id_parent
 * @property integer $ref_fk_object_id_child
 * @property integer $ref_fk_object_type_id_child
 */
class ObjectDependsOn extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'object_depends_on';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid'], 'string'],
            [['ref_fk_object_id_parent', 'ref_fk_object_type_id_parent', 'ref_fk_object_id_child', 'ref_fk_object_type_id_child'], 'integer'],
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
            'ref_fk_object_id_parent' => Yii::t('app', 'Ref Fk Object Id Parent'),
            'ref_fk_object_type_id_parent' => Yii::t('app', 'Ref Fk Object Type Id Parent'),
            'ref_fk_object_id_child' => Yii::t('app', 'Ref Fk Object Id Child'),
            'ref_fk_object_type_id_child' => Yii::t('app', 'Ref Fk Object Type Id Child'),
        ];
    }

    /**
     * @inheritdoc
     * @return ObjectDependsOnQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ObjectDependsOnQuery(get_called_class());
    }
}
