<?php

namespace app\controllers;

use Yii;
use app\models\MapObject2Object;
use app\models\MapObject2ObjectSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\MappingQualifier;
use app\models\ObjectPersistenceMethod;
use app\models\DatamanagementProcess;
use yii\models\ObjectType;
use yii\helpers\Html;
use yii\helpers\Json;
use app\models\base\Project;
use Da\User\Filter\AccessRuleFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;


/**
 * MapperController implements the CRUD actions for MapObject2Object model.
 */
class MapperController extends Controller
{
	
	private function getMappingQualifierList()
	{
		// autogeneriert ueber gii/CRUD
		$mapping_qualifierModel = new MappingQualifier();
		$mapping_qualifiers = $mapping_qualifierModel::find()->all();
		$mapping_qualifierList = array();
		$mapping_qualifierList[null] = null;
		foreach($mapping_qualifiers as $mapping_qualifier)
		{
			$mapping_qualifierList[$mapping_qualifier->id] = $mapping_qualifier->name;
		}
		return $mapping_qualifierList;
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
						'actions' => ['index','view','vallobjectsuniondepdrop'],
						'roles' => ['author', 'global-view', 'view' ."-" . Yii::$app->controller->id],
					],
					[
						'allow' => true,
						'actions' => ['create', 'update', 'createexternal', 'changedirectionajax'],
						'roles' => ['author', 'global-create', 'create' ."-" . Yii::$app->controller->id],
					],
					[
						'allow' => true,
						'actions' => ['delete', 'deleteajax'],
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
     * Lists all MapObject2Object models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MapObject2ObjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MapObject2Object model.
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
     * Creates a new MapObject2Object model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
				
		$model = new MapObject2Object();

		if (Yii::$app->request->post())
		{
			$model->load(Yii::$app->request->post());
		     
    	}    
			
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'mapping_qualifierList' => $this->getMappingQualifierList(),		// autogeneriert ueber gii/CRUD
'object_persistence_methodList' => $this->getObjectPersistenceMethodList(),		// autogeneriert ueber gii/CRUD
'datamanagement_processList' => $this->getDatamanagementProcessList(),		// autogeneriert ueber gii/CRUD
            ]);
        }
    }

    /**
     * Updates an existing MapObject2Object model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
				
		$model = $this->findModel($id);

		     
		
		
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
				            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'mapping_qualifierList' => $this->getMappingQualifierList(),		// autogeneriert ueber gii/CRUD
'object_persistence_methodList' => $this->getObjectPersistenceMethodList(),		// autogeneriert ueber gii/CRUD
'datamanagement_processList' => $this->getDatamanagementProcessList(),		// autogeneriert ueber gii/CRUD
            ]);
        }
		    }

    /**
     * Deletes an existing MapObject2Object model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		     
    
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
     * Finds the MapObject2Object model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MapObject2Object the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MapObject2Object::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	// Custom functions for MapObject2Object/Mapper.

	public function actionVallobjectsuniondepdrop() {
	
		// Methode fuer Ajaxcall aus der View um die Liste in Abh?ngigkeit der Dropdownliste "Objekttyp" zu liefern
		if (isset($_POST['depdrop_parents'])) 
		{
			$parents = $_POST['depdrop_parents'];
			if ($parents != null)
			{
				$objType_id = $parents[0];
				$filter_on_client_or_project = $parents[1]; // values can be a value of fk_project_id (with prefix "fk_project_id;") or fk_client_id (with prefix "fk_client_id;") or -1 (if -1 then show all)
				$out = $this->getVallobjectsunionListDepDrop($objType_id, $filter_on_client_or_project);
				$selected="";
				echo Json::encode(['output'=>$out, 'selected'=>$selected]);
				return;
			}
		}
		echo Json::encode(['output'=>'', 'selected'=>'']);
	}
	
	private function getVallobjectsunionListDepDrop($objType_id, $filter_on_client_or_project)
	{
		// Create list of objects for DepDrop
		$model1 = new \app\models\VAllObjectsUnion();
		if ($filter_on_client_or_project != "-1")
		{
			// The format can be of: "fk_project_id;<id or fk_project_id>" or "fk_client_id;<id or fk_client_id>"
			$client_Or_Project = explode(";", $filter_on_client_or_project)[0];
			if ($client_Or_Project == "fk_project_id")
			{
				$filter_project_id_param = explode(";", $filter_on_client_or_project)[1];
				$filter_client_id = \app\models\Project::find()->select("fk_client_id")->where(["id" => $filter_project_id_param]);
				$filter_project_id = \app\models\Project::find()->select("id")->where(["id" => $filter_project_id_param]);
			}
			else
			{
				$filter_client_id_param = explode(";", $filter_on_client_or_project)[1];
				$filter_client_id = \app\models\Project::find()->select("fk_client_id")->where(["fk_client_id" => $filter_client_id_param]);
				$filter_project_id = \app\models\Project::find()->select("id")->where(["fk_client_id" => $filter_client_id_param]);
			}

			$all_objects = $model1::find()
			   ->where(['in', "fk_project_id", $filter_project_id])
			   ->orWhere(['in', "fk_client_id", $filter_client_id])
			   ->all();
		}
		else
		{
			$all_objects = $model1::find()->all();
		}
		$object_typeList = [];
		foreach($all_objects as $object_item)
		{
			if ($objType_id=="*")		// show all object types
			{
				// if all shall be shown, then as a grouped dropdown list
				if ($filter_on_client_or_project != "-1")
				{
					$object_typeList[$object_item->object_type_name][$object_item->listkey] = ['id' => $object_item->listkey, 'name' => $object_item->listvalue_1 ];
				}
				else
				{
					$object_typeList[$object_item->object_type_name][$object_item->listkey] = ['id' => $object_item->listkey, 'name' => $object_item->listvalue_1_with_client_or_project ];
				}
			}
			else
			{
				if ($object_item->fk_object_type_id==$objType_id)
				{
					if ($filter_on_client_or_project != "-1")
					{							
						array_push($object_typeList, ['id' => $object_item->listkey, 'name' => $object_item->listvalue_1 ]);
					}
					else
					{							
						array_push($object_typeList, ['id' => $object_item->listkey, 'name' => $object_item->listvalue_1_with_client_or_project ]);
					}
				}
			}
		}
		return $object_typeList;
	}
	
	public function actionCreateexternal($ref_fk_object_id, $ref_fk_object_type_id) {
	
		$app_config_mapper_createext_time_limit = \vendor\meta_grid\helper\Utils::get_app_config("mapper_createext_time_limit");
		set_time_limit($app_config_mapper_createext_time_limit);
		$app_config_mapper_createext_memory_limit = \vendor\meta_grid\helper\Utils::get_app_config("mapper_createext_memory_limit");
		ini_set('memory_limit', $app_config_mapper_createext_memory_limit."M");

		// Controller fuer die View, wenn ein Mapping ueber ein Objekt aufgerufen wird.		
		$objectTypeModel = new \app\models\ObjectType ();
		$objectTypes = $objectTypeModel::find ()->all ();
		$objectTypesList = array ();
		$objectTypesList [null] = Yii::t('app', "Select...");
		foreach ( $objectTypes as $objectType ) {
			$objectTypesList [$objectType->id] = $objectType->name;
		}
		$objectTypesList ["*"] = "*";
		
		// Alle Objekte
		$VAllObjectsUnionModel = new \app\models\VAllObjectsUnion ();
		$VAllObjectsUnionItems = $VAllObjectsUnionModel::find ()->all ();
		$VAllObjectsUnionList = array ();
		foreach ( $VAllObjectsUnionItems as $VAllObjectsUnionItem ) {
			$VAllObjectsUnionList [$VAllObjectsUnionItem->listkey] = strip_tags($VAllObjectsUnionItem->listvalue_2);
		}
		
		$model = new MapObject2Object ();
		$model->ref_fk_object_id_1 = $ref_fk_object_id;
		$model->ref_fk_object_type_id_1 = $ref_fk_object_type_id;

		if (Yii::$app->request->post ())
		{	
			if (isset($_POST["VAllObjectsUnion"]["listkey"]))
			{
				$listkey = $_POST["VAllObjectsUnion"]["listkey"];

				if (! isset($listkey[1]))
				{
					$model->addError('ref_fk_object_type_id_2', '$listkey[1] may not be NULL!!'); // this message will not be seen. Prepared for future use!
				}
				else
				{
					$model->ref_fk_object_id_2 = explode(";", $listkey)[0];
					$model->ref_fk_object_type_id_2 = explode(";", $listkey)[1];
				}			
			}
			else
			{
				$model->addError('ref_fk_object_type_id_1', '$listkey not set!'); // this message will not be seen. Prepared for future use!
			}
		}
		
		// Information about source mapping object

		$permClientsCanSee = Yii::$app->User->identity->permClientsCanSee;
    	$permProjectsCanSee = Yii::$app->User->identity->permProjectsCanSee;

		$SrcObjectInfo = new \app\models\VAllObjectsUnion ();
		$SrcObjectInfo = $VAllObjectsUnionModel::find()
			->where(['id' => $ref_fk_object_id, 'fk_object_type_id' => $ref_fk_object_type_id])
			->andWhere(['or',
				['in','fk_client_id', $permClientsCanSee],
				['in','fk_project_id', $permProjectsCanSee]
				])
			->one();

		if ($SrcObjectInfo == NULL)
		{
			throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'No data or you have no permission for this data.'));
		}

		$TitleSrcInformation = $SrcObjectInfo->listvalue_1;
		$SrcFilterValueClientOrProjekt = $SrcObjectInfo->fk_project_id === null ? ("fk_client_id;" . $SrcObjectInfo->fk_client_id) : ("fk_project_id;" . $SrcObjectInfo->fk_project_id);

    	if ($model->load(Yii::$app->request->post()) && $model->save()) {
    		
    		// zurueck woher man gekommen ist...
    		$goBackToView = str_replace("_", "", $objectTypesList[$ref_fk_object_type_id])."/view"; 
			$goBackToId = $ref_fk_object_id;
    		
    		return $this->redirect([$goBackToView, 'id' => $goBackToId]);
				} else {
			return $this->render ( '_create_external', [ 
					'model' => $model,
					'objectTypesList' => $objectTypesList,
					'VAllObjectsUnionList' => $VAllObjectsUnionList,
					'TitleSrcInformation' => $TitleSrcInformation,
					'SrcFilterValueClientOrProjekt' => $SrcFilterValueClientOrProjekt
			] );
		}
	}

	public function actionAppglobalsearch($q) {
	
	// @ToDo: Code muss aufgeraeumt werden
		$ref_fk_object_id=-123;
		$ref_fk_object_type_id=-456;
		
		// TEST
		$objectTypeModel = new \app\models\ObjectType ();
		$objectTypes = $objectTypeModel::find ()->all ();
		$objectTypesList = array ();
		foreach ( $objectTypes as $objectType ) {
			$objectTypesList [$objectType->id] = $objectType->name;
		}
	
		// Alle Objekte
		$VAllObjectsUnionModel = new \app\models\VAllObjectsUnion ();
		$VAllObjectsUnionItems = $VAllObjectsUnionModel::find ()->all ();
		$VAllObjectsUnionList = array ();
		foreach ( $VAllObjectsUnionItems as $VAllObjectsUnionItem ) {
			$VAllObjectsUnionList [$VAllObjectsUnionItem->listkey] = strip_tags($VAllObjectsUnionItem->listvalue_2);
		}
	
		$model = new MapObject2Object ();
		$model->ref_fk_object_id_1 = $ref_fk_object_id;
		$model->ref_fk_object_type_id_1 = $ref_fk_object_type_id;
	
		if (Yii::$app->request->post ())
		{
 			$listkey = $_POST["openobject"];
	
 			$ref_fk_object_id = explode(";", $listkey)[0];
 			$ref_fk_object_type_id = explode(";", $listkey)[1];
	
 			if (substr($ref_fk_object_id,0,8)=="Shortcut")
 			{
 				// Ziel oeffnen
 				$goBackToView = str_replace("_", "", $objectTypesList[$ref_fk_object_type_id])."/create";
//  				$goBackToId = $ref_fk_object_id;
 					
 				return $this->redirect([$goBackToView]);
 					
 			}
 			else
 			{
 				// Ziel oeffnen
 				$goBackToView = str_replace("_", "", $objectTypesList[$ref_fk_object_type_id])."/view";
 				$goBackToId = $ref_fk_object_id;
 				
 				return $this->redirect([$goBackToView, 'id' => $goBackToId]);
 			}
 			
		} else {
			return $this->render ( '_appglobalsearch', [
					'model' => $model,
					'objectTypesList' => $objectTypesList,
					'VAllObjectsUnionList' => $VAllObjectsUnionList
			] );
		}
	}
	
	public function actionChangedirectionajax()
	{
		$data = Yii::$app->request->post('id');
		if (isset($data)) {
			$returnValue = $this->actionChangedirection($data);
			$chk = $returnValue;
		} else {
			$chk = -500;
		}
		return \yii\helpers\Json::encode($chk);
	}

	private function actionChangedirection($id) {
		
		try{
			$model = MapObject2Object::findOne($id);
			if ($model == null) {
				return -100;
			}
			$ref_fk_object_id_1 = $model->ref_fk_object_id_1;
			$ref_fk_object_type_id_1 = $model->ref_fk_object_type_id_1;
			$ref_fk_object_id_2 = $model->ref_fk_object_id_2;
			$ref_fk_object_type_id_2 = $model->ref_fk_object_type_id_2;
			
			$model->ref_fk_object_id_1 = $ref_fk_object_id_2;
			$model->ref_fk_object_type_id_1 = $ref_fk_object_type_id_2;
			$model->ref_fk_object_id_2 = $ref_fk_object_id_1;
			$model->ref_fk_object_type_id_2 = $ref_fk_object_type_id_1;
			
			$model->save();
			return 100;
		}
		catch (\Exception $e) 
		{
			Yii::trace($e, 'MapperController -> actionChangedirection');
			return -999;
		}
	}

	public function actionDeleteajax()
	{
		$data = Yii::$app->request->post('id');
		if (isset($data)) {
			$returnValue = $this->delete($data);
			$chk = $returnValue;
		} else {
			$chk = -500;
		}
		return \yii\helpers\Json::encode($chk);
	}

	private function delete($id)
    {
		try {
			$model = $this->findModel($id);
			$model->delete();
			return 100;
		} catch (\Exception $e) {
			$model->addError(null, $e->getMessage());
			$errMsg = $e->getMessage();
			
			$errMsgAdd = "";
			try{$errMsgAdd = '"'. $model->name . '"';} catch(\Exception $e){}

			if (strpos($errMsg, "Integrity constraint violation")) $errMsg = Yii::t('yii',"The object {errMsgAdd} is still referenced by other objects.", ['errMsgAdd' => $errMsgAdd]);
			Yii::$app->session->setFlash('deleteError', Yii::t('yii','Object can\'t be deleted: ') . $errMsg);
			return -999;
		}
    }

}