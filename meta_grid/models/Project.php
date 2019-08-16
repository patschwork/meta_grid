<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project".
 */
class Project extends \app\models\base\Project
{
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
		// 		$permClientsCanSee = Yii::$app->User->identity->permClientsCanSee;
		
		$obj=Yii::createObject(yii\db\ActiveQuery::className(), [get_called_class()]);
		if (!Yii::$app->User->identity->isAdmin)
		{
			$calledClassObj=Yii::createObject(['class' => get_called_class()]);
			$projectFieldId = $calledClassObj->tableName() . '.id';
				
			$obj->Where([
					 'in',$projectFieldId, $permProjectsCanSee
// 					,'in','fk_client_id', $permClientsCanSee
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
