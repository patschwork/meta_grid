<?php

namespace app\controllers;

use Yii;
use app\models\Project;
use app\models\ProjectSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\Client;

use yii\data\ActiveDataProvider;
use app\models\VAllMappingsUnion;
use app\models\Sourcesystem;
use app\models\DbDatabase;
use app\models\DbTable;
use app\models\DbTableField;
use app\models\Attribute;
use app\models\DataDeliveryObject;
use app\models\DataTransferProcess;
use app\models\Glossary;
use yii\db\ActiveQuery;
use Da\User\Filter\AccessRuleFilter;
use yii\filters\AccessControl;
/**
 * DocumentationController (not generated with GII).
 */
class DocumentationController extends Controller
{
	private $dataCollection = array();
	
	public function behaviors()
	{
		if (YII_ENV_DEV)
		{
			$this->registerControllerRole();
		}
	
		return [
				'access' => [
						'class' => AccessControl::class,
						'ruleConfig' => [
								'class' => AccessRuleFilter::class,
						],
						'rules' => [
								[
										'allow' => true,
										'roles' => ['admin'],
								],
								[
										'allow' => true,
										'actions' => ['createdocumentation'],
										'roles' => ['author', 'global-view', 'view' ."-" . Yii::$app->controller->id],
								],
						],
				],
		];
	}
	
	private function createRole($newRoleOrPermName, $authType, $description, $ruleName, $childRole, $childPerm)
	{
		$auth = Yii::$app->authManager;
		$checkRole = $auth->getRole($newRoleOrPermName);
		$checkPerm = $auth->getPermission($newRoleOrPermName);
		if ((is_null($checkRole) && $authType==="Role") || (is_null($checkPerm) && $authType==="Perm"))
		{
			if ($authType==="Role")
			{
				$newAuthObj = $auth->createRole($newRoleOrPermName);
			}
			else
			{
				if ($authType==="Perm")
				{
					$newAuthObj = $auth->createPermission($newRoleOrPermName);
				}
				else
				{
					throw "No supported authType";
				}
			}
			$newAuthObj->ruleName = $ruleName;
			if (!is_null($description))
			{
				$newAuthObj->description = $description;
			}
			 
			$auth->add($newAuthObj);
	
			if (!is_null($childRole))
			{
				$auth->addChild($auth->getRole($childRole), $newAuthObj);
			}
	
			if (!is_null($childPerm))
			{
				$auth->addChild($auth->getRole($childPerm), $newAuthObj);
			}
			return $newAuthObj;
		}
		return null;
	}
	
	public function registerControllerRole()
	{
		$metagrid_role_management = new \vendor\meta_grid\helper\Rolemanagement();
		$metagrid_role_management->registerControllerRole(Yii::$app->controller->id);
	}
		
