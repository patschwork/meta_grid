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
 *
 * @property ToolType $fkToolType
 * @property DbDatabase[] $dbDatabases
 * @property Scheduling[] $schedulings
 * @property DataDeliveryObject[] $dataDeliveryObjects
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
            [['fk_tool_type_id'], 'integer'],
            [['tool_name', 'vendor', 'version'], 'string', 'max' => 255],
            [['comment'], 'string', 'max' => 500]
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkToolType()
    {
        return $this->hasOne(\app\models\ToolType::className(), ['id' => 'fk_tool_type_id']);
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
    public function getDataDeliveryObjects()
    {
        return $this->hasMany(\app\models\DataDeliveryObject::className(), ['fk_tool_id' => 'id']);
    }
}
