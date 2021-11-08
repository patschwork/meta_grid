<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "mapping_qualifier".
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $fk_object_type_id
 * @property string $name
 * @property string $short_name
 * @property string $description
 * @property boolean $needs_object_depends_on
 * @property integer $fk_object_persistence_method_id
 * @property integer $fk_datamanagement_process_id
 *
 * @property MapObject2Object[] $mapObject2Objects
 * @property DatamanagementProcess $fkDatamanagementProcess
 * @property ObjectPersistenceMethod $fkObjectPersistenceMethod
 * @property ObjectType $fkObjectType
 */
class MappingQualifier extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mapping_qualifier';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid'], 'string'],
            [['fk_object_type_id', 'fk_object_persistence_method_id', 'fk_datamanagement_process_id'], 'integer'],
            [['needs_object_depends_on'], 'boolean'],
            [['name', 'short_name'], 'string', 'max' => 250],
            [['description'], 'string', 'max' => 4000],
            [['fk_datamanagement_process_id'], 'exist', 'skipOnError' => true, 'targetClass' => DatamanagementProcess::className(), 'targetAttribute' => ['fk_datamanagement_process_id' => 'id']],
            [['fk_object_persistence_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => ObjectPersistenceMethod::className(), 'targetAttribute' => ['fk_object_persistence_method_id' => 'id']],
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
            'short_name' => Yii::t('app', 'Short Name'),
            'description' => Yii::t('app', 'Description'),
            'needs_object_depends_on' => Yii::t('app', 'Needs Object Depends On'),
            'fk_object_persistence_method_id' => Yii::t('app', 'Fk Object Persistence Method ID'),
            'fk_datamanagement_process_id' => Yii::t('app', 'Fk Datamanagement Process ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMapObject2Objects()
    {
        return $this->hasMany(\app\models\MapObject2Object::className(), ['fk_mapping_qualifier_id' => 'id']);
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
    public function getFkObjectType()
    {
        return $this->hasOne(\app\models\ObjectType::className(), ['id' => 'fk_object_type_id']);
    }
}
