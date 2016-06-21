<?php

namespace app\controllers;

use Yii;
use app\models\DBTable;
use app\models\DBTableSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\ObjectType;
use app\models\Project;
use app\models\DbTableContext;
use app\models\DbTableType;


/**
 * DbtableController implements the CRUD actions for DBTable model.
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
		foreach($db_table_types as $db_table_type)
		{
			$db_table_typeList[$db_table_type->id] = $db_table_type->name;
		}
		return $db_table_typeList;
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
     * Lists all DBTable models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DBTableSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DBTable model.
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
     * Creates a new DBTable model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DBTable();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'object_typeList' => $this->getObjectTypeList(),		// autogeneriert ueber gii/CRUD
'projectList' => $this->getProjectList(),		// autogeneriert ueber gii/CRUD
'db_table_contextList' => $this->getDbTableContextList(),		// autogeneriert ueber gii/CRUD
'db_table_typeList' => $this->getDbTableTypeList(),		// autogeneriert ueber gii/CRUD
            ]);
        }
    }

    /**
     * Updates an existing DBTable model.
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
'db_table_contextList' => $this->getDbTableContextList(),		// autogeneriert ueber gii/CRUD
'db_table_typeList' => $this->getDbTableTypeList(),		// autogeneriert ueber gii/CRUD
            ]);
        }
    }

    /**
     * Deletes an existing DBTable model.
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
     * Finds the DBTable model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DBTable the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DBTable::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
