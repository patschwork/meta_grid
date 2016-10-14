<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "data_transfer_type".
 *
 * @property integer $id
 * @property string $uuid
 * @property string $name
 * @property string $description
 *
 * @property DataTransferProcess[] $dataTransferProcesses
 */
class DataTransferType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'data_transfer_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid'], 'string'],
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
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDataTransferProcesses()
    {
        return $this->hasMany(\app\models\DataTransferProcess::className(), ['fk_data_transfer_type_id' => 'id']);
    }
}
