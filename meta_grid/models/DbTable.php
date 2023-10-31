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
		return $obj->limit(9999);
    }    
    
    public static function findBySql($sql, $params = [])
    {
		throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'Implementation deactivated.'));
		return null;			
    	
    }
}
