<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "sourcesystem".
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $fk_object_type_id
 * @property integer $fk_project_id
 * @property string $name
 * @property string $description
 * @property integer $fk_contact_group_id_as_supporter
 *
 * @property ContactGroup $fkContactGroupIdAsSupporter
 * @property Project $fkProject
 * @property ObjectType $fkObjectType
 */
class Sourcesystem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sourcesystem';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid'], 'string'],
            [['fk_object_type_id', 'fk_project_id', 'fk_contact_group_id_as_supporter'], 'integer'],
            [['name'], 'string', 'max' => 250],
            [['description'], 'string', 'max' => 500]
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
            'fk_project_id' => Yii::t('app', 'Fk Project ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'fk_contact_group_id_as_supporter' => Yii::t('app', 'Fk Contact Group Id As Supporter'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkContactGroupIdAsSupporter()
    {
        return $this->hasOne(\app\models\ContactGroup::className(), ['id' => 'fk_contact_group_id_as_supporter']);
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
