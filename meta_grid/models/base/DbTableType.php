<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "db_table_type".
 *
 * @property integer $id
 * @property string $uuid
 * @property string $name
 * @property string $description
 * @property integer $fk_object_persistence_method_id
 * @property integer $fk_datamanagement_process_id
 * @property string $source_definition_language
 *
 * @property DbTable[] $dbTables
 * @property DatamanagementProcess $fkDatamanagementProcess
 * @property ObjectPersistenceMethod $fkObjectPersistenceMethod
 */
class DbTableType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'db_table_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid', 'name', 'description'], 'string'],
            [['fk_object_persistence_method_id', 'fk_datamanagement_process_id'], 'integer'],
            [['source_definition_language'], 'string', 'max' => 250],
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
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'fk_object_persistence_method_id' => Yii::t('app', 'Fk Object Persistence Method ID'),
            'fk_datamanagement_process_id' => Yii::t('app', 'Fk Datamanagement Process ID'),
            'source_definition_language' => Yii::t('app', 'Source Definition Language'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDbTables()
    {
        return $this->hasMany(\app\models\DbTable::className(), ['fk_db_table_type_id' => 'id']);
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
