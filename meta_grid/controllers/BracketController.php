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
use app\models\ObjectTypeAsSearchFilter;
use app\models\ObjectPersistenceMethod;
use app\models\DatamanagementProcess;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\models\BracketSearchPattern;
use app\models\BracketSearchPatternSearch;
use app\models\Model;
use Da\User\Filter\AccessRuleFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;


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
		$object_typeList[null] = null;
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
		$attributeList[null] = null;
		foreach($attributes as $attribute)
		{
			$attributeList[$attribute->id] = $attribute->name;
		}
		return $attributeList;
	}

	private function getObjectTypeAsSearchFilterList()
	{
		// autogeneriert ueber gii/CRUD
		$object_type_as_searchFilterModel = new ObjectType();
		$object_type_as_searchFilters = $object_type_as_searchFilterModel::find()->all();
		$object_type_as_searchFilterList = array();
		$object_type_as_searchFilterList[null] = null;
		foreach($object_type_as_searchFilters as $object_type_as_searchFilter)
		{
			$object_type_as_searchFilterList[$object_type_as_searchFilter->id] = $object_type_as_searchFilter->name;
		}
		return $object_type_as_searchFilterList;
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

	public function registerControllerRole()
	{
		$metagrid_role_management = new \vendor\meta_grid\helper\Rolemanagement();
		$metagrid_role_management->registerControllerRole(Yii::$app->controller->id);
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
     * @param int $id ID
     * @return mixed
     */
    public function actionView($id)
    {
		$modelBracket = $this->findModel($id); 
		$modelsBracketSearchPattern = $modelBracket->bracketSearchPatterns; 
		        
		return $this->render('view', [
			'model' => $modelBracket,
			'modelsBracketSearchPattern' => $modelsBracketSearchPattern,
		]);
		}

    /**
     * Creates a new Bracket model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($isfrommodal = false, $modalparent = "", $refreshfield = "")
    {
		$modelBracket = new Bracket();

		$modelBracket->load(Yii::$app->request->post());

		if (isset($modelBracket->fkProject)) { // prevent Error, when new dataset will be created				
			if (!in_array($modelBracket->fkProject->id, Yii::$app->User->identity->permProjectsCanEdit)) {	
				throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You have no permission to edit this data.'));
				return;
			}
		}


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
  				'model' => $modelBracket,
   				'object_typeList' => $this->getObjectTypeList(),		// autogeneriert ueber gii/CRUD
   				'projectList' => $this->getProjectList(),		// autogeneriert ueber gii/CRUD
   				'attributeList' => $this->getAttributeList(),		// autogeneriert ueber gii/CRUD
  				'object_type_as_searchFilterList' => $this->getObjectTypeAsSearchFilterList(),		// autogeneriert ueber gii/CRUD
  				'object_persistence_methodList' => $this->getObjectPersistenceMethodList(),		// autogeneriert ueber gii/CRUD
  				'datamanagement_processList' => $this->getDatamanagementProcessList(),		// autogeneriert ueber gii/CRUD
  				'modalparent'                   => $modalparent,
  				'refreshfield'                  => $refreshfield,				
  				'modelsBracketSearchPattern' => (empty($modelsBracketSearchPattern)) ? [new BracketSearchPattern] : $modelsBracketSearchPattern
      		]);
		    }

    /**
     * Updates an existing Bracket model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$modelBracket = $this->findModel($id);

		if (!in_array($modelBracket->fkProject->id, Yii::$app->User->identity->permProjectsCanEdit)) {	
			throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You have no permission to edit this data.'));
			return;
		}

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
  				'model' => $modelBracket,
   				'object_typeList' => $this->getObjectTypeList(),		// autogeneriert ueber gii/CRUD
   				'projectList' => $this->getProjectList(),		// autogeneriert ueber gii/CRUD
   				'attributeList' => $this->getAttributeList(),		// autogeneriert ueber gii/CRUD
  				'object_type_as_searchFilterList' => $this->getObjectTypeAsSearchFilterList(),		// autogeneriert ueber gii/CRUD
  				'object_persistence_methodList' => $this->getObjectPersistenceMethodList(),		// autogeneriert ueber gii/CRUD
  				'datamanagement_processList' => $this->getDatamanagementProcessList(),		// autogeneriert ueber gii/CRUD
  				'modalparent'                   => '',
  				'refreshfield'                  => '',				
  				'modelsBracketSearchPattern' => (empty($modelsBracketSearchPattern)) ? [new BracketSearchPattern] : $modelsBracketSearchPattern
        ]);
		    }

    /**
     * Deletes an existing Bracket model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     */
    public function actionDelete($id)
    {
		 if (!in_array($this->findModel($id)->fkProject->id, Yii::$app->User->identity->permProjectsCanEdit)) {throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You have no permission to edit this data.'));
	return;	}    
    
		try {
			$model = $this->findModel($id);
			$model->delete();
			return $this->redirect(\yii\helpers\Url::previous(Yii::$app->controller->id."/INDEX"));
		} catch (\Exception $e) {
			$model->addError(null, $e->getMessage());
			$errMsg = $e->getMessage();
			
			$errMsgAdd = "";
			try{$errMsgAdd = '"'. $model->name . '"';} catch(\Exception $e){}

			if (strpos($errMsg, "Integrity constraint violation")) $errMsg = Yii::t('yii',"The object {errMsgAdd} is still referenced by other objects.", ['errMsgAdd' => $errMsgAdd]);
			Yii::$app->session->setFlash('deleteError', Yii::t('yii','Object can\'t be deleted: ') . $errMsg);
			return $this->redirect(\yii\helpers\Url::previous(Yii::$app->controller->id."/INDEX"));  // Url::remember() is set in index-view
		}

    }

    /**
     * Finds the Bracket model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
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