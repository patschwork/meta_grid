<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "v_db_table_location_4_same_table_lookup".
 *
 * @property string $id
 * @property string $fk_project_id
 * @property string $location
 * @property string $project_name
 * @property string $db_table_location_normalized
 */
class VDbTableLocation4SameTableLookup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_db_table_location_4_same_table_lookup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'fk_project_id', 'location', 'db_table_location_normalized'], 'string'],
            [['project_name'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'fk_project_id' => Yii::t('app', 'Fk Project ID'),
            'location' => Yii::t('app', 'Location'),
            'project_name' => Yii::t('app', 'Project Name'),
            'db_table_location_normalized' => Yii::t('app', 'Db Table Location Normalized'),
        ];
    }

    public static function findOne($condition)
    {
		$model=static::findByCondition($condition)->one();
		if ( (isset($model)) || (Yii::$app->User->identity->isAdmin))
		{
			return $model;
		}
		else
		{
			// throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You have no permission for this data.')); // 0 rows is a valid result!
			return null;			
		}
    }
    
    public static function find()
    {
    	$permProjectsCanSee = Yii::$app->User->identity->permProjectsCanSee;
    	 
    	$obj=Yii::createObject(yii\db\ActiveQuery::className(), [get_called_class()]);
    	if (!Yii::$app->User->identity->isAdmin)
    	{
			$obj->Where([
					'in','fk_project_id', $permProjectsCanSee
	        ]);
    	}
		return $obj;
    }    
    
    public static function findBySql($sql, $params = [])
    {
		throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'Implementation deactivated.'));
		return null;			
    	
    }
}
