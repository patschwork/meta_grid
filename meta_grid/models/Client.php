<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "client".
 */
class Client extends \app\models\base\Client
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
		$permClientsCanSee = Yii::$app->User->identity->permClientsCanSee;
	
		
    	$obj=Yii::createObject(yii\db\ActiveQuery::className(), [get_called_class()]);
				
		
		if (!Yii::$app->User->identity->isAdmin)
		{
			$calledClassObj=Yii::createObject(['class' => get_called_class()]);
			$clientFieldId = $calledClassObj->tableName() . '.id';
// 			if (get_called_class() === 'app\models\VClientSearchinterface')
// 			{
// 				$clientFieldId = 'v_client_SearchInterface.id';
// 			}
					
			$obj->Where([
					'in',$clientFieldId, $permClientsCanSee
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
