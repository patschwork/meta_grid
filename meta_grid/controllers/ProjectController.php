<?php

namespace app\controllers;

use Yii;
use app\models\Project;
use app\models\ProjectSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\Client;

use yii\data\ActiveDataProvider;
use app\models\VAllMappingsUnion;
use app\models\Sourcesystem;
use app\models\DbDatabase;
use app\models\DbTable;
use app\models\DbTableField;
use app\models\Attribute;
use app\models\DataDeliveryObject;
use app\models\DataTransferProcess;
use app\models\Glossary;

/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends Controller
{
	private $dataCollection = array();
	
	private function getClientList()
	{
		// autogeneriert ueber gii/CRUD
		$clientModel = new Client();
		$clients = $clientModel::find()->all();
		$clientList = array();
		foreach($clients as $client)
		{
			$clientList[$client->id] = $client->name;
		}
		return $clientList;
	}
	
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Project models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Project model.
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
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Project();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'clientList' => $this->getClientList(),		// autogeneriert ueber gii/CRUD
            ]);
        }
    }

    /**
     * Updates an existing Project model.
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
                'clientList' => $this->getClientList(),		// autogeneriert ueber gii/CRUD
            ]);
        }
    }

    /**
     * Deletes an existing Project model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    private function collectData($modelQuery, $fk_project_id, $objName, $sortOrder)
    {
    	$query = $modelQuery;
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    	]);
    	$query->andFilterWhere([
    			'fk_project_id' => $fk_project_id,
    	]);
    	 
    	$object_name = Yii::t('app', $objName);
    	
    	foreach ($dataProvider->models as $key => $item)
    	{
    		$this->dataCollection[$sortOrder][$object_name][$key][Yii::t('app', 'Name')] = $item->name;
    		$this->dataCollection[$sortOrder][$object_name][$key][Yii::t('app', 'Description')] = $item->description;
//     		$this->dataCollection[$sortOrder][$object_name][$key]['listkey'] = $item->id.";".$item->fk_object_type_id;
    	    if ($item->fk_object_type_id==4)
    		{
    			$this->dataCollection[$sortOrder][$object_name][$key][Yii::t('app', 'Context')] = $item->fk_db_table_context_id == "" ? $item->fk_db_table_context_id : $item->fkDbTableContext->name;
    			$this->dataCollection[$sortOrder][$object_name][$key][Yii::t('app', 'Type')] = $item->fk_db_table_type_id == "" ? $item->fk_db_table_type_id : $item->fkDbTableType->name;
    		}
    	    if ($item->fk_object_type_id==5)
    		{
    			$this->dataCollection[$sortOrder][$object_name][$key][Yii::t('app', 'Belongs to Table')] = $item->fk_db_table_id == "" ? $item->fk_db_table_id : $item->fkDbTable->name;
    			$this->dataCollection[$sortOrder][$object_name][$key][Yii::t('app', 'Data Type')] = $item->datatype;
    		}
    	    if ($item->fk_object_type_id==3)
    		{
    			$this->dataCollection[$sortOrder][$object_name][$key][Yii::t('app', 'Type')] = $item->fk_data_delivery_type_id == "" ? $item->fk_data_delivery_type_id : $item->fkDataDeliveryType->name;
    			$this->dataCollection[$sortOrder][$object_name][$key][Yii::t('app', 'Data Owner')] = $item->fk_contact_group_id_as_data_owner == "" ? $item->fk_contact_group_id_as_data_owner : $item->fkContactGroupIdAsDataOwner->name;
    		}
    		if ($item->fk_object_type_id==13)
    		{
    			$this->dataCollection[$sortOrder][$object_name][$key][Yii::t('app', 'DataTransferType')] = $item->fk_data_transfer_type_id == "" ? $item->fk_data_transfer_type_id : $item->fkDataTransferType->name;
    		}
    		
    		$mapObject2ObjectSearchModel = new \app\models\VAllMappingsUnion();
    		$queryMapObject2Object = \app\models\VAllMappingsUnion::find();
    		$mapObject2ObjectDataProvider = new ActiveDataProvider([
    				'query' => $queryMapObject2Object,
    		]);
    		$queryMapObject2Object->andFilterWhere([
    				'filter_ref_fk_object_id' => $item->id,
    				'filter_ref_fk_object_type_id' => $item->fk_object_type_id,
    		]);

    		foreach ($mapObject2ObjectDataProvider->models as $mapKey => $mapItem)
    		{
    			$this->dataCollection[$sortOrder][$object_name][$key][Yii::t('app', 'Mapping')][$mapKey]['name'] = $mapItem->name;
    			$this->dataCollection[$sortOrder][$object_name][$key][Yii::t('app', 'Mapping')][$mapKey]['object_type_name'] = $mapItem->object_type_name;
//     			$this->dataCollection[$sortOrder][$object_name][$key][Yii::t('app', 'Mapping')][$mapKey]['listkey'] = $mapItem->listkey;
    		}
    	}
    }
    
    public function actionCreatedocumentation($id)
    {
		// Daten sammeln

//     	echo '<pre>';
    	 
    	$dataCollection = array();
    	
    	// Projects
    	$modelProject = $this->findModel($id);
    	$sortOrder = 1;
    	$object_name = Yii::t('app', "Project");
    	$this->dataCollection[$sortOrder][$object_name][0][Yii::t('app', 'Client')] = $modelProject->fkClient->name;
    	$this->dataCollection[$sortOrder][$object_name][0][Yii::t('app', 'Project')] = $modelProject->name;
    	$this->dataCollection[$sortOrder][$object_name][0][Yii::t('app', 'Description')] = $modelProject->description;

    	 


		$this->collectData(Sourcesystem::find(), $id, "Sourcesystems", 2);
    	$this->collectData(DbDatabase::find(), $id, "Databases", 3);
    	$this->collectData(DbTable::find(), $id, "Tables", 4);
//     	$this->collectData(DbTableField::find(), $id, "Table Fields", 5);
    	$this->collectData(Attribute::find(), $id, "Attributes", 6);
//     	$this->collectData(DataDeliveryObject::find(), $id, "Data Delivery Objects", 7);
//     	$this->collectData(DataTransferProcess::find(), $id, "Data Transfer Process", 8);
    	$this->collectData(Glossary::find(), $id, "Glossary", 9);
    	 
    	
    	
//     	\yii\helpers\VarDumper::dump($this->dataCollection);
//     	\yii\helpers\VarDumper::dump($dataCollection);
    	 
//     	echo '</pre>';
//     	exit();
    	 
    	
    	return $this->render('create_documentation', [
    			'dataCollection' => $this->dataCollection,
    	]);
    }

    /**
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Project::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
