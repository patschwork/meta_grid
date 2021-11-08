<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "map_object_2_object".
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $ref_fk_object_id_1
 * @property integer $ref_fk_object_type_id_1
 * @property integer $ref_fk_object_id_2
 * @property integer $ref_fk_object_type_id_2
 * @property integer $fk_mapping_qualifier_id
 * @property integer $fk_object_persistence_method_id
 * @property integer $fk_datamanagement_process_id
 *
 * @property DatamanagementProcess $fkDatamanagementProcess
 * @property ObjectPersistenceMethod $fkObjectPersistenceMethod
 * @property MappingQualifier $fkMappingQualifier
 * @property ObjectType $refFkObjectTypeId2
 * @property ObjectType $refFkObjectTypeId1
 */
class MapObject2Object extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'map_object_2_object';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid'], 'string'],
            [['ref_fk_object_id_1', 'ref_fk_object_type_id_1', 'ref_fk_object_id_2', 'ref_fk_object_type_id_2', 'fk_mapping_qualifier_id', 'fk_object_persistence_method_id', 'fk_datamanagement_process_id'], 'integer'],
            [['ref_fk_object_id_1', 'ref_fk_object_type_id_1', 'ref_fk_object_id_2', 'ref_fk_object_type_id_2'], 'unique', 'targetAttribute' => ['ref_fk_object_id_1', 'ref_fk_object_type_id_1', 'ref_fk_object_id_2', 'ref_fk_object_type_id_2'], 'message' => 'The combination of Ref Fk Object Id 1, Ref Fk Object Type Id 1, Ref Fk Object Id 2 and Ref Fk Object Type Id 2 has already been taken.'],
            [['fk_datamanagement_process_id'], 'exist', 'skipOnError' => true, 'targetClass' => DatamanagementProcess::className(), 'targetAttribute' => ['fk_datamanagement_process_id' => 'id']],
            [['fk_object_persistence_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => ObjectPersistenceMethod::className(), 'targetAttribute' => ['fk_object_persistence_method_id' => 'id']],
            [['fk_mapping_qualifier_id'], 'exist', 'skipOnError' => true, 'targetClass' => MappingQualifier::className(), 'targetAttribute' => ['fk_mapping_qualifier_id' => 'id']],
            [['ref_fk_object_type_id_2'], 'exist', 'skipOnError' => true, 'targetClass' => ObjectType::className(), 'targetAttribute' => ['ref_fk_object_type_id_2' => 'id']],
            [['ref_fk_object_type_id_1'], 'exist', 'skipOnError' => true, 'targetClass' => ObjectType::className(), 'targetAttribute' => ['ref_fk_object_type_id_1' => 'id']],
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
            'ref_fk_object_id_1' => Yii::t('app', 'Ref Fk Object Id 1'),
            'ref_fk_object_type_id_1' => Yii::t('app', 'Ref Fk Object Type Id 1'),
            'ref_fk_object_id_2' => Yii::t('app', 'Ref Fk Object Id 2'),
            'ref_fk_object_type_id_2' => Yii::t('app', 'Ref Fk Object Type Id 2'),
            'fk_mapping_qualifier_id' => Yii::t('app', 'Fk Mapping Qualifier ID'),
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
    public function getFkMappingQualifier()
    {
        return $this->hasOne(\app\models\MappingQualifier::className(), ['id' => 'fk_mapping_qualifier_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefFkObjectTypeId2()
    {
        return $this->hasOne(\app\models\ObjectType::className(), ['id' => 'ref_fk_object_type_id_2']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefFkObjectTypeId1()
    {
        return $this->hasOne(\app\models\ObjectType::className(), ['id' => 'ref_fk_object_type_id_1']);
    }
}
