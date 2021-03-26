<?php

namespace app\controllers;

use Yii;
use app\models\DataDeliveryObject;
use app\models\DataDeliveryObjectSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\ObjectType;
use app\models\Project;
use app\models\Tool;
use app\models\DataDeliveryType;
use app\models\ContactGroup;
use app\models\DeletedStatus;
use Da\User\Filter\AccessRuleFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;


/**
 * DatadeliveryobjectController implements the CRUD actions for DataDeliveryObject model.
 */
class DatadeliveryobjectController extends Controller
{
	
	private function getObjectTypeList()
	{
		// autogeneriert ueber gii/CRUD
		$object_typeModel = new ObjectType();
		$object_types = $object_typeModel::find()->all();
		$object_typeList = array();
		foreach($object_types as $object_type)
		{
			$object_typeList[$object_type->id] = $object_type->name;
		}
		return $object_typeList;
	}

	private function getProjectList()
	{
		// autogeneriert ueber gii/CRUD
		$projectModel = new Project();
		$projects = $projectModel::find()->all();
		$projectList = array();
		foreach($projects as $project)
		{
			$projectList[$project->id] = $project->name;
		}
		return $projectList;
	}

	private function getToolList()
	{
		// autogeneriert ueber gii/CRUD
		$toolModel = new Tool();
		$tools = $toolModel::find()->all();
		$toolList = array();
		foreach($tools as $tool)
		{
			$toolList[$tool->id] = $tool->tool_name;
		}
		return $toolList;
	}

	private function getDataDeliveryTypeList()
	{
		// autogeneriert ueber gii/CRUD
		$data_delivery_typeModel = new DataDeliveryType();
		$data_delivery_types = $data_delivery_typeModel::find()->all();
		$data_delivery_typeList = array();
		foreach($data_delivery_types as $data_delivery_type)
		{
			$data_delivery_typeList[$data_delivery_type->id] = $data_delivery_type->name;
		}
		return $data_delivery_typeList;
	}

	private function getContactGroupAsDataOwnerList()
	{
		// autogeneriert ueber gii/CRUD
		$contact_group_as_data_ownerModel = new ContactGroup();
		$contact_group_as_data_owners = $contact_group_as_data_ownerModel::find()->all();
		$contact_group_as_data_ownerList = array();
		$contact_group_as_data_ownerList[null] = null;
		foreach($contact_group_as_data_owners as $contact_group_as_data_owner)
		{
			$contact_group_as_data_ownerList[$contact_group_as_data_owner->id] = $contact_group_as_data_owner->name;
		}
		return $contact_group_as_data_ownerList;
	}

	private function getDeletedStatusList()
	{
		// autogeneriert ueber gii/CRUD
		$deleted_statusModel = new DeletedStatus();
		$deleted_statuss = $deleted_statusModel::find()->all();
		$deleted_statusList = array();
		$deleted_statusList[null] = null;
		foreach($deleted_statuss as $deleted_status)
		{
			$deleted_statusList[$deleted_status->id] = $deleted_status->name;
		}
		return $deleted_statusList;
	}
	
