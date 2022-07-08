<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "tag".
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $fk_object_type_id
 * @property string $name
 * @property integer $fk_project_id
 * @property integer $fk_user_id
 *
 * @property MapObject2Tag[] $mapObject2Tags
 * @property User $fkUser
 * @property Project $fkProject
 * @property ObjectType $fkObjectType
 */
class Tag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid'], 'string'],
            [['fk_object_type_id', 'fk_project_id', 'fk_user_id'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 250],
            [['name', 'fk_project_id', 'fk_user_id'], 'unique', 'targetAttribute' => ['name', 'fk_project_id', 'fk_user_id'], 'message' => 'The combination of Name, Fk Project ID and Fk User ID has already been taken.'],
            [['fk_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['fk_user_id' => 'id']],
            [['fk_project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['fk_project_id' => 'id']],
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
            'fk_object_type_id' => Yii::t('app', 'Fk Object Type ID'),
            'name' => Yii::t('app', 'Name'),
            'fk_project_id' => Yii::t('app', 'Fk Project ID'),
            'fk_user_id' => Yii::t('app', 'Fk User ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMapObject2Tags()
    {
        return $this->hasMany(\app\models\MapObject2Tag::className(), ['fk_tag_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkUser()
    {
        return $this->hasOne(User::className(), ['id' => 'fk_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkProject()
    {
        return $this->hasOne(\app\models\Project::className(), ['id' => 'fk_project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkObjectType()
    {
        return $this->hasOne(\app\models\ObjectType::className(), ['id' => 'fk_object_type_id']);
    }
}
