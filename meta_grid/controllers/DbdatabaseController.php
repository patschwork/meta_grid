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
     * @param integer $id
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
    public function actionCreate()
    {
				
		$model = new DbDatabase();

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
'deleted_statusList' => $this->getDeletedStatusList(),		// autogeneriert ueber gii/CRUD
            ]);
        }
    }

    /**
     * Updates an existing DbDatabase model.
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
'deleted_statusList' => $this->getDeletedStatusList(),		// autogeneriert ueber gii/CRUD
            ]);
        }
		    }

    /**
     * Deletes an existing DbDatabase model.
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
     * Finds the DbDatabase model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
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

		$bulk_loader_executable = \vendor\meta_grid\helper\Utils::get_app_config("bulk_loader_executable");
		$bulk_loader_metagrid_jdbc_url = \vendor\meta_grid\helper\Utils::get_app_config("bulk_loader_metagrid_jdbc_url");

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

		$exec = ($bulk_loader_executable !== NULL && $bulk_loader_executable !== "") ? $bulk_loader_executable : "kitchen.sh";
		$returnValue = "";
		$returnValue .= "## Linux". "\n";
		$returnValue .= $exec.' -file:"run_import.kjb" \\' . "\n";
		foreach($bulkLoaderParameterArr as $key=>$value)
		{
			$returnValue .= "-param:" . $key . "=" . '"' . $value . '"' . " \\" . "\n";
		}
		
		$exec = ($bulk_loader_executable !== NULL && $bulk_loader_executable !== "") ? $bulk_loader_executable : "kitchen.bat";
		$returnValue .= "\n";
		$returnValue .= ":: Windows". "\n";
		$returnValue .= $exec.' /file:"run_import.kjb"' . " ";
		foreach($bulkLoaderParameterArr as $key=>$value)
		{
			$returnValue .= '"/param:' . $key . "=" . $value . '"' . " ^" . "\n";
		}

		return $returnValue;
	}
}