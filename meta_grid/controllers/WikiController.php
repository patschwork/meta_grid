<?php

namespace app\controllers;

use Yii;
use app\models\Wiki;
use app\models\WikiSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\ObjectType;
use app\models\Project;
use app\models\User;
use Da\User\Filter\AccessRuleFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;


/**
 * WikiController implements the CRUD actions for Wiki model.
 */
class WikiController extends Controller
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

	private function getUserList()
	{
		// autogeneriert ueber gii/CRUD
		// $userModel = new User();
		// $users = $userModel::find()->all();
		$userList = array();
		// foreach($users as $user)
		// {
		// 	$userList[$user->id] = $user->name;
		// }
		return $userList;
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
    


    /**
     * Lists all Wiki models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WikiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Wiki model.
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
     * Creates a new Wiki model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($isfrommodal = false, $modalparent = "", $refreshfield = "")
    {
		$initMode = "personal";		
		$model = new Wiki();

		if (Yii::$app->request->post())
		{
			$model->load(Yii::$app->request->post());

			$wikiMode = Yii::$app->request->post()["WikiMode"];
			if ($wikiMode=="personal")
			{
				$model->fk_user_id = Yii::$app->user->id;
			}
		 
		if (!empty($model->fkProject->id))	
			 if (!in_array($model->fkProject->id, Yii::$app->User->identity->permProjectsCanEdit)) {
				throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You have no permission to edit this data.'));
				return;	
			}    
    	}    
			
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
 	
			if ($isfrommodal) {echo json_encode(['status' => 'Success', 'message' => $model->id]);}
			else {return $this->redirect(['view', 'id' => $model->id]);}

        } else {
			$params = [
                'model' => $model,
                'object_typeList' => $this->getObjectTypeList(),		// autogeneriert ueber gii/CRUD
				'projectList' => $this->getProjectList(),		// autogeneriert ueber gii/CRUD
				'userList' => $this->getUserList(),		// autogeneriert ueber gii/CRUD
				'modalparent'                   => $modalparent,
				'refreshfield'                  => $refreshfield,	
				'projectListDisables' => $this->getdisabledProjectList(),
                'mode' => $initMode,			
            ];
			return Yii::$app->request->isAjax ? $this->renderAjax('create', $params) : $this->render('create', $params);
        }
    }

    /**
     * Updates an existing Wiki model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$model = $this->findModel($id);


		$mode = "personal";
		if ($model->fk_project_id !== null)
		{
			$mode = "project";
		}
		if ($model->fk_user_id !== null)
		{
			$mode = "personal";
		}

		if ($model->fk_project_id === null && $model->fk_user_id === null)
		{
			$mode = "global";
		}

		if (!empty($model->fkProject->id))
		if (!in_array($model->fkProject->id, Yii::$app->User->identity->permProjectsCanEdit)) 
		{
			throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You have no permission to edit this data.'));
			return;	
		}     
		

		if ($model->load(Yii::$app->request->post()))
		{
			$tagMode = Yii::$app->request->post()["WikiMode"];
			if ($tagMode=="personal")
			{
				$model->fk_user_id = Yii::$app->user->id;
				$model->fk_project_id = null;
			}
			if ($tagMode=="global")
			{
				$model->fk_user_id = null;
				$model->fk_project_id = null;
			}

			if ($model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
			}
		}
        else {
            return $this->render('update', [
                'model' => $model,
                'object_typeList' => $this->getObjectTypeList(),		// autogeneriert ueber gii/CRUD
				'projectList' => $this->getProjectList(),		// autogeneriert ueber gii/CRUD
				'userList' => $this->getUserList(),		// autogeneriert ueber gii/CRUD
				'modalparent'                   => '',
				'refreshfield'                  => '',
				'projectListDisables' => $this->getdisabledProjectList(),
                'mode' => $mode,			

            ]);
        }
		    }

    /**
     * Deletes an existing Wiki model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     */
    public function actionDelete($id)
    {
    
		try {
			$model = $this->findModel($id);

				if (!empty($model->fkProject->id))	
					if (!in_array($this->findModel($id)->fkProject->id, Yii::$app->User->identity->permProjectsCanEdit)) 
					{
						throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You have no permission to edit this data.'));
						return;	
					}    


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

	// Custom functions for Wiki

	/**
	 * User may see projects but may not edit/delete.
	 * For this case the project item will be shown but is not selectable in the dropdown component.
	 */
	private function getdisabledProjectList()
	{
		$disProjectList = array();
		$disProjectList = $this->getProjectList();
		foreach($disProjectList as $key => $project)
		{
			if ($key == null) continue;
			if (!in_array($key, Yii::$app->User->identity->permProjectsCanEdit))
			{
				$disProjectList[$key] = ['disabled' => true];
			}
			else
			{
				$disProjectList[$key] = [];
			}
		}
		return $disProjectList;
	}

    /**
     * Finds the Wiki model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Wiki the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Wiki::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}