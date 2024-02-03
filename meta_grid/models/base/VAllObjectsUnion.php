<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "v_All_Objects_Union".
 *
 * @property int|null $id
 * @property int|null $fk_object_type_id
 * @property string|null $name
 * @property string|null $object_type_name
 * @property string|null $listvalue_1
 * @property string|null $listvalue_2
 * @property string|null $listkey
 * @property string|null $fk_client_id
 * @property int|null $fk_project_id
 * @property string|null $listvalue_1_with_client_or_project
 * @property string|null $listvalue_2_with_client_or_project
 * @property string|null $description
 * @property string|null $detail_1_name
 * @property string|null $detail_1_content
 * @property string|null $detail_2_name
 * @property string|null $detail_2_content
 * @property string|null $detail_3_name
 * @property string|null $detail_3_content
 * @property string|null $detail_4_name
 * @property string|null $detail_4_content
 * @property string|null $detail_5_name
 * @property string|null $detail_5_content
 */
class VAllObjectsUnion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'v_All_Objects_Union';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fk_object_type_id', 'fk_project_id'], 'integer'],
            [['listvalue_1', 'listvalue_2', 'listkey', 'fk_client_id', 'listvalue_1_with_client_or_project', 'listvalue_2_with_client_or_project', 'description', 'detail_1_name', 'detail_1_content', 'detail_2_name', 'detail_2_content', 'detail_3_name', 'detail_3_content', 'detail_4_name', 'detail_4_content', 'detail_5_name', 'detail_5_content'], 'string'],
            [['name'], 'string', 'max' => 500],
            [['object_type_name'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
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
            'description' => Yii::t('app', 'Description'),
            'detail_1_name' => Yii::t('app', 'Detail 1 Name'),
            'detail_1_content' => Yii::t('app', 'Detail 1 Content'),
            'detail_2_name' => Yii::t('app', 'Detail 2 Name'),
            'detail_2_content' => Yii::t('app', 'Detail 2 Content'),
            'detail_3_name' => Yii::t('app', 'Detail 3 Name'),
            'detail_3_content' => Yii::t('app', 'Detail 3 Content'),
            'detail_4_name' => Yii::t('app', 'Detail 4 Name'),
            'detail_4_content' => Yii::t('app', 'Detail 4 Content'),
            'detail_5_name' => Yii::t('app', 'Detail 5 Name'),
            'detail_5_content' => Yii::t('app', 'Detail 5 Content'),
        ];
    }
}
