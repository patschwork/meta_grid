<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contact".
 */
class Contact extends \app\models\base\Contact
{
	// // Patrick, 2016-01-16, Labels angepaßt
    // public function attributeLabels()
    // {
        // return [
        	// 'fk_contact_group_id' => Yii::t('app', 'Contact Group'),
            // 'fk_client_id' => Yii::t('app', 'Client'),
            // 'email' => Yii::t('app', 'E-Mail'),
            // 'ldap_cn' => Yii::t('app', 'LDAP CN'),
			// 'clientName' => Yii::t('app', 'Client X'),
        // ];
    // }
    
    // http://www.yiiframework.com/wiki/621/filter-sort-by-calculated-related-fields-in-gridview-yii-2-0/
    /* Getter for client name */
    public function getClientName() {
    	return $this->fkClient->name;
    }
	
	// Only for beeing im sync with other models. Useful for showing the name in breadcrumbs
    public function getName()
    {
		return $this->givenname . " " . $this->surname;
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
