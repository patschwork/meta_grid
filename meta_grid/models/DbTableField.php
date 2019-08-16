<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "db_table_field".
 */
class DbTableField extends \app\models\base\DbTableField
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
