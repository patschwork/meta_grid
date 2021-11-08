<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "bracket_searchPattern".
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $fk_object_type_id
 * @property integer $fk_bracket_id
 * @property string $searchPattern
 * @property integer $fk_object_persistence_method_id
 * @property integer $fk_datamanagement_process_id
 *
 * @property DatamanagementProcess $fkDatamanagementProcess
 * @property ObjectPersistenceMethod $fkObjectPersistenceMethod
 * @property Bracket $fkBracket
 * @property ObjectType $fkObjectType
 */
class BracketSearchPattern extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bracket_searchPattern';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid'], 'string'],
            [['fk_object_type_id', 'fk_bracket_id', 'fk_object_persistence_method_id', 'fk_datamanagement_process_id'], 'integer'],
            [['fk_bracket_id', 'searchPattern'], 'required'],
            [['searchPattern'], 'string', 'max' => 500],
            [['fk_datamanagement_process_id'], 'exist', 'skipOnError' => true, 'targetClass' => DatamanagementProcess::className(), 'targetAttribute' => ['fk_datamanagement_process_id' => 'id']],
            [['fk_object_persistence_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => ObjectPersistenceMethod::className(), 'targetAttribute' => ['fk_object_persistence_method_id' => 'id']],
            [['fk_bracket_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bracket::className(), 'targetAttribute' => ['fk_bracket_id' => 'id']],
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
            'fk_bracket_id' => Yii::t('app', 'Fk Bracket ID'),
            'searchPattern' => Yii::t('app', 'Search Pattern'),
            'fk_object_persistence_method_id' => Yii::t('app', 'Fk Object Persistence Method ID'),
            'fk_datamanagement_process_id' => Yii::t('app', 'Fk Datamanagement Process ID'),
        ];
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
    public function getFkBracket()
    {
        return $this->hasOne(\app\models\Bracket::className(), ['id' => 'fk_bracket_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkObjectType()
    {
        return $this->hasOne(\app\models\ObjectType::className(), ['id' => 'fk_object_type_id']);
    }
}