    public function behaviors()
    {
		if (YII_ENV_DEV)
		{
			$this->registerControllerRole();
		}
		
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
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
						'actions' => ['index','view'],
						'roles' => ['author', 'global-view', 'view' ."-" . Yii::$app->controller->id],
					],
					[
						'allow' => true,
						'actions' => ['create', 'update', 'createexternal'],
						'roles' => ['author', 'global-create', 'create' ."-" . Yii::$app->controller->id],
					],
					[
						'allow' => true,
						'actions' => ['delete'],
						'roles' => ['author', 'global-delete', 'delete' ."-" . Yii::$app->controller->id],
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
    
	private function registerControllerRole()
	{

		$this->createRole("global-view", "Role", "May view all objectstypes", "isNotAGuest", null, null);
		$this->createRole("global-create", "Role", "May create all objectstypes", "isNotAGuest", null, null);
		$this->createRole("global-delete", "Role", "May delete all objectstypes", "isNotAGuest", null, null);
		$newAuthorRole = $this->createRole("author", "Role", "May edit all objecttypes", "isNotAGuest", null, null);		
		if (!is_null($newAuthorRole))
		{			
			Yii::$app->authManager->addChild($newAuthorRole, Yii::$app->authManager->getRole("global-view"));
			Yii::$app->authManager->addChild($newAuthorRole, Yii::$app->authManager->getRole("global-create"));
			Yii::$app->authManager->addChild($newAuthorRole, Yii::$app->authManager->getRole("global-delete"));
		}

		$newRoleName = 'view' ."-" . Yii::$app->controller->id;
		$this->createRole($newRoleName, "Perm", "May only view objecttype " . Yii::$app->controller->id, "isNotAGuest", "global-view", null);
		
		$newRoleName = 'create' ."-" . Yii::$app->controller->id;
		$this->createRole($newRoleName, "Perm", "May only create objecttype " . Yii::$app->controller->id, "isNotAGuest", "global-create", null);
		
		$newRoleName = 'delete' ."-" . Yii::$app->controller->id;
		$this->createRole($newRoleName, "Perm", "May only delete objecttype " . Yii::$app->controller->id, "isNotAGuest", "global-delete", null);
	}
    


    /**
     * Lists all DataDeliveryObject models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DataDeliveryObjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DataDeliveryObject model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
		}

    /**
     * Creates a new DataDeliveryObject model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
				
		$model = new DataDeliveryObject();

		if (Yii::$app->request->post())
		{
			$model->load(Yii::$app->request->post());
		 if (!in_array($model->fkProject->id, Yii::$app->User->identity->permProjectsCanEdit)) {throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You have no permission to edit this data.'));
	return;	}    
    	}    
			
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'object_typeList' => $this->getObjectTypeList(),		// autogeneriert ueber gii/CRUD
'projectList' => $this->getProjectList(),		// autogeneriert ueber gii/CRUD
'toolList' => $this->getToolList(),		// autogeneriert ueber gii/CRUD
'data_delivery_typeList' => $this->getDataDeliveryTypeList(),		// autogeneriert ueber gii/CRUD
'contact_group_as_data_ownerList' => $this->getContactGroupAsDataOwnerList(),		// autogeneriert ueber gii/CRUD
'deleted_statusList' => $this->getDeletedStatusList(),		// autogeneriert ueber gii/CRUD
            ]);
        }
    }

    /**
     * Updates an existing DataDeliveryObject model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
				
		$model = $this->findModel($id);

		 if (!in_array($model->fkProject->id, Yii::$app->User->identity->permProjectsCanEdit)) {throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You have no permission to edit this data.'));
	return;	}    
		
		
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
				            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'object_typeList' => $this->getObjectTypeList(),		// autogeneriert ueber gii/CRUD
'projectList' => $this->getProjectList(),		// autogeneriert ueber gii/CRUD
'toolList' => $this->getToolList(),		// autogeneriert ueber gii/CRUD
'data_delivery_typeList' => $this->getDataDeliveryTypeList(),		// autogeneriert ueber gii/CRUD
'contact_group_as_data_ownerList' => $this->getContactGroupAsDataOwnerList(),		// autogeneriert ueber gii/CRUD
'deleted_statusList' => $this->getDeletedStatusList(),		// autogeneriert ueber gii/CRUD
            ]);
        }
		    }

    /**
     * Deletes an existing DataDeliveryObject model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		 if (!in_array($this->findModel($id)->fkProject->id, Yii::$app->User->identity->permProjectsCanEdit)) {throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You have no permission to edit this data.'));
	return;	}    
    
		try {
			$model = $this->findModel($id);
			$model->delete();
			return $this->redirect(Url::previous());
		} catch (\Exception $e) {
			$model->addError(null, $e->getMessage());
			$errMsg = $e->getMessage();
			
			$errMsgAdd = "";
			try{$errMsgAdd = '"'. $model->name . '"';} catch(\Exception $e){}

			if (strpos($errMsg, "Integrity constraint violation")) $errMsg = Yii::t('yii',"The object {errMsgAdd} is still referenced by other objects.", ['errMsgAdd' => $errMsgAdd]);
			Yii::$app->session->setFlash('deleteError', Yii::t('yii','Object can\'t be deleted: ') . $errMsg);
			return $this->redirect(Url::previous());  // Url::remember() is set in index-view
		}

    }

    /**
     * Finds the DataDeliveryObject model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DataDeliveryObject the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DataDeliveryObject::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}