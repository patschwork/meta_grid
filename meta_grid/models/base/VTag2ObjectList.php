<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "v_tag_2_object_list".
 *
 * @property int|null $id
 * @property int|null $ref_fk_object_id
 * @property int|null $ref_fk_object_type_id
 * @property string|null $object_type_name
 * @property string|null $object_name
 * @property int|null $fk_tag_id
 * @property string|null $tag_name
 * @property string|null $action
 * @property int|null $fk_project_id
 * @property int|null $fk_user_id
 * @property string|null $optgroup
 */
class VTag2ObjectList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'v_tag_2_object_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'ref_fk_object_id', 'ref_fk_object_type_id', 'fk_tag_id', 'fk_project_id', 'fk_user_id', 'fk_project_id_from_object', 'fk_client_id_from_object'], 'integer'],
            [['action', 'optgroup', 'controllername'], 'string'],
            [['object_type_name', 'tag_name'], 'string', 'max' => 250],
            [['object_name'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'ref_fk_object_id' => Yii::t('app', 'Ref Fk Object ID'),
            'ref_fk_object_type_id' => Yii::t('app', 'Ref Fk Object Type ID'),
            'object_type_name' => Yii::t('app', 'Object Type Name'),
            'object_name' => Yii::t('app', 'Object Name'),
            'fk_tag_id' => Yii::t('app', 'Fk Tag ID'),
            'tag_name' => Yii::t('app', 'Tag Name'),
            'action' => Yii::t('app', 'Action'),
            'fk_project_id' => Yii::t('app', 'Fk Project ID'),
            'fk_user_id' => Yii::t('app', 'Fk User ID'),
            'optgroup' => Yii::t('app', 'Optgroup'),
            'controllername' => Yii::t('app', 'Controllername'),
            'fk_project_id_from_object' => Yii::t('app', 'Fk Project ID from object'),
            'fk_client_id_from_object' => Yii::t('app', 'Fk Client ID from object'),
        ];
    }
}
