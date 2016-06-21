<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "db_table_field".
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $fk_object_type_id
 * @property integer $fk_project_id
 * @property string $name
 * @property string $description
 * @property integer $fk_db_table_id
 * @property string $datatype
 *
 * @property DbTable $fkDbTable
 * @property Project $fkProject
 * @property ObjectType $fkObjectType
 */
class DbTableField extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'db_table_field';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid'], 'string'],
            [['fk_object_type_id', 'fk_project_id', 'fk_db_table_id'], 'integer'],
            [['name', 'datatype'], 'string', 'max' => 250],
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
            'fk_db_table_id' => Yii::t('app', 'Fk Db Table ID'),
            'datatype' => Yii::t('app', 'Datatype'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkDbTable()
    {
        return $this->hasOne(\app\models\DbTable::className(), ['id' => 'fk_db_table_id']);
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
