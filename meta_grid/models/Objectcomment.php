<?php

namespace app\models;

use Yii;

class Objectcomment extends \app\models\base\Objectcomment
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
        $permProjectsCanSee = Yii::$app->User->identity->permProjectsCanSee;

        $permClientsCanSeeString = array_map('strval', $permClientsCanSee);
        $permProjectsCanSeeString = array_map('strval', $permProjectsCanSee);


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


        $subQuery = (new yii\db\Query())
        ->select(
            [
                'object_comment.id'
            ])
        ->from('object_comment')
        ->leftJoin('object_type', 'object_comment.ref_fk_object_type_id = object_type.id')
        ->leftJoin('v_All_Objects_Union', 'object_comment.id = v_All_Objects_Union.id AND object_comment.fk_object_type_id = v_All_Objects_Union.fk_object_type_id')
        ->where(['or',
            ['in','fk_client_id', $permClientsCanSeeString],
            ['in','fk_project_id', $permProjectsCanSeeString]
        ])
        ->andFilterWhere(['in', 'object_type.name', $roleFilter]);


        $obj=Yii::createObject(yii\db\ActiveQuery::className(), [get_called_class()])
        ->where(['id' => $subQuery]); // Use subquery in WHERE condition


        return $obj;		
	}

	
	public static function findBySql($sql, $params = [])
	{
		throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'Implementation deactivated.'));
		return null;
			
	}
}
