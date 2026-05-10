<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "wiki".
 *
 * @property int|null $id
 * @property string|null $uuid
 * @property int|null $fk_object_type_id
 * @property string $name
 * @property string|null $description
 * @property int|null $fk_project_id
 * @property int|null $fk_user_id
 *
 * @property ObjectType $fkObjectType
 * @property Project $fkProject
 */
class Wiki extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wiki';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'default', 'value' => null],
            [['uuid'], 'default', 'value' => 'NULL'],
            [['fk_object_type_id'], 'default', 'value' => 31],
            // [['fk_user_id'], 'default', 'value' => 0],
            [['uuid', 'description'], 'string'],
            [['fk_object_type_id', 'fk_project_id', 'fk_user_id'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 250],
            [['name', 'fk_project_id', 'fk_user_id'], 'unique', 'targetAttribute' => ['name', 'fk_project_id', 'fk_user_id']],
            // [['fk_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['fk_user_id' => 'id']],
            [['fk_project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::class, 'targetAttribute' => ['fk_project_id' => 'id']],
            [['fk_object_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ObjectType::class, 'targetAttribute' => ['fk_object_type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uuid' => 'Uuid',
            'fk_object_type_id' => 'Fk Object Type ID',
            'name' => 'Name',
            'description' => 'Description',
            'fk_project_id' => 'Fk Project ID',
            'fk_user_id' => 'Fk User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkObjectType()
    {
        return $this->hasOne(\app\models\ObjectType::className(), ['id' => 'fk_object_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkProject()
    {
        return $this->hasOne(\app\models\Project::className(), ['id' => 'fk_project_id']);
    }

    /**
     * Gets query for [[FkUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    // public function getFkUser()
    // {
    //     return $this->hasOne(User::class, ['id' => 'fk_user_id']);
    // }

}
