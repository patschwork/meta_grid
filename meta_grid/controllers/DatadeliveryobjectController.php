<?php

namespace app\controllers;

use Yii;
use app\models\DataDeliveryObject;
use app\models\DataDeliveryObjectSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\ObjectType;
use app\models\Project;
use app\models\Tool;
use app\models\DataDeliveryType;
use app\models\ContactGroup;


/**
 * DatadeliveryobjectController implements the CRUD actions for DataDeliveryObject model.
 */
class DatadeliveryobjectController extends Controller
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

	private function getDataDeliveryTypeList()
	{
		// autogeneriert ueber gii/CRUD
		$data_delivery_typeModel = new DataDeliveryType();
		$data_delivery_types = $data_delivery_typeModel::find()->all();
		$data_delivery_typeList = array();
		foreach($data_delivery_types as $data_delivery_type)
		{
			$data_delivery_typeList[$data_delivery_type->id] = $data_delivery_type->name;
		}
		return $data_delivery_typeList;
	}

	private function getContactGroupAsDataOwnerList()
	{
		// autogeneriert ueber gii/CRUD
		//// $contact_group_as_data_ownerModel = new ContactGroupAsDataOwner();
		$contact_group_as_data_ownerModel = new ContactGroup();
		$contact_group_as_data_owners = $contact_group_as_data_ownerModel::find()->all();
		$contact_group_as_data_ownerList = array();
		foreach($contact_group_as_data_owners as $contact_group_as_data_owner)
		{
			$contact_group_as_data_ownerList[$contact_group_as_data_owner->id] = $contact_group_as_data_owner->name;
		}
		return $contact_group_as_data_ownerList;
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
     * Lists all DataDeliveryObject models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DataDeliveryObjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DataDeliveryObject model.
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
     * Creates a new DataDeliveryObject model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DataDeliveryObject();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'object_typeList' => $this->getObjectTypeList(),		// autogeneriert ueber gii/CRUD
'projectList' => $this->getProjectList(),		// autogeneriert ueber gii/CRUD
'toolList' => $this->getToolList(),		// autogeneriert ueber gii/CRUD
'data_delivery_typeList' => $this->getDataDeliveryTypeList(),		// autogeneriert ueber gii/CRUD
'contact_group_as_data_ownerList' => $this->getContactGroupAsDataOwnerList(),		// autogeneriert ueber gii/CRUD
            ]);
        }
    }

    /**
     * Updates an existing DataDeliveryObject model.
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
                'object_typeList' => $this->getObjectTypeList(),		// autogeneriert ueber gii/CRUD
'projectList' => $this->getProjectList(),		// autogeneriert ueber gii/CRUD
'toolList' => $this->getToolList(),		// autogeneriert ueber gii/CRUD
'data_delivery_typeList' => $this->getDataDeliveryTypeList(),		// autogeneriert ueber gii/CRUD
'contact_group_as_data_ownerList' => $this->getContactGroupAsDataOwnerList(),		// autogeneriert ueber gii/CRUD
            ]);
        }
    }

    /**
     * Deletes an existing DataDeliveryObject model.
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
     * Finds the DataDeliveryObject model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DataDeliveryObject the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DataDeliveryObject::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
