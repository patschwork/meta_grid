<?php

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "db_table".
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $fk_object_type_id
 * @property integer $fk_project_id
 * @property string $name
 * @property string $description
 * @property string $location
 * @property integer $fk_db_table_context_id
 * @property integer $fk_db_table_type_id
 * @property integer $fk_deleted_status_id
 *
 * @property DeletedStatus $fkDeletedStatus
 * @property DbTableType $fkDbTableType
 * @property DbTableContext $fkDbTableContext
 * @property Project $fkProject
 * @property ObjectType $fkObjectType
 * @property DbTableField[] $dbTableFields
 */
class DbTable extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'db_table';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid', 'location'], 'string'],        		
			['fk_db_table_context_id', 'checkEqualProjectId'],
        	[['fk_object_type_id', 'fk_project_id', 'fk_db_table_context_id', 'fk_db_table_type_id', 'fk_deleted_status_id'], 'integer'],
            [['name'], 'string', 'max' => 250],
            [['description'], 'string', 'max' => 4000],
            [['fk_deleted_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\DeletedStatus::className(), 'targetAttribute' => ['fk_deleted_status_id' => 'id']],            
        ];
    }

    public function checkEqualProjectId($attribute, $params)
    {
        if (!\app\models\DbTableContext::findOne(['id'=> $this->$attribute, 'fk_project_id'=>$this->fk_project_id])) {
    		$this->addError($attribute, \Yii::t('app', 'Cross project references are not allowed.'));
    	}
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
            'location' => Yii::t('app', 'Location'),
            'fk_db_table_context_id' => Yii::t('app', 'Fk Db Table Context ID'),
            'fk_db_table_type_id' => Yii::t('app', 'Fk Db Table Type ID'),
            'fk_deleted_status_id' => Yii::t('app', 'Fk Deleted Status ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkDeletedStatus()
    {
        return $this->hasOne(DeletedStatus::className(), ['id' => 'fk_deleted_status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkDbTableType()
    {
        return $this->hasOne(\app\models\DbTableType::className(), ['id' => 'fk_db_table_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkDbTableContext()
    {
        return $this->hasOne(\app\models\DbTableContext::className(), ['id' => 'fk_db_table_context_id']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDbTableFields()
    {
        return $this->hasMany(\app\models\DbTableField::className(), ['fk_db_table_id' => 'id']);
    }
}
