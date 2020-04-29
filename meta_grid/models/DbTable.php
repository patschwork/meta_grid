<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "db_table".
 */
class DbTable extends \app\models\base\DbTable
{
	
	public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        $db_table_field = DbTableField::findAll(['fk_db_table_id' => $this->id]);
        
        foreach($db_table_field as $row)
        {
                $row->delete();
        }
        
        return true;
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
			throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You have no permission for this data.'));
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
    
    // { ... phabricator-task: T23
    public function getDatabaseInfoFromLocation()
    {
    	// The bulk loader sets the location attrbute as follows: "Databasename"."Schema"."Table"
    	$returnValue = "";
    	
    	if ((strpos($this->location, '"."')) !== false)
    	{
    		if (count(explode('"."', $this->location)) >= 3)
    		{
    			$returnValue = explode(".", $this->location)[0];
    			$returnValue = str_replace('"',"",$returnValue);
    		}
    	}
    	
    	return $returnValue;
    }
    // ...}
	
	// { ... phabricator-task: T59
    public function attributeLabels() {
		
		$addionalLabels = array('databaseInfoFromLocation' => Yii::t('app', 'Database'));
		return array_merge(parent::attributeLabels(), $addionalLabels);
    }
    // ...}
}
