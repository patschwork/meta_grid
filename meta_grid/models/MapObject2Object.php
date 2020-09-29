<?php

namespace app\models;

use Yii;

class MapObject2Object extends \app\models\base\MapObject2Object
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
	
		$permProjectsCanSee = Yii::$app->User->identity->permProjectsCanSee;
		$permClientsCanSee = Yii::$app->User->identity->permClientsCanSee;

		$chkPermModel = VAllMappingsUnion::find()->select(['id'])
			->Where(['in','fk_project_id', $permProjectsCanSee])
			->orWhere(['in','fk_client_id', $permClientsCanSee])
			;
		if (!Yii::$app->User->identity->isAdmin)
		{
			$obj->Where([
					'in','id', $chkPermModel
			]);
		}
		return $obj;
	}
	
	public static function findBySql($sql, $params = [])
	{
		throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'Implementation deactivated.'));
		return null;
			
	}

    public function rules()
    {
		$addionalRules = array([['ref_fk_object_type_id_2'], 'required', 'message'=>Yii::t('app', 'Please choose a mapping object.')]);
		return array_merge(parent::rules(), $addionalRules);
    }
}
