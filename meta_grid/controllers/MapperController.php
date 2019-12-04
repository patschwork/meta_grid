<?php

namespace app\controllers;

use Yii;
use app\models\MapObject2Object;
use app\models\MapObject2ObjectSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\models\ObjectType;
use yii\helpers\Html;
use yii\helpers\Json;
use app\models\base\Project;
use Da\User\Filter\AccessRuleFilter;
use yii\filters\AccessControl;

/**
 * MapperController implements the CRUD actions for MapObject2Object model.
 */
class MapperController extends Controller
{
		
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
		     
    
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
				$out = $this->getVallobjectsunionListDepDrop($objType_id);
				$selected="";
				echo Json::encode(['output'=>$out, 'selected'=>$selected]);
				return;
			}
		}
		echo Json::encode(['output'=>'', 'selected'=>'']);
	}
	
	private function getVallobjectsunionListDepDrop($objType_id)
	{
		// Liste der Objekte aufbereiten fuer DepDrop Liste
		$object_type_Model = new \app\models\ObjectType();
		$model1 = new \app\models\VAllObjectsUnion();
		$all_objects = $model1::find()->all();
		$object_typeList = [];
		foreach($all_objects as $object_item)
		{
			if ($objType_id=="*")		// Alle Objekttypen anzeigen
			{
				// Wenn alle angezeigt weden sollen, dann als gruppierte DropDown Liste
				$object_typeList[$object_item->object_type_name][$object_item->listkey] = ['id' => $object_item->listkey, 'name' => $object_item->listvalue_1 ];
	
				// 				// listvalue_2 Beispiel: sourcesystem - SAP HR
				// 				$object_typeList[$object_item->object_type_name][$object_item->listkey] = ['id' => $object_item->listkey, 'name' => $object_item->listvalue_2 ];
			}
			else
			{
				if ($object_item->fk_object_type_id==$objType_id)
				{
					array_push($object_typeList, ['id' => $object_item->listkey, 'name' => $object_item->listvalue_1 ]);
				}
			}
		}
		return $object_typeList;
	}
	
	public function actionCreateexternal($ref_fk_object_id, $ref_fk_object_type_id) {
	
		// Controller fuer die View, wenn ein Mapping ueber ein Objekt aufgerufen wird.		
		$objectTypeModel = new \app\models\ObjectType ();
		$objectTypes = $objectTypeModel::find ()->all ();
		$objectTypesList = array ();
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
			$listkey = $_POST["VAllObjectsUnion"]["listkey"];
				
			$model->ref_fk_object_id_2 = explode(";", $listkey)[0];
			$model->ref_fk_object_type_id_2 = explode(";", $listkey)[1];
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
					'TitleSrcInformation' => $TitleSrcInformation
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
}