    /**
     * Collects the object information and fills private array variable.
     * @param ActiveQuery $modelQuery
     * @param integer $fk_project_id
     * @param string $objName
     * @param integer $sortOrder
     * @return nothing
     */    
    private function collectData($modelQuery, $fk_project_id, $objName, $sortOrder)
    {
    	$query = $modelQuery;
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    	]);
    	$query->andFilterWhere([
    			'fk_project_id' => $fk_project_id,
    	]);
    	 
    	$object_name = Yii::t('app', $objName);
    	
    	foreach ($dataProvider->models as $key => $item)
    	{
    		$this->dataCollection[$sortOrder][$object_name][$key][Yii::t('app', 'Name')] = $item->name;
    		$this->dataCollection[$sortOrder][$object_name][$key][Yii::t('app', 'Description')] = $item->description;
    	    if ($item->fk_object_type_id==4)
    		{
    			$this->dataCollection[$sortOrder][$object_name][$key][Yii::t('app', 'Context')] = $item->fk_db_table_context_id == "" ? $item->fk_db_table_context_id : $item->fkDbTableContext->name;
    			$this->dataCollection[$sortOrder][$object_name][$key][Yii::t('app', 'Type')] = $item->fk_db_table_type_id == "" ? $item->fk_db_table_type_id : $item->fkDbTableType->name;
    		}
    	    if ($item->fk_object_type_id==5)
    		{
    			$this->dataCollection[$sortOrder][$object_name][$key][Yii::t('app', 'Belongs to Table')] = $item->fk_db_table_id == "" ? $item->fk_db_table_id : $item->fkDbTable->name;
    			$this->dataCollection[$sortOrder][$object_name][$key][Yii::t('app', 'Data Type')] = $item->datatype;
    		}
    	    if ($item->fk_object_type_id==3)
    		{
    			$this->dataCollection[$sortOrder][$object_name][$key][Yii::t('app', 'Type')] = $item->fk_data_delivery_type_id == "" ? $item->fk_data_delivery_type_id : $item->fkDataDeliveryType->name;
    			$this->dataCollection[$sortOrder][$object_name][$key][Yii::t('app', 'Data Owner')] = $item->fk_contact_group_id_as_data_owner == "" ? $item->fk_contact_group_id_as_data_owner : $item->fkContactGroupIdAsDataOwner->name;
    		}
    		if ($item->fk_object_type_id==13)
    		{
    			$this->dataCollection[$sortOrder][$object_name][$key][Yii::t('app', 'DataTransferType')] = $item->fk_data_transfer_type_id == "" ? $item->fk_data_transfer_type_id : $item->fkDataTransferType->name;
    		}
    		
    		$mapObject2ObjectSearchModel = new \app\models\VAllMappingsUnion();
    		$queryMapObject2Object = \app\models\VAllMappingsUnion::find();
    		$mapObject2ObjectDataProvider = new ActiveDataProvider([
    				'query' => $queryMapObject2Object,
    		]);
    		$queryMapObject2Object->andFilterWhere([
    				'filter_ref_fk_object_id' => $item->id,
    				'filter_ref_fk_object_type_id' => $item->fk_object_type_id,
    		]);

    		foreach ($mapObject2ObjectDataProvider->models as $mapKey => $mapItem)
    		{
    			$this->dataCollection[$sortOrder][$object_name][$key][Yii::t('app', 'Mapping')][$mapKey]['name'] = $mapItem->name;
    			$this->dataCollection[$sortOrder][$object_name][$key][Yii::t('app', 'Mapping')][$mapKey]['object_type_name'] = $mapItem->object_type_name;
    		}
    	}
    }

    /**
     * Creates a html output as documentation template.
     * Call example: http://localhost:81/dwh_meta_v2_DEV/frontend/yii/basic/web/index.php?r=documentation%2Fcreatedocumentation&project_id=1
     * @param integer $project_id
     * @return mixed
     */    
    public function actionCreatedocumentation($project_id)
    {
		// Collect data
   	 
    	$dataCollection = array();
    	
    	// Projects
    	$modelProject = $this->findProjectModel($project_id);
    	$sortOrder = 1;
    	$object_name = Yii::t('app', "Project");
    	$this->dataCollection[$sortOrder][$object_name][0][Yii::t('app', 'Client')] = $modelProject->fkClient->name;
    	$this->dataCollection[$sortOrder][$object_name][0][Yii::t('app', 'Project')] = $modelProject->name;
    	$this->dataCollection[$sortOrder][$object_name][0][Yii::t('app', 'Description')] = $modelProject->description;

		$this->collectData(Sourcesystem::find(), $project_id, "Sourcesystems", 2);
    	$this->collectData(DbDatabase::find(), $project_id, "Databases", 3);
    	$this->collectData(DbTable::find(), $project_id, "Tables", 4);
    	$this->collectData(DbTableField::find(), $project_id, "Table Fields", 5);
    	$this->collectData(Attribute::find(), $project_id, "Attributes", 6);
    	$this->collectData(DataDeliveryObject::find(), $project_id, "Data Delivery Objects", 7);
    	$this->collectData(DataTransferProcess::find(), $project_id, "Data Transfer Process", 8);
    	$this->collectData(Glossary::find(), $project_id, "Glossary", 9);
    	
    	return $this->render('create_documentation', [
    			'dataCollection' => $this->dataCollection,
    	]);
    }
    
    /**
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findProjectModel($id)
    {
    	if (($model = Project::findOne($id)) !== null) {
    		return $model;
    	} else {
    		throw new NotFoundHttpException('The requested page does not exist.');
    	}
    }
    
}
