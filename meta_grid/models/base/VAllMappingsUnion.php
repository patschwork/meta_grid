<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "v_All_Mappings_Union".
 *
 * @property string $connectiondirection
 * @property string $listvalue_1
 * @property string $listvalue_2
 * @property string $name
 * @property string $object_type_name
 * @property string $listkey
 * @property integer $ref_fk_object_id_1
 * @property integer $ref_fk_object_type_id_1
 * @property integer $ref_fk_object_id_2
 * @property integer $ref_fk_object_type_id_2
 * @property integer $filter_ref_fk_object_id
 * @property integer $filter_ref_fk_object_type_id
 */
class VAllMappingsUnion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_All_Mappings_Union';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['connectiondirection', 'listvalue_1', 'listvalue_2', 'name', 'listkey'], 'string'],
            [['ref_fk_object_id_1', 'ref_fk_object_type_id_1', 'ref_fk_object_id_2', 'ref_fk_object_type_id_2', 'filter_ref_fk_object_id', 'filter_ref_fk_object_type_id'], 'integer'],
            [['object_type_name'], 'string', 'max' => 250]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'connectiondirection' => Yii::t('app', 'Connectiondirection'),
            'listvalue_1' => Yii::t('app', 'Listvalue 1'),
            'listvalue_2' => Yii::t('app', 'Listvalue 2'),
            'name' => Yii::t('app', 'Name'),
            'object_type_name' => Yii::t('app', 'Object Type Name'),
            'listkey' => Yii::t('app', 'Listkey'),
            'ref_fk_object_id_1' => Yii::t('app', 'Ref Fk Object Id 1'),
            'ref_fk_object_type_id_1' => Yii::t('app', 'Ref Fk Object Type Id 1'),
            'ref_fk_object_id_2' => Yii::t('app', 'Ref Fk Object Id 2'),
            'ref_fk_object_type_id_2' => Yii::t('app', 'Ref Fk Object Type Id 2'),
            'filter_ref_fk_object_id' => Yii::t('app', 'Filter Ref Fk Object ID'),
            'filter_ref_fk_object_type_id' => Yii::t('app', 'Filter Ref Fk Object Type ID'),
        ];
    }
}
