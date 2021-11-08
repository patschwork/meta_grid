<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "tool".
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $fk_tool_type_id
 * @property string $tool_name
 * @property string $vendor
 * @property string $version
 * @property string $comment
 * @property integer $fk_object_persistence_method_id
 * @property integer $fk_datamanagement_process_id
 *
 * @property DataDeliveryObject[] $dataDeliveryObjects
 * @property DataDeliveryObjectOLD[] $dataDeliveryObjectOLDs
 * @property DbDatabase[] $dbDatabases
 * @property Scheduling[] $schedulings
 * @property DatamanagementProcess $fkDatamanagementProcess
 * @property ObjectPersistenceMethod $fkObjectPersistenceMethod
 * @property ToolType $fkToolType
 */
class Tool extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tool';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid'], 'string'],
            [['fk_tool_type_id', 'fk_object_persistence_method_id', 'fk_datamanagement_process_id'], 'integer'],
            [['tool_name', 'vendor', 'version'], 'string', 'max' => 255],
            [['comment'], 'string', 'max' => 500],
            [['fk_datamanagement_process_id'], 'exist', 'skipOnError' => true, 'targetClass' => DatamanagementProcess::className(), 'targetAttribute' => ['fk_datamanagement_process_id' => 'id']],
            [['fk_object_persistence_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => ObjectPersistenceMethod::className(), 'targetAttribute' => ['fk_object_persistence_method_id' => 'id']],
            [['fk_tool_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ToolType::className(), 'targetAttribute' => ['fk_tool_type_id' => 'id']],
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
            'fk_tool_type_id' => Yii::t('app', 'Fk Tool Type ID'),
            'tool_name' => Yii::t('app', 'Tool Name'),
            'vendor' => Yii::t('app', 'Vendor'),
            'version' => Yii::t('app', 'Version'),
            'comment' => Yii::t('app', 'Comment'),
            'fk_object_persistence_method_id' => Yii::t('app', 'Fk Object Persistence Method ID'),
            'fk_datamanagement_process_id' => Yii::t('app', 'Fk Datamanagement Process ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDataDeliveryObjects()
    {
        return $this->hasMany(\app\models\DataDeliveryObject::className(), ['fk_tool_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDataDeliveryObjectOLDs()
    {
        return $this->hasMany(\app\models\DataDeliveryObjectOLD::className(), ['fk_tool_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDbDatabases()
    {
        return $this->hasMany(\app\models\DbDatabase::className(), ['fk_tool_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchedulings()
    {
        return $this->hasMany(\app\models\Scheduling::className(), ['fk_tool_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkDatamanagementProcess()
    {
        return $this->hasOne(\app\models\DatamanagementProcess::className(), ['id' => 'fk_datamanagement_process_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkObjectPersistenceMethod()
    {
        return $this->hasOne(\app\models\ObjectPersistenceMethod::className(), ['id' => 'fk_object_persistence_method_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkToolType()
    {
        return $this->hasOne(\app\models\ToolType::className(), ['id' => 'fk_tool_type_id']);
    }
}
