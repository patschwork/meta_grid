<?php

namespace app\controllers;

use Yii;
use app\models\base\ImportStageDbTable;
use app\models\ImportStageDbTableSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\Project;
use app\models\DbDatabase;
use app\models\DbTable;
use app\models\DbTableField;
use app\models\MapObject2Object;
use app\models\ToolType;
use app\models\Tool;
use Da\User\Filter\AccessRuleFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;


/**
 * ImportstagedbtableController implements the CRUD actions for ImportStageDbTable model.
 */
class ImportstagedbtableController extends Controller
{
	public function behaviors()
    {
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
                        'actions' => ['index', 'processselected'],
                        'roles' => ['author', 'global-create', 'create' ."-" . 'dbtable'],
                        'matchCallback' => function ($rule, $action) {
                            if (Yii::$app->user->identity->isAdmin || (Yii::$app->User->can('create-dbtable') && Yii::$app->User->can('create-dbtablefield'))) {
                                return true;
                            }
                            return false;
                        }
                    ],
                ],
            ],			
        ];
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

	private function getDbDatabaseList()
	{
		// autogeneriert ueber gii/CRUD
		$db_databaseModel = new DbDatabase();
		$db_databases = $db_databaseModel::find()->all();
		$db_databaseList = array();
		foreach($db_databases as $db_database)
		{
			$db_databaseList[$db_database->id] = $db_database->name;
		}
		return $db_databaseList;
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
     * Lists all ImportStageDbTable models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ImportStageDbTableSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ImportStageDbTable model.
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
     * Creates a new ImportStageDbTable model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
				
		$model = new ImportStageDbTable();

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
                'projectList' => $this->getProjectList(),		// autogeneriert ueber gii/CRUD
'db_databaseList' => $this->getDbDatabaseList(),		// autogeneriert ueber gii/CRUD
            ]);
        }
    }

    /**
     * Updates an existing ImportStageDbTable model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
				
		$model = $this->findModel($id);

	// 	 if (!in_array($model->fkProject->id, Yii::$app->User->identity->permProjectsCanEdit)) {throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You have no permission to edit this data.'));
	// return;	}    
	// 	 if (!in_array($model->fk_project_id, Yii::$app->User->identity->permProjectsCanEdit)) {throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You have no permission to edit this data.'));
	// return;	}    
		
		
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
				            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'projectList' => $this->getProjectList(),		// autogeneriert ueber gii/CRUD
'db_databaseList' => $this->getDbDatabaseList(),		// autogeneriert ueber gii/CRUD
            ]);
        }
		    }

    /**
     * Deletes an existing ImportStageDbTable model.
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
     * Finds the ImportStageDbTable model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ImportStageDbTable the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ImportStageDbTable::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
	}
	
	// https://stackoverflow.com/questions/37473895/how-i-can-process-a-checkbox-column-from-yii2-gridview
	public function actionProcessselected()
	{
		$Utils = new \vendor\meta_grid\helper\Utils();
		$app_config_importstage_dbtable_processing_time_limit = $Utils->get_app_config("importstage_dbtable_processing_time_limit");
		$app_config_importstage_dbtable_processing_memory_limit = $Utils->get_app_config("importstage_dbtable_processing_memory_limit");
		set_time_limit($app_config_importstage_dbtable_processing_time_limit);
		ini_set('memory_limit', $app_config_importstage_dbtable_processing_memory_limit."M");
		$checkWithExists = true; // location same?!
		$action = Yii::$app->request->post('action'); // dropDown (array)
		$select = Yii::$app->request->post('selection'); //checkbox (array)
	
		if ($select === NULL && $action <= 1)
		{
			return $this->redirect(['index']);
		}

		if ($action == 1)
		{
			$model=ImportStageDbTable::deleteAll(['in', "id", $select]);
		}		
		if ($action == 3)
		{
			$model=ImportStageDbTable::deleteAll();
		}
		if ($action == 0 || $action == 2)
		{
			$DbTableObjectTypeId = NULL;
			$createdOrExistingTableId = array();
			if ($action == 0)
			{
				$loadModelResult = ImportStageDbTable::find()->where(['in', "id", $select])->andWhere(["<=","_import_state",0])->all();
			}
			if ($action == 2)
			{
				$loadModelResult = ImportStageDbTable::find()->where(["<=","_import_state",0])->all();
			}
			
			// process db_table
			foreach($loadModelResult as $loadModel)
			{
				if (($loadModel->location !== NULL) && ($loadModel->location !== "") && ($checkWithExists))
				{
					// $DbTableModel = DbTable::findOne(["location" => $loadModel->location, "fk_project_id" => $loadModel->fk_project_id]);
					$DbTableModel = DbTable::find()->where(["location" => $loadModel->location, "fk_project_id" => $loadModel->fk_project_id])->one(); // Workaround T120...
					if ($DbTableModel !== NULL)
					{
						$createdOrExistingTableId[$loadModel->id] = $DbTableModel->id;
					}
					else
					{
						$DbTableCreateModel = new DbTable();
						$DbTableCreateModel->fk_project_id = $loadModel->fk_project_id;
						$DbTableCreateModel->name = $loadModel->db_table_name;
						$DbTableCreateModel->description = $loadModel->db_table_description;
						$DbTableCreateModel->location = $loadModel->location;
						// $DbTableCreateModel->fk_db_table_context_id = $loadModel-> ...
						// $DbTableCreateModel->fk_db_table_type_id = $loadModel-> ... // don't know yet
						$DbTableCreateModel->save();
						$createdOrExistingTableId[$loadModel->id] = $DbTableCreateModel->id;
						if ($DbTableObjectTypeId === NULL)
						{
							$DbTableObjectTypeId = DbTable::find()->where(["id" => $DbTableCreateModel->id, "fk_project_id" => $loadModel->fk_project_id])->one()->fk_object_type_id;
						}
						unset($DbTableCreateModel);
					}
				}
			}

			if ($action == 2)
			{
				$loadModelResult = ImportStageDbTable::find()->where(["<=","_import_state",0])->all();
			}
			else
			{
				$loadModelResult = ImportStageDbTable::find()->where(['in', "id", $select])->all();
			}
			// process db_table_field
			foreach($loadModelResult as $loadModel)
			{
				if (array_key_exists($loadModel->id, $createdOrExistingTableId))
				{
					// $DbTableFieldModel = DbTableField::findOne([
					// 	"name" => $loadModel->db_table_field_name
					//    ,"fk_project_id" => $loadModel->fk_project_id
					//    ,"fk_db_table_id" => $createdOrExistingTableId[$loadModel->id]
					//    ]);
					$DbTableFieldModel = DbTableField::find()->where([
						"name" => $loadModel->db_table_field_name
					   ,"fk_project_id" => $loadModel->fk_project_id
					   ,"fk_db_table_id" => $createdOrExistingTableId[$loadModel->id]
					   ])->one(); // Workaround T120...
					if ($DbTableFieldModel == NULL)
					{
						$DbTableFieldCreateModel = new DbTableField();
						$DbTableFieldCreateModel->fk_project_id = $loadModel->fk_project_id;
						$DbTableFieldCreateModel->name = $loadModel->db_table_field_name;
						$DbTableFieldCreateModel->description = $loadModel->db_table_field_description;
						$DbTableFieldCreateModel->fk_db_table_id = $createdOrExistingTableId[$loadModel->id];
						$DbTableFieldCreateModel->datatype = $loadModel->db_table_field_datatype;
						// $DbTableFieldCreateModel->bulk_load_checksum
						// $DbTableFieldCreateModel->fk_deleted_status_id
						$DbTableFieldCreateModel->is_PrimaryKey = $loadModel->isPrimaryKeyField;
						$DbTableFieldCreateModel->is_BusinessKey = $loadModel->is_BusinessKey;
						$DbTableFieldCreateModel->is_GDPR_relevant = $loadModel->is_GDPR_relevant;
						$DbTableFieldCreateModel->save();
						unset($DbTableFieldCreateModel);
						$loadModel->_import_state = 1;
						$loadModel->save();
					}
				}
				else
				{
					// DbTable for DbTableField not found error!
					$loadModel->_import_state = -2;
					$loadModel->save();
				}
			}

			if ($action == 2)
			{
				$loadModelResult = ImportStageDbTable::find()->where(["<=","_import_state",0])->all();
			}
			else
			{
				$loadModelResult = ImportStageDbTable::find()->where(['in', "id", $select])->all();
			}
			// process mappings
			foreach($loadModelResult as $loadModel)
			{
				if (array_key_exists($loadModel->id, $createdOrExistingTableId))
				{
					if (
							($loadModel->database_or_catalog !== NULL && $loadModel->database_or_catalog !== "")
							&&
							($loadModel->fk_db_database_id === NULL)
					   )
					{
						$DbDatabaseExists = DbDatabase::find()->where(["fk_project_id" => $loadModel->fk_project_id, "name" => $loadModel->database_or_catalog])->one();
						
						if ($DbDatabaseExists === NULL)
						{
							$DbDatabaseCreateModel = new DbDatabase();
							$DbDatabaseCreateModel->name = $loadModel->database_or_catalog;
							$DbDatabaseCreateModel->fk_project_id = $loadModel->fk_project_id;
							$DbDatabaseCreateModel->description = "(Added via Copy&Paste Import)";
							$AnyTooltypeIdAsDatabase = ToolType::find()->where(["like", "UPPER(name)", "DATABASE"])->orWhere(["like", "UPPER(name)", strtoupper(Yii::t("app","Database"))])->one();
							if ($AnyTooltypeIdAsDatabase !== NULL)
							{
								$AnyToolDatabaseId = Tool::find()->where(["fk_tooltype_id" => $AnyTooltypeIdAsDatabase->id])->one();
							}
							else
							{
								$AnyToolDatabaseId = Tool::find()->one();						
							}
							$DbDatabaseCreateModel->fk_tool_id = $AnyToolDatabaseId->id;
							$DbDatabaseCreateModel->save();
							$DbDatabaseId = $DbDatabaseCreateModel->id;
							// $DbDatabaseObjectTypeId = $DbDatabaseCreateModel->fk_object_type_id;
							unset($DbDatabaseCreateModel);
							$DbDatabaseObjectTypeId = DbDatabase::find()->where(["fk_project_id" => $loadModel->fk_project_id, "id" => $DbDatabaseId])->one()->fk_object_type_id;
						}
						else
						{
							$DbDatabaseId = $DbDatabaseExists->id;
							$DbDatabaseObjectTypeId = $DbDatabaseExists->fk_object_type_id;
						}

						if (MapObject2Object::find()->where([
							"ref_fk_object_id_1" => $createdOrExistingTableId[$loadModel->id]
							,"ref_fk_object_type_id_1" => $DbTableObjectTypeId
							,"ref_fk_object_id_2" => $DbDatabaseId
							,"ref_fk_object_type_id_2" => $DbDatabaseObjectTypeId
							])->one() === NULL)
						{
							if ($createdOrExistingTableId[$loadModel->id] !== NULL && ($DbTableObjectTypeId !== NULL))
							{
								$MapObject2ObjectCreateModel = new MapObject2Object();
								$MapObject2ObjectCreateModel->ref_fk_object_id_1 = $DbDatabaseId;
								$MapObject2ObjectCreateModel->ref_fk_object_type_id_1 = $DbDatabaseObjectTypeId;
								$MapObject2ObjectCreateModel->ref_fk_object_id_2 = $createdOrExistingTableId[$loadModel->id];
								$MapObject2ObjectCreateModel->ref_fk_object_type_id_2 = $DbTableObjectTypeId;
								$MapObject2ObjectCreateModel->save();
								// Yii::trace('$MapObject2ObjectCreateModel->id = ' . $MapObject2ObjectCreateModel->id, "actionProcessselected");
								unset($MapObject2ObjectCreateModel);
								$loadModel->_import_state = 2;
								$loadModel->save();
							}
						}
					}
				}
			}
		}

		return $this->redirect(['index']);
	 }
}