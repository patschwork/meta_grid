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
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		        return $this->render('view', [
            'model' => $this->findModel($id),
		'SQLSelectStatement' => $this->buildSQLSelectStatement($id),
			        ]);
		}

    /**
     * Creates a new DbTable model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$db_table_show_buttons_for_different_object_type_updates=\vendor\meta_grid\helper\Utils::get_app_config("db_table_show_buttons_for_different_object_type_updates");

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
        	return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'object_typeList' => $this->getObjectTypeList(),		// autogeneriert ueber gii/CRUD
'projectList' => $this->getProjectList(),		// autogeneriert ueber gii/CRUD
'db_table_contextList' => $this->getDbTableContextList(),		// autogeneriert ueber gii/CRUD
'db_table_typeList' => $this->getDbTableTypeList(),		// autogeneriert ueber gii/CRUD
'deleted_statusList' => $this->getDeletedStatusList(),		// autogeneriert ueber gii/CRUD
            ]);
        }
    }

    /**
     * Updates an existing DbTable model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$db_table_show_buttons_for_different_object_type_updates = \vendor\meta_grid\helper\Utils::get_app_config("db_table_show_buttons_for_different_object_type_updates");
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
            ]);
        }
		    }

    /**
     * Deletes an existing DbTable model.
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
     * Finds the DbTable model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
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

    protected function CreateCSV($export_fk_ids = 0, $sessionPrepKey, $exportFilename)
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
	
	/**
	 * Deletes old files after a time period. 
	 * Hits also exported files from other controllers
	 * @ToDo: Could be moved to a central function/class
	 */
	protected function cleanupOldResultFiles($exportPath, $sessionPrepKey)
	{
		$debug = 0;
		$files = array_diff(scandir($exportPath), array('.', '..'));
		foreach($files as $key=>$file)
		{
			try
			{
				// example: dbtable_export_2020-10-03_08-10-25.csv
				$dt_from_filename = explode(".", explode("_export_", $file)[1])[0]; // 2020-10-03_08-10-25
				$date_from_filename = explode("_", $dt_from_filename)[0]; // 2020-10-03
				$time_from_filename = str_replace("-", ":", explode("_", $dt_from_filename)[1]); // 08:10:25
				$date1 = new \DateTime($date_from_filename . " " . $time_from_filename);
				$date2 = new \DateTime("now");
				$diffInSeconds = $date2->getTimestamp() - $date1->getTimestamp();
				if ($debug > 0)
				{
					Yii::trace($sessionPrepKey, "Checking for clean up old file = $file / diffInSeconds=". $diffInSeconds);
				}
				if ($diffInSeconds >= 60*10) // older than minutes (10 minutes)
				{
					$pathfile = join(DIRECTORY_SEPARATOR, array($exportPath, $file));
					@unlink($pathfile);
					if (file_exists($pathfile))
					{
						Yii::trace($sessionPrepKey, "Could not clean up old file = $file");
					}
				}
			}
			catch (\Throwable $th) {
				Yii::trace($sessionPrepKey, 'Error on removing older files');
			}
		}
		
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
		$exportFilePath = $path . DIRECTORY_SEPARATOR . Yii::$app->controller->id . '_export_' . date("Y-m-d_H-i-s") . '.csv';
		$this->CreateCSV($export_fk_ids, $sessionPrepKey, $exportFilePath);
		$this->initDownload($exportFilePath);
		if ($no_cleanup !== "1")
		{
			$this->cleanupResultTable($sessionPrepKey);
			$this->cleanupOldResultFiles($path, $sessionPrepKey);
		}
		return;
	}
}