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

/**
 * MapObject2ObjectController implements the CRUD actions for MapObject2Object model.
 */
class MapObject2ObjectController extends Controller {

	public function actionVallobjectsuniondepdrop() {
	
		// Methode für Ajaxcall aus der View um die Liste in Abhängigkeit der Dropdownliste "Objekttyp" zu liefern
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
		// Liste der Objekte aufbereiten für DepDrop Liste
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
	
	public function behaviors() {
		return [ 
				'verbs' => [ 
						'class' => VerbFilter::className (),
						'actions' => [ 
								'delete' => [ 
										'post' 
								] 
						] 
				] 
		];
	}
	
	/**
	 * Lists all MapObject2Object models.
	 *
	 * @return mixed
	 */
	public function actionIndex() {
		$searchModel = new MapObject2ObjectSearch ();
		$dataProvider = $searchModel->search ( Yii::$app->request->queryParams );
		
		return $this->render ( 'index', [ 
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider 
		] );
	}
	
	/**
	 * Displays a single MapObject2Object model.
	 *
	 * @param integer $id        	
	 * @return mixed
	 */
	public function actionView($id) {
		return $this->render ( 'view', [ 
				'model' => $this->findModel ( $id ) 
		] );
	}
	
	/**
	 * Creates a new MapObject2Object model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionCreate() {
		$objectTypeModel = new \app\models\ObjectType ();
		$objectTypes = $objectTypeModel::find ()->all ();
		$objectTypesList = array ();
		foreach ( $objectTypes as $objectType ) {
			$objectTypesList [$objectType->id] = $objectType->name;
		}
		
		$model = new MapObject2Object ();
		
		if ($model->load ( Yii::$app->request->post () ) && $model->save ()) {
			return $this->redirect ( [ 
					'view',
					'id' => $model->id 
			] );
		} else {
			return $this->render ( 'create', [ 
					'model' => $model,
					'objectTypesList' => $objectTypesList 
			] );
		}
	}
	public function actionCreateexternal($ref_fk_object_id, $ref_fk_object_type_id) {
	
		// Controller für die View, wenn ein Mapping über ein Objekt aufgerufen wird.		
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
		
    	if ($model->load(Yii::$app->request->post()) && $model->save()) {
    		
    		// zurueck woher man gekommen ist...
    		$goBackToView = str_replace("_", "", $objectTypesList[$ref_fk_object_type_id])."/view"; 
			$goBackToId = $ref_fk_object_id;
    		
    		return $this->redirect([$goBackToView, 'id' => $goBackToId]);
				} else {
			return $this->render ( '_create_external', [ 
					'model' => $model,
					'objectTypesList' => $objectTypesList,
					'VAllObjectsUnionList' => $VAllObjectsUnionList 
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
 				// Ziel öffnen
 				$goBackToView = str_replace("_", "", $objectTypesList[$ref_fk_object_type_id])."/create";
//  				$goBackToId = $ref_fk_object_id;
 					
 				return $this->redirect([$goBackToView]);
 					
 			}
 			else
 			{
 				// Ziel öffnen
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
	
	
	
	/**
	 * Updates an existing MapObject2Object model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id        	
	 * @return mixed
	 */
	public function actionUpdate($id) {
		$objectTypeModel = new \app\models\ObjectType ();
		$objectTypes = $objectTypeModel::find ()->all ();
		$objectTypesList = array ();
		foreach ( $objectTypes as $objectType ) {
			$objectTypesList [$objectType->id] = $objectType->name;
		}
		
		$model = $this->findModel ( $id );
		
		if ($model->load ( Yii::$app->request->post () ) && $model->save ()) {
			return $this->redirect ( [ 
					'view',
					'id' => $model->id 
			] );
		} else {
			return $this->render ( 'update', [ 
					'model' => $model,
					'objectTypesList' => $objectTypesList 
			] );
		}
	}
	
	/**
	 * Deletes an existing MapObject2Object model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id        	
	 * @return mixed
	 */
	public function actionDelete($id) {
		$this->findModel ( $id )->delete ();
		
		return $this->redirect ( [ 
				'index' 
		] );
	}
	
	/**
	 * Finds the MapObject2Object model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id        	
	 * @return MapObject2Object the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = MapObject2Object::findOne ( $id )) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException ( 'The requested page does not exist.' );
		}
	}
}
