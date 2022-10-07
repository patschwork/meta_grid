<?php

namespace app\controllers;

use Yii;
use app\models\Keyfigure;
use app\models\KeyfigureSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\ObjectType;
use app\models\Project;
use app\models\DeletedStatus;
use app\models\ObjectPersistenceMethod;
use app\models\DatamanagementProcess;
use Da\User\Filter\AccessRuleFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;


/**
 * KeyfigureController implements the CRUD actions for Keyfigure model.
 */
class KeyfigureController extends Controller
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
						'actions' => ['index','view', 'export'],
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
     * Lists all Keyfigure models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new KeyfigureSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Keyfigure model.
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
     * Creates a new Keyfigure model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($isfrommodal = false, $modalparent = "", $refreshfield = "")
    {
				
		$model = new Keyfigure();

		if (Yii::$app->request->post())
		{
			$model->load(Yii::$app->request->post());
		 if (!in_array($model->fkProject->id, Yii::$app->User->identity->permProjectsCanEdit)) {throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You have no permission to edit this data.'));
	return;	}    
    	}    
			
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
 	
			if ($isfrommodal) {echo json_encode(['status' => 'Success', 'message' => $model->id]);}
			else {return $this->redirect(['view', 'id' => $model->id]);}

        } else {
			$params = [
                'model' => $model,
                'object_typeList' => $this->getObjectTypeList(),		// autogeneriert ueber gii/CRUD
'projectList' => $this->getProjectList(),		// autogeneriert ueber gii/CRUD
'deleted_statusList' => $this->getDeletedStatusList(),		// autogeneriert ueber gii/CRUD
'object_persistence_methodList' => $this->getObjectPersistenceMethodList(),		// autogeneriert ueber gii/CRUD
'datamanagement_processList' => $this->getDatamanagementProcessList(),		// autogeneriert ueber gii/CRUD
				'modalparent'                   => $modalparent,
				'refreshfield'                  => $refreshfield,				
            ];
			return Yii::$app->request->isAjax ? $this->renderAjax('create', $params) : $this->render('create', $params);
        }
    }

    /**
     * Updates an existing Keyfigure model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
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
'deleted_statusList' => $this->getDeletedStatusList(),		// autogeneriert ueber gii/CRUD
'object_persistence_methodList' => $this->getObjectPersistenceMethodList(),		// autogeneriert ueber gii/CRUD
'datamanagement_processList' => $this->getDatamanagementProcessList(),		// autogeneriert ueber gii/CRUD
				'modalparent'                   => '',
				'refreshfield'                  => '',
            ]);
        }
		    }

    /**
     * Deletes an existing Keyfigure model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     */
    public function actionDelete($id)
    {
		 if (!in_array($this->findModel($id)->fkProject->id, Yii::$app->User->identity->permProjectsCanEdit)) {throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You have no permission to edit this data.'));
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
     * Finds the Keyfigure model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Keyfigure the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Keyfigure::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    // Custom Implementation for exporting to XML or JSON format
    private function exportBase($format,$queryResult)
    {
    	 
    	if ($format == "" || !($queryResult))
    	{
    		\Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
    		return "No format parameter given or what unknown.<br>Avaiable values for format are: XML,JSON.<br>Avaiable values for what are: main,comments,mappings";
    	}
    	else
    	{
    		$headers = Yii::$app->response->headers;
    		if ($format == "XML")
    		{
    			$headers->add('Content-Type', 'text/xml; charset=utf-8');
    			\Yii::$app->response->format = \yii\web\Response::FORMAT_XML;
    			$export_result = $queryResult;
    
    			return $export_result;
    		};
    
    		if ($format == "JSON")
    		{
    			$headers->add('Content-Type', 'text; charset=utf-8');
    			\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    			$export_result = $queryResult;
    			return $export_result;
    		};
    	}
    }
    
    // Example for call: http://localhost:81/dwh_meta_v2_DEV/frontend/yii/basic/web/index.php?r=keyfigure/export&format=XML&what=
    public function actionExport($format,$what)
    {
		if ($what=="" || $what=="main")
    	{
    		$model =  new \app\models\Keyfigure();
    		return $this->exportBase($format, $model->find()->all());
    	}
    	else
    	{
    	    if ($what=="comments")
    		{
    			$model = new \app\models\Objectcomment;
    			$ot_model = new \app\models\VAllObjectsUnion;
    			$query_ot = $ot_model::find()->distinct(true)->select(['fk_object_type_id'])->where(['object_type_name' => 'keyfigure'])->one(); 
    			$query = $model::find()->where(['ref_fk_object_type_id' => $query_ot->fk_object_type_id])->all();
    			return $this->exportBase($format, $query);
    		}			    		
    	   	if ($what=="mappings")
    		{
    			$model = new \app\models\VAllMappingsUnion;
    			$ot_model = new \app\models\VAllObjectsUnion;
    			$query_ot = $ot_model::find()->distinct(true)->select(['fk_object_type_id'])->where(['object_type_name' => 'keyfigure'])->one();
    			$query = $model::find()->where(['filter_ref_fk_object_type_id' => $query_ot->fk_object_type_id])->all();
    			return $this->exportBase($format, $query);
    		}			    		
    	    if ($what!="!elsecasedummy!")
    		{
    			return $this->exportBase($format, null);
    		}			    		
    	}
    }
    
    public function actionExporttest($format)
    {
    	return "";
		if ($format == "XML")
    	{
   			$headers->add('Content-Type', 'text/xml; charset=utf-8');
    		\Yii::$app->response->format = \yii\web\Response::FORMAT_XML;
    	}    	 

    	if ($format == "JSON")
    	{
    		$headers->add('Content-Type', 'text; charset=utf-8');
    		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	}
    	 
    	if ($format == "")
    	{
    		\Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
    		return "No format parameter given. Avaiable values are: XML,JSON";
    	}
    	 
		// SQL Query klappt auch
    	/*
			 SELECT 
				 k.*
				,c.uuid AS uuid_comment
				,c.comment
			FROM keyfigure k
			LEFT JOIN object_comment c ON
				c.ref_fk_object_id=k.id
					AND
				c.ref_fk_object_type_id=k.fk_object_type_id
			 * */
		$query = new \yii\db\Query;
		$query->select('k.*
					,c.uuid AS uuid_comment
					,c.comment')
 			->from('keyfigure k')
   			->leftJoin('object_comment c', 'c.ref_fk_object_id=k.id
						AND
					c.ref_fk_object_type_id=k.fk_object_type_id')
   			;
		$command = $query->createCommand();
		$export_result = $command->queryAll();
    			
  		return $export_result;

    }
}