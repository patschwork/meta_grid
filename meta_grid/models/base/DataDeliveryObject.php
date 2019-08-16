<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "data_delivery_object".
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $fk_object_type_id
 * @property integer $fk_project_id
 * @property string $name
 * @property string $description
 * @property integer $fk_tool_id
 * @property integer $fk_data_delivery_type_id
 * @property integer $fk_contact_group_id_as_data_owner
 *
 * @property ContactGroup $fkContactGroupIdAsDataOwner
 * @property DataDeliveryType $fkDataDeliveryType
 * @property Tool $fkTool
 * @property Project $fkProject
 * @property ObjectType $fkObjectType
 */
class DataDeliveryObject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'data_delivery_object';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid'], 'string'],
            [['fk_object_type_id', 'fk_project_id', 'fk_tool_id', 'fk_data_delivery_type_id', 'fk_contact_group_id_as_data_owner'], 'integer'],
            [['name'], 'string', 'max' => 250],
            [['description'], 'string', 'max' => 4000]
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
            'fk_tool_id' => Yii::t('app', 'Fk Tool ID'),
            'fk_data_delivery_type_id' => Yii::t('app', 'Fk Data Delivery Type ID'),
            'fk_contact_group_id_as_data_owner' => Yii::t('app', 'Fk Contact Group Id As Data Owner'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkContactGroupIdAsDataOwner()
    {
        return $this->hasOne(\app\models\ContactGroup::className(), ['id' => 'fk_contact_group_id_as_data_owner']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkDataDeliveryType()
    {
        return $this->hasOne(\app\models\DataDeliveryType::className(), ['id' => 'fk_data_delivery_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkTool()
    {
        return $this->hasOne(\app\models\Tool::className(), ['id' => 'fk_tool_id']);
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
