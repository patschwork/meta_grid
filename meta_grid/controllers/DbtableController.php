<?php

namespace app\controllers;

use Yii;
use app\models\DbTable;
use app\models\DbTableSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\ObjectType;
use app\models\Project;
use app\models\DbTableContext;
use app\models\DbTableType;
use app\models\DeletedStatus;
use app\models\ObjectPersistenceMethod;
use app\models\DatamanagementProcess;
use Da\User\Filter\AccessRuleFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii2tech\csvgrid\CsvGrid;

/**
 * DbtableController implements the CRUD actions for DbTable model.
 */
class DbtableController extends Controller
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

	private function getDbTableContextList()
	{
		// autogeneriert ueber gii/CRUD
		$db_table_contextModel = new DbTableContext();
		$db_table_contexts = $db_table_contextModel::find()->all();
		$db_table_contextList = array();
		$db_table_contextList[null] = null;
		foreach($db_table_contexts as $db_table_context)
		{
			$db_table_contextList[$db_table_context->id] = $db_table_context->name;
		}
		return $db_table_contextList;
	}

	private function getDbTableTypeList()
	{
		// autogeneriert ueber gii/CRUD
		$db_table_typeModel = new DbTableType();
		$db_table_types = $db_table_typeModel::find()->all();
		$db_table_typeList = array();
		$db_table_typeList[null] = null;
		foreach($db_table_types as $db_table_type)
		{
			$db_table_typeList[$db_table_type->id] = $db_table_type->name;
		}
		return $db_table_typeList;
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
     * Lists all DbTable models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DbTableSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DbTable model.
     * @param int $id ID
     * @return mixed
     */
    public function actionView($id)
    {
		        return $this->render('view', [
            'model' => $this->findModel($id),
		'SQLSelectStatement' => $this->buildSQLSelectStatement($id),
		'sameTableList' => $this->sameTableList($id),
			        ]);
		}

    /**
     * Creates a new DbTable model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($isfrommodal = false, $modalparent = "", $refreshfield = "")
    {
		$Utils = new \vendor\meta_grid\helper\Utils();
	$db_table_show_buttons_for_different_object_type_updates=$Utils->get_app_config("db_table_show_buttons_for_different_object_type_updates");

		if ($db_table_show_buttons_for_different_object_type_updates != 1) 
		{
			return $this->redirect(['dbtablefieldmultipleedit/create']);
		}
				
		$model = new DbTable();

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
'db_table_contextList' => $this->getDbTableContextList(),		// autogeneriert ueber gii/CRUD
'db_table_typeList' => $this->getDbTableTypeList(),		// autogeneriert ueber gii/CRUD
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
     * Updates an existing DbTable model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$Utils = new \vendor\meta_grid\helper\Utils();
	$db_table_show_buttons_for_different_object_type_updates = $Utils->get_app_config("db_table_show_buttons_for_different_object_type_updates");
		if ($db_table_show_buttons_for_different_object_type_updates != 1) 
		{
			return $this->redirect(['dbtablefieldmultipleedit/update', 'id' => $id]);
		}	
				
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
'db_table_contextList' => $this->getDbTableContextList(),		// autogeneriert ueber gii/CRUD
'db_table_typeList' => $this->getDbTableTypeList(),		// autogeneriert ueber gii/CRUD
'deleted_statusList' => $this->getDeletedStatusList(),		// autogeneriert ueber gii/CRUD
'object_persistence_methodList' => $this->getObjectPersistenceMethodList(),		// autogeneriert ueber gii/CRUD
'datamanagement_processList' => $this->getDatamanagementProcessList(),		// autogeneriert ueber gii/CRUD
				'modalparent'                   => '',
				'refreshfield'                  => '',
            ]);
        }
		    }

    /**
     * Deletes an existing DbTable model.
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
     * Finds the DbTable model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return DbTable the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DbTable::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	private function buildSQLSelectStatement($id)
	{
		$modelDbTableField = \app\models\DbTableField::find()->select(["name", "datatype", "is_PrimaryKey"])->where(["fk_db_table_id" => $id])->all();
		$model = $this->findModel($id);
		$returnValue = "";
		
		$returnValue .= "SELECT" . "\n";
		$i=0;
		foreach($modelDbTableField as $key=>$value)
		{
			$i++;
			$returnValue .= $i > 1 ? "   ," : "    ";
			$returnValue .= "\"". $value->name . "\"" . "\n";
		}
		$returnValue .= "FROM " . $model->location;
		$returnValue .= ";" . "\n";
		$returnValue .= "\n";
		
		$returnValue .= "CREATE TABLE" ." " . $model->location . "\n";
		$returnValue .= "(" . "\n";
		
		$j=0;
		foreach($modelDbTableField as $key=>$value)
		{
			$j++;
			$returnValue .= $j > 1 ? "   ," : "    ";
			$returnValue .= "\"". $value->name . "\"" ;
			$returnValue .= " " . $value->datatype;
			$returnValue .= $value->is_PrimaryKey === true ? " NOT NULL PRIMARY KEY" : "";
			$returnValue .= "\n";
		}
		$returnValue .= ")";
		$returnValue .= ";" . "\n";
		
		return $i === 0 ? "" : $returnValue;
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
		$searchModel = new \app\models\ExportFileDbTableResultSearch();
		$queryParams = $this->replaceKeys( "DbTableSearch", "ExportFileDbTableResultSearch", Yii::$app->request->queryParams);
		$queryParams["ExportFileDbTableResultSearch"]["session"]=$sessionPrepKey;
		$dataProvider = $searchModel->search($queryParams);

		$columns = [
			['attribute' => 'id'],
			['attribute' => 'client_name'],
			['attribute' => 'project_name'],
			['attribute' => 'name'],
			['attribute' => 'description'],
			['attribute' => 'location'],
			['attribute' => 'db_table_context_name'],
			['attribute' => 'db_table_type_name'],
			['attribute' => 'deleted_status_name'],
			['attribute' => 'databaseInfoFromLocation'],
			['attribute' => 'mappings'],
			['attribute' => 'comments'],
		];
		
		if ($export_fk_ids === "1")
		{
			$columns = array_merge($columns, 				[
				['attribute' => 'uuid'],
				['attribute' => 'fk_object_type_id'],
				['attribute' => 'fk_client_id'],
				['attribute' => 'fk_project_id'],
				['attribute' => 'fk_db_table_type_id'],
				['attribute' => 'fk_db_table_context_id'],
				['attribute' => 'fk_deleted_status_id'],
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
		Yii::trace($sessionPrepKey, '$sessionPrepKey');
		$permProjectsCanSee = Yii::$app->User->identity->permProjectsCanSee;
		foreach($permProjectsCanSee as $key=>$value)
		{
			$model = new \app\models\base\ExportFileDbTableParams();
			$model->session = $sessionPrepKey;
			$model->allowed_fk_project_id = $value;
			$model->save();
			unset($model);
		}

		$permClientsCanSee = Yii::$app->User->identity->permClientsCanSee;
		foreach($permClientsCanSee as $key=>$value)
		{
			$model = new \app\models\base\ExportFileDbTableParams();
			$model->session = $sessionPrepKey;
			$model->allowed_fk_client_id = $value;
			$model->save();
			unset($model);
		}
		
		$model = new \app\models\base\ExportFileDbTableQueue();
		$model->session = $sessionPrepKey;
		$model->save(); // fires DB-TRIGGER
		unset($model);
	}

	protected function cleanupResultTable($sessionPrepKey)
	{
		\app\models\base\ExportFileDbTableResult::deleteAll(['session' => $sessionPrepKey]);
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

	public function sameTableList($id)
	{
		$permProjectsCanSee = Yii::$app->User->identity->permProjectsCanSee;
		$model = \app\models\base\VDbTableLocation4SameTableLookup::findOne(['id' => $id]);
	    if (($model !== null)) 
		{
			$allRelatedByName = \app\models\base\VDbTableLocation4SameTableLookup::find()
				->where(['db_table_location_normalized' => $model->db_table_location_normalized])
				->andFilterWhere(['<>','id', $id]) // exclude the actual id
				->andFilterWhere(['in','fk_project_id', $permProjectsCanSee]) // only for the permission given to the user (@TODO, model class contains checks, too. But not working... )
				->all();
			if (($allRelatedByName !== null))
			{
				return $allRelatedByName;
			}
		}
		return null;
	}

}