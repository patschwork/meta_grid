<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "v_tagsOptGroup".
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $fk_object_type_id
 * @property string $name
 * @property integer $fk_project_id
 * @property integer $fk_user_id
 * @property string $optgroup
 */
class VTagsOptGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_tagsOptGroup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'fk_object_type_id', 'fk_project_id', 'fk_user_id'], 'integer'],
            [['uuid', 'optgroup'], 'string'],
            [['name'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uuid' => 'Uuid',
            'fk_object_type_id' => 'Fk Object Type ID',
            'name' => 'Name',
            'fk_project_id' => 'Fk Project ID',
            'fk_user_id' => 'Fk User ID',
            'optgroup' => 'Optgroup',
        ];
    }
}
