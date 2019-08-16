<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contact_group".
 */
class ContactGroup extends \app\models\base\ContactGroup
{   
	// public function attributeLabels()
    // {
        // return [
            // 'fk_client_id' => Yii::t('app', 'Client'),
        // ];
    // }
    
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
    		$obj->Where([
    				'in','fk_client_id', $permClientsCanSee
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
