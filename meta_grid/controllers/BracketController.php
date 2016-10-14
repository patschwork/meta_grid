<?php

namespace app\controllers;

use Yii;
use app\models\Bracket;
use app\models\BracketSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\ObjectType;
use app\models\Project;
use app\models\Attribute;
//use app\models\ObjectType;		// !!!


// !!!
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\widgets\ActiveForm;
// use app\base\Model;
// use yii\base\Model;

use app\models\BracketSearchPattern;
use app\models\BracketSearchPatternSearch;
use app\models\Model;
// !!!

/**
 * BracketController implements the CRUD actions for Bracket model.
 */
class BracketController extends Controller
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

	private function getAttributeList()
	{
		// autogeneriert ueber gii/CRUD
		$attributeModel = new Attribute();
		$attributes = $attributeModel::find()->all();
		$attributeList = array();
		foreach($attributes as $attribute)
		{
			$attributeList[$attribute->id] = $attribute->name;
		}
		return $attributeList;
	}

	private function getObjectTypeAsSearchFilterList()
	{
		// autogeneriert ueber gii/CRUD
		$object_type_as_searchFilterModel = new ObjectType();		// !!!
		$object_type_as_searchFilters = $object_type_as_searchFilterModel::find()->all();
		$object_type_as_searchFilterList = array();
		foreach($object_type_as_searchFilters as $object_type_as_searchFilter)
		{
			$object_type_as_searchFilterList[$object_type_as_searchFilter->id] = $object_type_as_searchFilter->name;
		}
		return $object_type_as_searchFilterList;
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
     * Lists all Bracket models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BracketSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Bracket model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
    	$modelBracket = $this->findModel($id);
    	$modelsBracketSearchPattern = $modelBracket->bracketSearchPatterns;
    	
        return $this->render('view', [
            'modelBracket' => $modelBracket,
            'modelsBracketSearchPattern' => $modelsBracketSearchPattern,
        ]);
    }

    /**
     * Creates a new Bracket model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $modelBracket = new Bracket();

        $modelsBracketSearchPattern = [new BracketSearchPattern];
        
        
        if ($modelBracket->load(Yii::$app->request->post())) {
        	
        	$modelsBracketSearchPattern = Model::createMultiple(BracketSearchPattern::classname());
        	Model::loadMultiple($modelsBracketSearchPattern, Yii::$app->request->post());
        	
        	// validate all models
        	$valid = $modelBracket->validate();
        	
//			Kann nicht erfolgreich validiert werden, da fk_bracket_id erst noch erstellt werden muss (bei Create)
//         	$valid = Model::validateMultiple($modelsBracketSearchPattern) && $valid;
       	
        	if ($valid) {
        		//$transaction = \Yii::$app->db->beginTransaction();
        		
        		try {
        			if ($flag = $modelBracket->save(false)) {
        				
        				foreach ($modelsBracketSearchPattern as $modelBracketSearchPattern) {
        					$modelBracketSearchPattern->fk_bracket_id = $modelBracket->id;
        					if (! ($flag = $modelBracketSearchPattern->save(false))) {
//         						$transaction->rollBack();
        						break;
        					}
        				}
        			}
        			
        			
        			if ($flag) {
//         				$transaction->commit();
						return $this->redirect(['view', 'id' => $modelBracket->id]);
        			}
        		}
        		catch (Exception $e) {
//         			$transaction->rollBack();
        		}
        	}
        }

//         return $this->render('create', [
//         		'modelCustomer' => $modelCustomer,
//         		'modelsAddress' => (empty($modelsAddress)) ? [new Address] : $modelsAddress
//         ]);

        
        
       		return $this->render('create', [
  				'modelBracket' => $modelBracket,
   				'object_typeList' => $this->getObjectTypeList(),		// autogeneriert ueber gii/CRUD
   				'projectList' => $this->getProjectList(),		// autogeneriert ueber gii/CRUD
   				'attributeList' => $this->getAttributeList(),		// autogeneriert ueber gii/CRUD
  				'object_type_as_searchFilterList' => $this->getObjectTypeAsSearchFilterList(),		// autogeneriert ueber gii/CRUD
  				'modelsBracketSearchPattern' => (empty($modelsBracketSearchPattern)) ? [new BracketSearchPattern] : $modelsBracketSearchPattern
      		]);

	}

    
    /**
     * Updates an existing Bracket model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $modelBracket = $this->findModel($id);
        $modelsBracketSearchPattern = $modelBracket->bracketSearchPatterns;

        if ($modelBracket->load(Yii::$app->request->post())) {

            $oldIDs = ArrayHelper::map($modelsBracketSearchPattern, 'id', 'id');
            $modelsBracketSearchPattern = Model::createMultiple(BracketSearchPattern::classname(), $modelsBracketSearchPattern);
            Model::loadMultiple($modelsBracketSearchPattern, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsBracketSearchPattern, 'id', 'id')));

            // ajax validation
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ArrayHelper::merge(
                    ActiveForm::validateMultiple($modelsBracketSearchPattern),
                    ActiveForm::validate($modelBracket)
                );
            }

            // validate all models
            $valid = $modelBracket->validate();
//             $valid = Model::validateMultiple($modelsBracketSearchPattern) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $modelBracket->save(false)) {
                        if (! empty($deletedIDs)) {
                            BracketSearchPattern::deleteAll(['id' => $deletedIDs]);
                        }
                        foreach ($modelsBracketSearchPattern as $modelBracketSearchPattern) {
                            $modelBracketSearchPattern->fk_bracket_id = $modelBracket->id;
                            if (! ($flag = $modelBracketSearchPattern->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $modelBracket->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('update', [
  				'modelBracket' => $modelBracket,
   				'object_typeList' => $this->getObjectTypeList(),		// autogeneriert ueber gii/CRUD
   				'projectList' => $this->getProjectList(),		// autogeneriert ueber gii/CRUD
   				'attributeList' => $this->getAttributeList(),		// autogeneriert ueber gii/CRUD
  				'object_type_as_searchFilterList' => $this->getObjectTypeAsSearchFilterList(),		// autogeneriert ueber gii/CRUD
  				'modelsBracketSearchPattern' => (empty($modelsBracketSearchPattern)) ? [new BracketSearchPattern] : $modelsBracketSearchPattern
        ]);
    }

    
//     public function actionUpdate($id)
//     {
//     	// NOCH NICHT ANGEPASST !!!
//     	$model = $this->findModel($id);
    
//     	if ($model->load(Yii::$app->request->post()) && $model->save()) {
//     		return $this->redirect(['view', 'id' => $model->id]);
//     	} else {
//     		return $this->render('update', [
//     				'model' => $model,
//     				'object_typeList' => $this->getObjectTypeList(),		// autogeneriert ueber gii/CRUD
//     				'projectList' => $this->getProjectList(),		// autogeneriert ueber gii/CRUD
//     				'attributeList' => $this->getAttributeList(),		// autogeneriert ueber gii/CRUD
//     				'object_type_as_searchFilterList' => $this->getObjectTypeAsSearchFilterList(),		// autogeneriert ueber gii/CRUD
//     		]);
//     	}
//     }
    
    
    /**
     * Deletes an existing Bracket model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
    	// NOCH NICHT ANGEPASST !!!
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Bracket model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Bracket the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bracket::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
