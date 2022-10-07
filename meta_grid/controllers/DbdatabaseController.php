<?php

namespace app\controllers;

use Yii;
use app\models\DbDatabase;
use app\models\DbDatabaseSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\ObjectType;
use app\models\Project;
use app\models\Tool;
use app\models\DeletedStatus;
use app\models\ObjectPersistenceMethod;
use app\models\DatamanagementProcess;
use Da\User\Filter\AccessRuleFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;


/**
 * DbdatabaseController implements the CRUD actions for DbDatabase model.
 */
class DbdatabaseController extends Controller
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
     * Lists all DbDatabase models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DbDatabaseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DbDatabase model.
     * @param int $id ID
     * @return mixed
     */
    public function actionView($id)
    {
		        return $this->render('view', [
            'model' => $this->findModel($id),
            'bulkloaderExecutionString' => $this->buildBulkloaderExecutionString($id)
			        ]);
		}

    /**
     * Creates a new DbDatabase model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($isfrommodal = false, $modalparent = "", $refreshfield = "")
    {
				
		$model = new DbDatabase();

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
'toolList' => $this->getToolList(),		// autogeneriert ueber gii/CRUD
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
     * Updates an existing DbDatabase model.
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
'toolList' => $this->getToolList(),		// autogeneriert ueber gii/CRUD
'deleted_statusList' => $this->getDeletedStatusList(),		// autogeneriert ueber gii/CRUD
'object_persistence_methodList' => $this->getObjectPersistenceMethodList(),		// autogeneriert ueber gii/CRUD
'datamanagement_processList' => $this->getDatamanagementProcessList(),		// autogeneriert ueber gii/CRUD
				'modalparent'                   => '',
				'refreshfield'                  => '',
            ]);
        }
		    }

    /**
     * Deletes an existing DbDatabase model.
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
     * Finds the DbDatabase model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return DbDatabase the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DbDatabase::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	private function bulkloadertemplate()
	{
		// Template text generated with 'kitchen.sh -file:"run_import.kjb" -listparam | grep "Parameter:"'
		$template_string_linux='
Parameter: changeset_path=, default=- : Path to scan for liquibase changesets
Parameter: filter_routine_names= : Filter routine names based on parameter value (like operation without wildcards). Attention: Case sensitive
Parameter: get_description_from_routine_definition=, default=Y : Extract the comment from the SQL Definition. Y=Yes | N=No
Parameter: get_description_from_routine_definition_SplitElemenent_1=, default=1 : Element to use for result of get_description_from_routine_definition_SplitOnString_1
Parameter: get_description_from_routine_definition_SplitElemenent_2=, default=0 : Element to use for result of get_description_from_routine_definition_SplitOnString_2
Parameter: get_description_from_routine_definition_SplitOnString_1=, default=Description:  : First substring to split the routine definition (e.g.  "Description: ") without quotations
Parameter: get_description_from_routine_definition_SplitOnString_2=, default=\n : Second substring to split the routine definition (e.g.  "\n") without quotations
Parameter: handle_descriptions=, default=3 : 1=Use only database object description | 2=Use only from routine description | 3=Use both
Parameter: location_lookup_database=, default=db_name : Database for liquibase changeset lookup db_table
Parameter: map_db_table_to_db_database_id=, default=11 : DB_Database ID which will be used to map (new) database tables found
Parameter: map_transfer_process_to_db_database_id=, default=11 : DB_Database ID which will be used to map (new) database routines found
Parameter: metagrid_jdbc_db_pwd= : JDBC Database Password for meta#grid
Parameter: metagrid_jdbc_db_user= : JDBC Database User for meta#grid
Parameter: metagrid_jdbc_driver_class=, default=org.sqlite.JDBC : JDBC Java Driver Class to use to write in meta#grid database
Parameter: metagrid_jdbc_url=, default=jdbc:sqlite:/home/patrick/Development_WorkingCopies/dwh_meta/dwh_meta_v2/dwh_meta.sqlite : JDBC URL of the source system to write in meta#grid database (including IP/DNS, Port, Databasename)
Parameter: project_id=, default=7 : Project ID which information belongs to
Parameter: source_db_schema_exclude=, default=sys,INFORMATION_SCHEMA,Backup,zz_Attic,information_schema,pg_catalog,pg_toast : Ignore special schemes in databse e.g. sys
Parameter: source_jdbc_db_pwd=, default=<PASSWORD> : JDBC Database Password
Parameter: source_jdbc_db_user=, default=<USER> : JDBC Database User for source
Parameter: source_jdbc_driver_class=, default=net.sourceforge.jtds.jdbc.Driver : JDBC Java Driver Class to use to read from source
Parameter: source_jdbc_url=, default=jdbc:jtds:sqlserver://127.0.0.1:1433/Chinook : JDBC URL of the source system to read from (including IP/DNS, Port, Databasename)
Parameter: filter_view_names= : Filter view names based on parameter value (like operation without wildcards). Attention: Case sensitive and only affects reading view descriptions
Parameter: overwrite_description_if_existing_differs=, default=Y : If there description is already filled and differs, shall it be overwritten. Y=Overwrite | N=Do not overwrite. Attention: only affects reading view descriptions
		';

		$arr = array();

		$line = explode("\n",str_replace("\t","", $template_string_linux))[3];
		$parameter = explode("=", explode(": ", $line)[1])[0];
		$default_value = "";
		if (strpos($line, "default") > 0)
		{	
			$default_value = explode("default=", explode(": ", $line)[1])[1];
		}

		foreach(explode("\n",str_replace("\t","", $template_string_linux)) as $key=>$value)
		{
			if (strpos($value, "=")>0 && strpos($value, ": ")>0)
			{
				$parameter = explode("=", explode(": ", $value)[1])[0];
				$default_value = "";
				if (strpos($value, "default") > 0)
				{	
					$default_value = rtrim(explode("default=", explode(": ", $value)[1])[1]);
				}
				$arr[$parameter]=$default_value;
			}
		}
		return $arr;
	}

	private function buildBulkloaderExecutionString($id)
	{

		$Utils = new \vendor\meta_grid\helper\Utils();
		$bulk_loader_executable = $Utils->get_app_config("bulk_loader_executable");
		$bulk_loader_metagrid_jdbc_url = $Utils->get_app_config("bulk_loader_metagrid_jdbc_url");
		$bulk_loader_java_home = $Utils->get_app_config("bulk_loader_java_home");

		$model = $this->findModel($id);
		$bulkLoaderParameterArr = $this->bulkloadertemplate();
		$bulkLoaderParameterArr["project_id"] = $model->fk_project_id;
		$bulkLoaderParameterArr["map_db_table_to_db_database_id"] = $model->id;
		$bulkLoaderParameterArr["map_transfer_process_to_db_database_id"] = $model->id;
		
		if (Yii::$app->db->getDriverName() == "sqlite")
		{
			$sqlitePath = str_replace("sqlite:", "", Yii::$app->db->dsn);
			$sqliteRealPath = realpath($sqlitePath);
			$bulkLoaderParameterArr["metagrid_jdbc_url"] = "jdbc:sqlite:" . $sqliteRealPath;
		}
		
		if ($bulk_loader_metagrid_jdbc_url !== NULL && $bulk_loader_metagrid_jdbc_url !== "")
		{
			$bulkLoaderParameterArr["metagrid_jdbc_url"] = $bulk_loader_metagrid_jdbc_url;
		}
		
		$bulkLoaderParameterArr["location_lookup_database"] = $model->name;
		$bulkLoaderParameterArr["source_jdbc_driver_class"] = "[--> JDBC DRIVER CLASS <--]";
		$bulkLoaderParameterArr["source_jdbc_url"] = "[--> JDBC URL <--]";

		if (stripos($model->fkTool->vendor, "Microsoft") !== false)
		{
			if (stripos($model->fkTool->tool_name, "SQL") !== false)
			{
				$bulkLoaderParameterArr["source_jdbc_driver_class"] = "net.sourceforge.jtds.jdbc.Driver";
				$bulkLoaderParameterArr["source_jdbc_url"] = "jdbc:jtds:sqlserver://127.0.0.1:1433/$model->name";
			}
		}
		if (stripos($model->fkTool->vendor, "Postgres") !== false)
		{
			if (stripos($model->fkTool->tool_name, "SQL") !== false)
			{
				$bulkLoaderParameterArr["source_jdbc_driver_class"] = "org.postgresql.Driver";
				$bulkLoaderParameterArr["source_jdbc_url"] = "jdbc:postgresql://127.0.0.1:5432/$model->name";
			}
		}		
		if (stripos($model->fkTool->vendor, "ORACLE") !== false)
		{
			if (stripos($model->fkTool->tool_name, "MySQL") !== false)
			{
				$bulkLoaderParameterArr["source_jdbc_driver_class"] = "com.mysql.jdbc.Driver";
				$bulkLoaderParameterArr["source_jdbc_url"] = "jdbc:mysql://127.0.0.1:3306/$model->name";
			}
		}		
		if (stripos($model->fkTool->vendor, "SQLite") !== false)
		{
			if (stripos($model->fkTool->tool_name, "SQLite") !== false)
			{
				$bulkLoaderParameterArr["source_jdbc_driver_class"] = "org.sqlite.JDBC";
				$bulkLoaderParameterArr["source_jdbc_url"] = "jdbc:sqlite:/myPath/$model->name";
			}
		}
		if (stripos($model->fkTool->vendor, "Intersystems") !== false)
		{
			if (stripos($model->fkTool->tool_name, "Intersystems Cach") !== false)
			{
				$bulkLoaderParameterArr["source_jdbc_driver_class"] = "com.intersys.jdbc.CacheDriver";
				$bulkLoaderParameterArr["source_jdbc_url"] = "jdbc:Cache://127.0.0.1:1972/$model->name";
			}
		}

		$exec = ($bulk_loader_executable !== NULL && $bulk_loader_executable !== "") ? $bulk_loader_executable : "kitchen.sh";
		$returnValue = "";
		$returnValue .= "## Linux". "\n";
		$returnValue .= "\n";
		$returnValue .= ($bulk_loader_java_home !== null && $bulk_loader_java_home !== "") ? "export JAVA_HOME=\"$bulk_loader_java_home\"" : "# export JAVA_HOME=\"&lt;You can set this parameter in the table app_config with the key='bulk_loader_java_home'&gt;\"";
		$returnValue .= "\n";
		$returnValue .= "\n";
		$returnValue .= $exec.' -file:"run_import.kjb" \\' . "\n";
		foreach($bulkLoaderParameterArr as $key=>$value)
		{
			$returnValue .= "-param:" . $key . "=" . '"' . $value . '"' . " \\" . "\n";
		}
		
		$returnValue .= "-----------------------------------------------------------------------------------". "\n";

		$exec = ($bulk_loader_executable !== NULL && $bulk_loader_executable !== "") ? $bulk_loader_executable : "kitchen.bat";
		$returnValue .= "\n";
		$returnValue .= "REM Windows". "\n";
		$returnValue .= "@echo off". "\n";
		$returnValue .= "\n";
		$returnValue .= ($bulk_loader_java_home !== NULL && $bulk_loader_java_home !== "") ? "SET JAVA_HOME=$bulk_loader_java_home" : "REM SET JAVA_HOME=&lt;You can set this parameter in the table app_config with the key='bulk_loader_java_home'&gt;";
		$returnValue .= "\n";
		$returnValue .= "\n";
		foreach($bulkLoaderParameterArr as $key=>$value)
		{
			$returnValue .= 'SET ' . $key . "=" . $value . "\n";
		}
		$returnValue .= "\n";
		$returnValue .= "pushd %cd%" . "\n";
		$returnValue .= "SET startcd=%cd%" . "\n";
		$returnValue .= "SET kettle_bin_path=" . dirname($exec) . "\n";
		$returnValue .= 'cd "%kettle_bin_path%' . "\n";
		$returnValue .= 'SET kitchen_bin=' . basename($exec) . "\n";
		$returnValue .= 'SET job_path="run_import.kjb"' . "\n";
		$returnValue .= 'start %kitchen_bin% /file:%job_path% ';
		foreach($bulkLoaderParameterArr as $key=>$value)
		{
			$returnValue .= '"/param:' . $key . "=%" . $key . '%" ' ;
		}
		$returnValue .= "\n";
		$returnValue .= 'cd %startcd%' . "\n";
		$returnValue .= 'popd' . "\n";

		return $returnValue;
	}
}