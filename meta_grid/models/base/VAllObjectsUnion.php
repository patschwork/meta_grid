<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "v_All_Objects_Union".
 *
 * @property integer $id
 * @property integer $fk_object_type_id
 * @property string $name
 * @property string $object_type_name
 * @property string $listvalue_1
 * @property string $listvalue_2
 * @property string $listkey
 * @property string $fk_client_id
 * @property integer $fk_project_id
 * @property string $listvalue_1_with_client_or_project
 * @property string $listvalue_2_with_client_or_project
 */
class VAllObjectsUnion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_All_Objects_Union';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'fk_object_type_id', 'fk_project_id'], 'integer'],
            [['listvalue_1', 'listvalue_2', 'listkey', 'fk_client_id', 'listvalue_1_with_client_or_project', 'listvalue_2_with_client_or_project'], 'string'],
            [['name'], 'string', 'max' => 500],
            [['object_type_name'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'fk_object_type_id' => Yii::t('app', 'Fk Object Type ID'),
            'name' => Yii::t('app', 'Name'),
            'object_type_name' => Yii::t('app', 'Object Type Name'),
            'listvalue_1' => Yii::t('app', 'Listvalue 1'),
            'listvalue_2' => Yii::t('app', 'Listvalue 2'),
            'listkey' => Yii::t('app', 'Listkey'),
            'fk_client_id' => Yii::t('app', 'Fk Client ID'),
            'fk_project_id' => Yii::t('app', 'Fk Project ID'),
            'listvalue_1_with_client_or_project' => Yii::t('app', 'Listvalue 1 With Client Or Project'),
            'listvalue_2_with_client_or_project' => Yii::t('app', 'Listvalue 2 With Client Or Project'),
        ];
    }
}
