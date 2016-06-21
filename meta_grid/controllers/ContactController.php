<?php

namespace app\controllers;

use Yii;
use app\models\Contact;
use app\models\ContactSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\ObjectType;
use app\models\ContactGroup;
use app\models\Client;

use yii\helpers\Json;	// Patrick, 2016-01-17

/**
 * ContactController implements the CRUD actions for Contact model.
 */
class ContactController extends Controller
{
	
	
	public function actionContactgroupdepdrop() {
		if (isset($_POST['depdrop_parents'])) {

			$parents = $_POST['depdrop_parents'];
			if ($parents != null) 
			{
				$fk_client_id = $parents[0];
				$out = $this->getContactGroupListDepDrop($fk_client_id);
				$selected="";				
				echo Json::encode(['output'=>$out, 'selected'=>$selected]);
				return;
			}
		}
		echo Json::encode(['output'=>'', 'selected'=>'']);
	}
	
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

	private function getContactGroupList()
	{
		// autogeneriert ueber gii/CRUD
		$contact_groupModel = new ContactGroup();
		$contact_groups = $contact_groupModel::find()->all();
		$contact_groupList = array();
		foreach($contact_groups as $contact_group)
		{
			$contact_groupList[$contact_group->id] = $contact_group->name;
		}
		return $contact_groupList;
	}

	private function getContactGroupListDepDrop($fk_client_id)
	{
		// autogeneriert ueber gii/CRUD
		
		$contact_groupModel = new ContactGroup();
		$contact_groups = $contact_groupModel::find()->all();
		$contact_groupList = [];
		foreach($contact_groups as $contact_group)
		{
			if ($contact_group->fk_client_id==$fk_client_id)
			{				
				array_push($contact_groupList, ['id' => $contact_group->id, 'name' => $contact_group->name ]);
			}
		}
		return $contact_groupList;
	}
	
	
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
     * Lists all Contact models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ContactSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Contact model.
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
     * Creates a new Contact model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Contact();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'object_typeList' => $this->getObjectTypeList(),		// autogeneriert ueber gii/CRUD
'contact_groupList' => $this->getContactGroupList(),		// autogeneriert ueber gii/CRUD
'clientList' => $this->getClientList(),		// autogeneriert ueber gii/CRUD
            ]);
        }
    }

    /**
     * Updates an existing Contact model.
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
'contact_groupList' => $this->getContactGroupList(),		// autogeneriert ueber gii/CRUD
'clientList' => $this->getClientList(),		// autogeneriert ueber gii/CRUD
            ]);
        }
    }

    /**
     * Deletes an existing Contact model.
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
     * Finds the Contact model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Contact the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Contact::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
