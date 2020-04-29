<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "deleted_status".
 *
 * @property integer $id
 * @property string $uuid
 * @property integer $fk_object_type_id
 * @property string $name
 * @property string $description
 *
 * @property Contact[] $contacts
 * @property ContactGroup[] $contactGroups
 * @property DataDeliveryObject[] $dataDeliveryObjects
 * @property DataTransferProcess[] $dataTransferProcesses
 * @property DbDatabase[] $dbDatabases
 * @property DbTable[] $dbTables
 * @property DbTableField[] $dbTableFields
 * @property ObjectType $fkObjectType
 * @property Keyfigure[] $keyfigures
 * @property Parameter[] $parameters
 * @property Scheduling[] $schedulings
 * @property Sourcesystem[] $sourcesystems
 */
class DeletedStatus extends \app\models\base\DeletedStatus
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
