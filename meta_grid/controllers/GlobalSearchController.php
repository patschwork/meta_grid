<?php

namespace app\controllers;

use Yii;
use app\models\VAllObjectsUnion;
use app\models\GlobalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\ObjectType;
use app\models\Client;
use app\models\Project;
use Da\User\Filter\AccessRuleFilter;
use yii\filters\AccessControl;

/**
 * GlobalSearchController implements the CRUD actions for VAllObjectsUnion model.
 */
class GlobalSearchController extends Controller
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
    	$newAuthorRole = $this->createRole("author", "Role", "May edit all objecttypes", "isNotAGuest", null, null);
    	if (!is_null($newAuthorRole))
    	{
    		Yii::$app->authManager->addChild($newAuthorRole, Yii::$app->authManager->getRole("global-view"));
    	}
    
    	$newRoleName = 'view' ."-" . Yii::$app->controller->id;
    	$this->createRole($newRoleName, "Perm", "May only view objecttype " . Yii::$app->controller->id, "isNotAGuest", "global-view", null);
    }
    
    
    /**
     * Lists all VAllObjectsUnion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GlobalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single VAllObjectsUnion model.
     * @param integer $id
     * @param integer $fk_object_type_id
     * @return mixed
     */
    public function actionView($id, $fk_object_type_id)
    {
		        return $this->render('view', [
            'model' => $this->findModel($id, $fk_object_type_id),
        ]);
		}

    /**
     * Creates a new VAllObjectsUnion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		        $model = new VAllObjectsUnion();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'fk_object_type_id' => $model->fk_object_type_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'object_typeList' => $this->getObjectTypeList(),		// autogeneriert ueber gii/CRUD
'clientList' => $this->getClientList(),		// autogeneriert ueber gii/CRUD
'projectList' => $this->getProjectList(),		// autogeneriert ueber gii/CRUD
            ]);
        }
		    }

    /**
     * Updates an existing VAllObjectsUnion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param integer $fk_object_type_id
     * @return mixed
     */
    public function actionUpdate($id, $fk_object_type_id)
    {
				$model = $this->findModel($id, $fk_object_type_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'fk_object_type_id' => $model->fk_object_type_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'object_typeList' => $this->getObjectTypeList(),		// autogeneriert ueber gii/CRUD
'clientList' => $this->getClientList(),		// autogeneriert ueber gii/CRUD
'projectList' => $this->getProjectList(),		// autogeneriert ueber gii/CRUD
            ]);
        }
		    }

    /**
     * Deletes an existing VAllObjectsUnion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param integer $fk_object_type_id
     * @return mixed
     */
    public function actionDelete($id, $fk_object_type_id)
    {
        $this->findModel($id, $fk_object_type_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the VAllObjectsUnion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param integer $fk_object_type_id
     * @return VAllObjectsUnion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $fk_object_type_id)
    {
        if (($model = VAllObjectsUnion::findOne(['id' => $id, 'fk_object_type_id' => $fk_object_type_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}