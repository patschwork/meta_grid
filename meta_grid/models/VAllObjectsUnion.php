<?php

namespace app\models;

use Yii;
use app\models\ObjectType;

/**
 * This is the model class for table "v_All_Objects_Union".
 */
class VAllObjectsUnion extends \app\models\base\VAllObjectsUnion
{
	public static function primaryKey()
    {
        return array('id','fk_object_type_id');
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
    	$permProjectsCanSee = Yii::$app->User->identity->permProjectsCanSee;
    
    	$obj=Yii::createObject(yii\db\ActiveQuery::className(), [get_called_class()]);
    	if (!Yii::$app->User->identity->isAdmin)
    	{
			$obj->Where(['or',
				['in','fk_client_id', $permClientsCanSee],
				['in','fk_project_id', $permProjectsCanSee]
				]);

			$allObjTypes = ObjectType::find()->select('name')->distinct()->asArray()->all();
			$roleFilter = array();
			$roleFilter[-1] = "empty";
			foreach($allObjTypes as $key => $objType)
			{   
				$roleObjTypeName=str_replace("_","",$objType["name"]);
				if ($objType["name"] === "contact_group") {$roleObjTypeName = "contact-group";}
				if (Yii::$app->User->can("view-" . $roleObjTypeName)) {
					$roleFilter[$key] = $objType["name"];
				}    
			}
			$obj->andFilterWhere(['in', 'object_type_name', $roleFilter]);
    	}
    	return $obj;
    }
    
    public static function findBySql($sql, $params = [])
    {
    	throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'Implementation deactivated.'));
    	return null;
    		
    }
}
