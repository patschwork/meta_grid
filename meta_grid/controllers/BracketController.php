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
			'model' => $modelBracket,
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
  				'modelsBracketSearchPattern' => (empty($modelsBracketSearchPattern)) ? [new BracketSearchPattern] : $modelsBracketSearchPattern
        ]);
		    }

    /**
     * Deletes an existing Bracket model.
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