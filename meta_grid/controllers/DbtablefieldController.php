<?php

namespace app\controllers;

use Yii;
use app\models\DbTableField;
use app\models\DbTableFieldSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\ObjectType;
use app\models\Project;
use app\models\DbTable;
use app\models\DeletedStatus;
use app\models\ObjectPersistenceMethod;
use app\models\DatamanagementProcess;
use Da\User\Filter\AccessRuleFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii2tech\csvgrid\CsvGrid;

/**
 * DbtablefieldController implements the CRUD actions for DbTableField model.
 */
class DbtablefieldController extends Controller
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

	private function getDbTableList()
	{
		// autogeneriert ueber gii/CRUD
		$db_tableModel = new DbTable();
		$db_tables = $db_tableModel::find()->all();
		$db_tableList = array();
		$db_tableList[null] = null;
		foreach($db_tables as $db_table)
		{
			$db_tableList[$db_table->id] = $db_table->name;
		}
		return $db_tableList;
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
						'actions' => ['index','view','export_csv'],
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
     * Lists all DbTableField models.
     * @return mixed
     */
    public function actionIndex()
    {
	    
	$Utils = new \vendor\meta_grid\helper\Utils();
        $app_config_import_processing_time_limit = $Utils->get_app_config("dbtablefield_time_limit");
	$app_config_import_processing_memory_limit = $Utils->get_app_config("dbtablefield_memory_limit");
	set_time_limit($app_config_import_processing_time_limit);
	ini_set('memory_limit', $app_config_import_processing_memory_limit."M");
	    
        $searchModel = new DbTableFieldSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DbTableField model.
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
     * Creates a new DbTableField model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($isfrommodal = false, $modalparent = "", $refreshfield = "")
    {
				
		$model = new DbTableField();

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
'db_tableList' => $this->getDbTableList(),		// autogeneriert ueber gii/CRUD
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
     * Updates an existing DbTableField model.
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
'db_tableList' => $this->getDbTableList(),		// autogeneriert ueber gii/CRUD
'deleted_statusList' => $this->getDeletedStatusList(),		// autogeneriert ueber gii/CRUD
'object_persistence_methodList' => $this->getObjectPersistenceMethodList(),		// autogeneriert ueber gii/CRUD
'datamanagement_processList' => $this->getDatamanagementProcessList(),		// autogeneriert ueber gii/CRUD
				'modalparent'                   => '',
				'refreshfield'                  => '',
            ]);
        }
		    }

    /**
     * Deletes an existing DbTableField model.
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
     * Finds the DbTableField model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return DbTableField the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DbTableField::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	// Custom function.
	// Called from button in from views\dbtablefield\_index_external.php (used in db_table)
    public function actionCreateexternal($fk_db_table_id)
    {
    	$model = new DbTableField();
    
    	if ($model->load(Yii::$app->request->post()) && $model->save()) {
    		return $this->redirect(['view', 'id' => $model->id]);
    	} else {
    		return $this->render('create', [
    				'model' => $model,
    				'object_typeList' => $this->getObjectTypeList(),		// autogeneriert ueber gii/CRUD
    				'projectList' => $this->getProjectList(),		// autogeneriert ueber gii/CRUD
    				'db_tableList' => $this->getDbTableList(),		// autogeneriert ueber gii/CRUD
    				'fk_db_table_id' => $fk_db_table_id,
    		]);
    	}
    }

	private function replaceKeys($oldKey, $newKey, array $input){
		$return = array(); 
		foreach ($input as $key => $value) {
			if ($key===$oldKey)
				$key = $newKey;
	
			if (is_array($value))
				$value = $this->replaceKeys( $oldKey, $newKey, $value);
	
			$return[$key] = $value;
		}
		return $return; 
	}

    protected function CreateCSV($sessionPrepKey, $exportFilename, $export_fk_ids = 0)
    {
		$searchModel = new \app\models\ExportFileDbTableFieldResultSearch();
		$queryParams = $this->replaceKeys( "DbTableFieldSearch", "ExportFileDbTableFieldResultSearch", Yii::$app->request->queryParams);
		$queryParams["ExportFileDbTableFieldResultSearch"]["session"]=$sessionPrepKey;
		$dataProvider = $searchModel->search($queryParams);

		$columns = [
			['attribute' => 'id'],
			['attribute' => 'project_name'],
			['attribute' => 'client_name'],
			['attribute' => 'name'],
			['attribute' => 'description'],
			['attribute' => 'datatype'],
			['attribute' => 'is_PrimaryKey'],
			['attribute' => 'is_BusinessKey'],
			['attribute' => 'is_GDPR_relevant'],
			['attribute' => 'databaseInfoFromLocation'],
			['attribute' => 'schemaInfoFromLocation'],
			['attribute' => 'db_table_name'],
			['attribute' => 'deleted_status_name'],
			['attribute' => 'comments'],
			['attribute' => 'mappings'],
		];
		
		if ($export_fk_ids === "1")
		{
			$columns = array_merge($columns, 				[
				['attribute' => 'uuid'],
				['attribute' => 'fk_object_type_id'],
				['attribute' => 'fk_client_id'],
				['attribute' => 'fk_project_id'],
				['attribute' => 'fk_db_table_id'],
				['attribute' => 'fk_deleted_status_id'],
				['attribute' => 'bulk_load_checksum'],
			]);
		}

		$exporter = new CsvGrid([
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
		$exporter->export()->saveAs($exportFilename);
	}

	protected function initDownload($exportFilePath) 
	{
		$file = $exportFilePath;
		if (file_exists($file)) {
			Yii::$app->response->sendFile($file);
		   } 
	}
	
	protected function prepareExportData($sessionPrepKey)
	{
		Yii::debug($sessionPrepKey, '$sessionPrepKey');
		$permProjectsCanSee = Yii::$app->User->identity->permProjectsCanSee;
		foreach($permProjectsCanSee as $key=>$value)
		{
			$model = new \app\models\base\ExportFileDbTableFieldParams();
			$model->session = $sessionPrepKey;
			$model->allowed_fk_project_id = $value;
			$model->save();
			unset($model);
		}

		$permClientsCanSee = Yii::$app->User->identity->permClientsCanSee;
		foreach($permClientsCanSee as $key=>$value)
		{
			$model = new \app\models\base\ExportFileDbTableFieldParams();
			$model->session = $sessionPrepKey;
			$model->allowed_fk_client_id = $value;
			$model->save();
			unset($model);
		}
		
		$model = new \app\models\base\ExportFileDbTableFieldQueue();
		$model->session = $sessionPrepKey;
		$model->save(); // fires DB-TRIGGER
		unset($model);
	}

	protected function cleanupResultTable($sessionPrepKey)
	{
		\app\models\base\ExportFileDbTableFieldResult::deleteAll(['session' => $sessionPrepKey]);
	}

	/**
	 * Checks if a folder exist and return canonicalized absolute pathname (sort version)
	 * @param string $folder the path being checked.
	 * @return mixed returns TRUE on success otherwise FALSE
	 */
	private function folder_exist($folder)
	{
		// Get canonicalized absolute pathname
		$path = realpath($folder);

		// If it exist, check if it's a directory
		return ($path !== false AND is_dir($path)) ? true : false;
	}

	protected function createOutputDir($path)
	{
		if (! $this->folder_exist($path))
		{
			\yii\helpers\FileHelper::createDirectory($path, $mode = 0775, $recursive = true);
		}
		return $this->folder_exist($path);
	}
	
	protected function cleanupResultFile($exportFilePath)
	{
		unlink($exportFilePath);
	}

	public function actionExport_csv($export_fk_ids = "0", $no_cleanup = "0")
	{
		$path = Yii::getAlias('@app') . "/exportfiles";
		$dt = date("Y-m-d_H-m-s");
		$dirExists=$this->createOutputDir($path);
		if (! $dirExists)
		{
			throw new \yii\base\UserException( Yii::t("app","Output directory couldn't be created!") );
		}
		$sessionPrepKey = Yii::$app->controller->id . "|" . $dt . "|" . Yii::$app->session->id . "|" . Yii::$app->user->id;
		$this->prepareExportData($sessionPrepKey);
		$exportFilePath = $path . '/'. Yii::$app->controller->id . '_export_' . date("Y-m-d_H-m-s") . '.csv';
		$this->CreateCSV($sessionPrepKey, $exportFilePath, $export_fk_ids);
		$this->initDownload($exportFilePath);
		if ($no_cleanup !== "1")
		{
			$this->cleanupResultTable($sessionPrepKey);
			$this->cleanupResultFile($exportFilePath);
		}
		return;
	}
}
