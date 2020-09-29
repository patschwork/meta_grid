<?php

namespace app\controllers;

use Yii;
use app\models\Project;
use app\models\ProjectSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\Client;
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
    

	private function createProjectPermissions()
	{
		$projectModel = new Project();
		$projects = $projectModel::find()->all();
		foreach($projects as $project)
		{
			// $clientList[$client->id] = $client->name;

			$auth = Yii::$app->authManager;
			$newRoleOrPermName="project-".$project->id."-read";
			$checkPerm = $auth->getPermission($newRoleOrPermName);
			if (is_null($checkPerm)) {
				$newAuthObj = $auth->createPermission($newRoleOrPermName);
				$newAuthObj->ruleName = "isNotAGuest";
				$newAuthObj->description = "Read-Permission for project " . $project->name . " (" . $project->fkClient->name . ")";
				$newAuthObj->data = ['id' => $project->id, 'dataaccessfilter' => 'project', 'right' => 'read'];
				$auth->add($newAuthObj);
			}
			$auth = Yii::$app->authManager;
			$newRoleOrPermName="project-".$project->id."-write";
			$checkPerm = $auth->getPermission($newRoleOrPermName);
			if (is_null($checkPerm)) {
				$newAuthObj = $auth->createPermission($newRoleOrPermName);
				$newAuthObj->description = "Read-Permission for project " . $project->name . " (" . $project->fkClient->name . ")";
				$newAuthObj->ruleName = "isNotAGuest";
				$newAuthObj->data = ['id' => $project->id, 'dataaccessfilter' => 'project', 'right' => 'write'];
				$auth->add($newAuthObj);
			}
		}
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
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
				
		$model = new Project();

		if (Yii::$app->request->post())
		{
			$model->load(Yii::$app->request->post());
		 if (!in_array($model->fkClient->id, Yii::$app->User->identity->permClientsCanEdit)) {throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You have no permission to edit this data.'));
	return;	}    
    	}    
			
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$this->createProjectPermissions();
			$userId = Yii::$app->User->Id;
			$this->addPermissionToUser($model->id, $userId);	
        	return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'clientList' => $this->getClientList(),		// autogeneriert ueber gii/CRUD
            ]);
        }
    }

    /**
     * Updates an existing Project model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
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
            ]);
        }
		    }

    /**
     * Deletes an existing Project model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		 if (!in_array($this->findModel($id)->fkClient->id, Yii::$app->User->identity->permClientsCanEdit)) {throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You have no permission to edit this data.'));
	return;	}    
    
		try {
			$model = $this->findModel($id);
			$model->delete();
			return $this->redirect(['index']);
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
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
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