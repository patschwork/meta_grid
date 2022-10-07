<?php

namespace app\controllers;

use Yii;
use app\models\Project;
use app\models\ProjectSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\Client;
use app\models\ObjectPersistenceMethod;
use app\models\DatamanagementProcess;
use Da\User\Filter\AccessRuleFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;


/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends Controller
{
	
	private function getClientList()
	{
		// autogeneriert ueber gii/CRUD
		$clientModel = new Client();
		$clients = $clientModel::find()->all();
		$clientList = array();
		foreach($clients as $client)
		{
			$clientList[$client->id] = $client->name;
		}
		return $clientList;
	}

	private function getObjectPersistenceMethodList()
	{
		// autogeneriert ueber gii/CRUD
		$object_persistence_methodModel = new ObjectPersistenceMethod();
		$object_persistence_methods = $object_persistence_methodModel::find()->all();
		$object_persistence_methodList = array();
		foreach($object_persistence_methods as $object_persistence_method)
		{
			$object_persistence_methodList[$object_persistence_method->id] = $object_persistence_method->name;
		}
		return $object_persistence_methodList;
	}

	private function getDatamanagementProcessList()
	{
		// autogeneriert ueber gii/CRUD
		$datamanagement_processModel = new DatamanagementProcess();
		$datamanagement_processs = $datamanagement_processModel::find()->all();
		$datamanagement_processList = array();
		foreach($datamanagement_processs as $datamanagement_process)
		{
			$datamanagement_processList[$datamanagement_process->id] = $datamanagement_process->name;
		}
		return $datamanagement_processList;
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

	public function registerControllerRole()
	{
		$metagrid_role_management = new \vendor\meta_grid\helper\Rolemanagement();
		$metagrid_role_management->registerControllerRole(Yii::$app->controller->id);
	}
    

	private function createProjectPermissions($named_project_id = NULL, $named_project_name = NULL, $named_client_name = NULL)
	{
		$result1 = false;
		$result2 = false;
		if ($named_project_id !== NULL && $named_project_name !== NULL && $named_client_name !== NULL)
		{
			$auth = Yii::$app->authManager;
			$newRoleOrPermName="project-".$named_project_id."-read";
			$checkPerm = $auth->getPermission($newRoleOrPermName);
			if (is_null($checkPerm)) {
				$newAuthObj = $auth->createPermission($newRoleOrPermName);
				$newAuthObj->ruleName = "isNotAGuest";
				$newAuthObj->description = "Read-Permission for project " . $named_project_name . " (" . $named_client_name . ")";
				$newAuthObj->data = ['id' => $named_project_id, 'dataaccessfilter' => 'project', 'right' => 'read'];
				$result1 = $auth->add($newAuthObj);
			}
			$auth = Yii::$app->authManager;
			$newRoleOrPermName="project-".$named_project_id."-write";
			$checkPerm = $auth->getPermission($newRoleOrPermName);
			if (is_null($checkPerm)) {
				$newAuthObj = $auth->createPermission($newRoleOrPermName);
				$newAuthObj->description = "Read-Permission for project " . $named_project_name . " (" . $named_client_name . ")";
				$newAuthObj->ruleName = "isNotAGuest";
				$newAuthObj->data = ['id' => $named_project_id, 'dataaccessfilter' => 'project', 'right' => 'write'];
				$result2 = $auth->add($newAuthObj);
			}
			return $result1 && $result2;
		}

		$projectModel = new Project();
		$projects = $projectModel::find()->all();
		foreach($projects as $project)
		{
			$auth = Yii::$app->authManager;
			$newRoleOrPermName="project-".$project->id."-read";
			$checkPerm = $auth->getPermission($newRoleOrPermName);
			if (is_null($checkPerm)) {
				$newAuthObj = $auth->createPermission($newRoleOrPermName);
				$newAuthObj->ruleName = "isNotAGuest";
				$newAuthObj->description = "Read-Permission for project " . $project->name . " (" . $project->fkClient->name . ")";
				$newAuthObj->data = ['id' => $project->id, 'dataaccessfilter' => 'project', 'right' => 'read'];
				$result1 = $auth->add($newAuthObj);
			}
			$auth = Yii::$app->authManager;
			$newRoleOrPermName="project-".$project->id."-write";
			$checkPerm = $auth->getPermission($newRoleOrPermName);
			if (is_null($checkPerm)) {
				$newAuthObj = $auth->createPermission($newRoleOrPermName);
				$newAuthObj->description = "Read-Permission for project " . $project->name . " (" . $project->fkClient->name . ")";
				$newAuthObj->ruleName = "isNotAGuest";
				$newAuthObj->data = ['id' => $project->id, 'dataaccessfilter' => 'project', 'right' => 'write'];
				$result2 = $auth->add($newAuthObj);
			}
		}
		return $result1 && $result2;
	}

	public function actionCreateprojectpermissions()
	{
		$this->createProjectPermissions();
		echo "Project permissions created if needed.";
		die;
	}
	
	protected function addPermissionToUser($project_id, $user_id)
	{
		$auth = Yii::$app->authManager;
		$newRoleOrPermName="project-".$project_id."-read";
		$perm=$auth->getPermission($newRoleOrPermName);
		$auth->assign($perm, $user_id);
		$newRoleOrPermName="project-".$project_id."-write";
		$perm=$auth->getPermission($newRoleOrPermName);
		$auth->assign($perm, $user_id);
	}

    /**
     * Lists all Project models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Project model.
     * @param int $id ID
     * @return mixed
     */
    public function actionView($id)
    {
		        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
		}

    /**
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($isfrommodal = false, $modalparent = "", $refreshfield = "")
    {
				
		$model = new Project();

		if (Yii::$app->request->post())
		{
			$model->load(Yii::$app->request->post());
		 if (!in_array($model->fkClient->id, Yii::$app->User->identity->permClientsCanEdit)) {throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You have no permission to edit this data.'));
	return;	}    
    	}    
			
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$this->createProjectPermissions($model->id, $model->name, $model->fkClient->name);
			$userId = Yii::$app->User->Id;
			$this->addPermissionToUser($model->id, $userId);	
 	
			if ($isfrommodal) {echo json_encode(['status' => 'Success', 'message' => $model->id]);}
			else {return $this->redirect(['view', 'id' => $model->id]);}

        } else {
			$params = [
                'model' => $model,
                'clientList' => $this->getClientList(),		// autogeneriert ueber gii/CRUD
'object_persistence_methodList' => $this->getObjectPersistenceMethodList(),		// autogeneriert ueber gii/CRUD
'datamanagement_processList' => $this->getDatamanagementProcessList(),		// autogeneriert ueber gii/CRUD
				'modalparent'                   => $modalparent,
				'refreshfield'                  => $refreshfield,				
            ];
			return Yii::$app->request->isAjax ? $this->renderAjax('create', $params) : $this->render('create', $params);
        }
    }

    /**
     * Updates an existing Project model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     */
    public function actionUpdate($id)
    {
				
		$model = $this->findModel($id);

		 if (!in_array($model->fkClient->id, Yii::$app->User->identity->permClientsCanEdit)) {throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You have no permission to edit this data.'));
	return;	}    
		
		
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
							$this->createProjectPermissions();			
		            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'clientList' => $this->getClientList(),		// autogeneriert ueber gii/CRUD
'object_persistence_methodList' => $this->getObjectPersistenceMethodList(),		// autogeneriert ueber gii/CRUD
'datamanagement_processList' => $this->getDatamanagementProcessList(),		// autogeneriert ueber gii/CRUD
				'modalparent'                   => '',
				'refreshfield'                  => '',
            ]);
        }
		    }

    /**
     * Deletes an existing Project model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     */
    public function actionDelete($id)
    {
		 if (!in_array($this->findModel($id)->fkClient->id, Yii::$app->User->identity->permClientsCanEdit)) {throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You have no permission to edit this data.'));
	return;	}    
    
		try {
			$model = $this->findModel($id);
			$model->delete();
			return $this->redirect(\yii\helpers\Url::previous(Yii::$app->controller->id."/INDEX"));
		} catch (\Exception $e) {
			$model->addError(null, $e->getMessage());
			$errMsg = $e->getMessage();
			
			$errMsgAdd = "";
			try{$errMsgAdd = '"'. $model->name . '"';} catch(\Exception $e){}

			if (strpos($errMsg, "Integrity constraint violation")) $errMsg = Yii::t('yii',"The object {errMsgAdd} is still referenced by other objects.", ['errMsgAdd' => $errMsgAdd]);
			Yii::$app->session->setFlash('deleteError', Yii::t('yii','Object can\'t be deleted: ') . $errMsg);
			return $this->redirect(\yii\helpers\Url::previous(Yii::$app->controller->id."/INDEX"));  // Url::remember() is set in index-view
		}

    }

    /**
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Project::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}