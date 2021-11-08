<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "object_depends_on".
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $ref_fk_object_id_parent
 * @property integer $ref_fk_object_type_id_parent
 * @property integer $ref_fk_object_id_child
 * @property integer $ref_fk_object_type_id_child
 * @property integer $fk_object_persistence_method_id
 * @property integer $fk_datamanagement_process_id
 *
 * @property DatamanagementProcess $fkDatamanagementProcess
 * @property ObjectPersistenceMethod $fkObjectPersistenceMethod
 */
class ObjectDependsOn extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'object_depends_on';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid'], 'string'],
            [['ref_fk_object_id_parent', 'ref_fk_object_type_id_parent', 'ref_fk_object_id_child', 'ref_fk_object_type_id_child', 'fk_object_persistence_method_id', 'fk_datamanagement_process_id'], 'integer'],
            [['fk_datamanagement_process_id'], 'exist', 'skipOnError' => true, 'targetClass' => DatamanagementProcess::className(), 'targetAttribute' => ['fk_datamanagement_process_id' => 'id']],
            [['fk_object_persistence_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => ObjectPersistenceMethod::className(), 'targetAttribute' => ['fk_object_persistence_method_id' => 'id']],
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
            'ref_fk_object_id_parent' => Yii::t('app', 'Ref Fk Object Id Parent'),
            'ref_fk_object_type_id_parent' => Yii::t('app', 'Ref Fk Object Type Id Parent'),
            'ref_fk_object_id_child' => Yii::t('app', 'Ref Fk Object Id Child'),
            'ref_fk_object_type_id_child' => Yii::t('app', 'Ref Fk Object Type Id Child'),
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
}
