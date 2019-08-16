<?php

namespace app\models;

use Yii;

class ObjectDependsOn extends \app\models\base\ObjectDependsOn
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
		$obj=Yii::createObject(yii\db\ActiveQuery::className(), [get_called_class()]);
		if (!Yii::$app->User->identity->isAdmin)
		{
			$obj->Where([
					'in','id', [-1234567]
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
