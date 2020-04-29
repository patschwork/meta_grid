<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use Symfony\Component\VarDumper\VarDumper as VarDumperVarDumper;
use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;
use yii\helpers\VarDumper;
use yii\helpers\Inflector;	// Patrick, 2016-01-15, #fk_Felder

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
}

/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Yii;
use <?= ltrim($generator->modelClass, '\\') ?>;
<?php if (!empty($generator->searchModelClass)): ?>
use <?= ltrim($generator->searchModelClass, '\\') . (isset($searchModelAlias) ? " as $searchModelAlias" : "") ?>;
<?php else: ?>
use yii\data\ActiveDataProvider;
<?php endif; ?>
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

<?php
// Patrick, 2016-01-15, #fk_Felder
$columnslist = $generator->getColumnNames();
foreach ($columnslist as $column) {
	if (substr($column,0,3)=="fk_")
	{
		$fk_model_variable = str_replace("_id","",str_replace("fk_","",$column));
		$fk_model_function = strtoupper(substr($fk_model_variable,0,1)).substr($fk_model_variable,1,strlen($fk_model_variable)-1);
		$fk_model_function = str_replace(" ","",Inflector::camel2words($fk_model_function));
		if ($fk_model_function=="ContactGroupAsDataOwner") $fk_model_function="ContactGroup";
		if ($fk_model_function=="ContactGroupAsSupporter") $fk_model_function="ContactGroup";
		echo "use app\\models\\$fk_model_function;\n";
	}
}
?>
<?php if ($generator->modelClass === 'app\models\Bracket'): ?>
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\models\BracketSearchPattern;
use app\models\BracketSearchPatternSearch;
use app\models\Model;
<?php endif; ?>
<?php if ($generator->modelClass === "app\models\MapObject2Object"): ?>
use yii\models\ObjectType;
use yii\helpers\Html;
use yii\helpers\Json;
use app\models\base\Project;
<?php endif; ?>
use Da\User\Filter\AccessRuleFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
<?php 
// Patrick, 2016-01-15, #fk_Felder
$actionCreateUpdateFkList = "";
?>

/**
 * <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
 */
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{
	<?php
		// Patrick, 2016-01-15, #fk_Felder
		$columnslist = $generator->getColumnNames();

	    foreach ($columnslist as $column) {
	        if (substr($column,0,3)=="fk_")
	        {
	        	$fk_model_variable = str_replace("_id","",str_replace("fk_","",$column));
	        	// Daraus wird fk_client_id --> client
	        	
	        	$fk_model_function = strtoupper(substr($fk_model_variable,0,1)).substr($fk_model_variable,1,strlen($fk_model_variable)-1);
	        	// Daraus wird client --> Client
	        	
	        	$fk_model_function = str_replace(" ","",Inflector::camel2words($fk_model_function));
	        	// Daraus wird Contact_group --> ContactGroup
	        	
	        	// echo $fk_model_variable;
	        	// echo $fk_model_function;

	        	echo "\n";
	        	echo "	private function get$fk_model_function"."List()\n";
	        	// Daraus wird --> private function getClientList()

	        	echo "	{\n";
	        	echo "		// autogeneriert ueber gii/CRUD\n";

	        	$newClass = $fk_model_function;
	        	
	        	// Ausnahmen definieren
	        	if ($newClass=="ContactGroupAsDataOwner") $newClass="ContactGroup";
	        	if ($newClass=="ContactGroupAsSupporter") $newClass="ContactGroup";
	        	if ($newClass=="ObjectTypeAsSearchFilter") $newClass="ObjectType";
				$nullIsAnValidOption = 0;
				
				if ($generator->modelClass === 'app\models\Bracket')
				{
					if ($newClass === "ObjectType") $nullIsAnValidOption = 1;			
					if ($newClass === "Attribute") $nullIsAnValidOption = 1;						
				}

				if ($newClass === "DbTable") $nullIsAnValidOption = 1;			
				if ($newClass === "DeletedStatus") $nullIsAnValidOption = 1;			
				if ($newClass === "DbTableContext") $nullIsAnValidOption = 1;			
				if ($newClass === "DbTableType") $nullIsAnValidOption = 1;

	        	echo "		$$fk_model_variable"."Model = new $newClass();\n";
				echo "		$$fk_model_variable"."s = $$fk_model_variable"."Model::find()->all();\n";
				echo "		$$fk_model_variable"."List = array();\n";
				echo $nullIsAnValidOption === 1 ? "		$$fk_model_variable"."List[null] = null;\n" : "";  
				echo "		foreach($$fk_model_variable"."s as $$fk_model_variable".")\n";
				echo "		{\n";
				$fieldPropertyName = "name";
				if ($fk_model_variable=="tool") $fieldPropertyName="tool_name";
				echo "			$$fk_model_variable"."List[$$fk_model_variable"."->id] = $$fk_model_variable"."->$fieldPropertyName;\n";
				echo "		}\n";
				echo "		return $$fk_model_variable"."List;\n";
				echo "	}\n";
	        
				$actionCreateUpdateFkList .= "'$fk_model_variable"."List' => \$this->get$fk_model_function"."List(),		// autogeneriert ueber gii/CRUD\n";
	        }
	    }
	?>
	
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
    
<?php if ($generator->modelClass === 'app\models\Client'): ?>
	private function createClientPermissions()
	{	
		$clientModel = new Client();
		$clients = $clientModel::find()->all();
		$clientList = array();
		foreach($clients as $client)
		{
			$auth = Yii::$app->authManager;
			$newRoleOrPermName="client-".$client->id."-read";
			$checkPerm = $auth->getPermission($newRoleOrPermName);
			if (is_null($checkPerm)) {
				$newAuthObj = $auth->createPermission($newRoleOrPermName);
				$newAuthObj->ruleName = "isNotAGuest";
				$newAuthObj->description = "Read-Permission for client " . $client->name;
				$newAuthObj->data = ['id' => $client->id, 'dataaccessfilter' => 'client', 'right' => 'read'];
				$auth->add($newAuthObj);
			}
			$auth = Yii::$app->authManager;
			$newRoleOrPermName="client-".$client->id."-write";
			$checkPerm = $auth->getPermission($newRoleOrPermName);
			if (is_null($checkPerm)) {
				$newAuthObj = $auth->createPermission($newRoleOrPermName);
				$newAuthObj->description = "Read-Permission for client " . $client->name;
				$newAuthObj->ruleName = "isNotAGuest";
				$newAuthObj->data = ['id' => $client->id, 'dataaccessfilter' => 'client', 'right' => 'write'];
				$auth->add($newAuthObj);
			}
		}
	}

	public function actionCreateclientpermissions()
  	{
  		$this->createClientPermissions();
  		echo "Client permissions created if needed.";
  		die;
  	}  
<?php endif; ?>

<?php if ($generator->modelClass === 'app\models\Project'): ?>
	private function createProjectPermissions()
	{
		$projectModel = new Project();
		$projects = $projectModel::find()->all();
		foreach($projects as $project)
		{
			// $clientList[$client->id] = $client->name;

			$auth = Yii::$app->authManager;
			$newRoleOrPermName="project-".$project->id."-read";
			$checkPerm = $auth->getPermission($newRoleOrPermName);
			if (is_null($checkPerm)) {
				$newAuthObj = $auth->createPermission($newRoleOrPermName);
				$newAuthObj->ruleName = "isNotAGuest";
				$newAuthObj->description = "Read-Permission for project " . $project->name . " (" . $project->fkClient->name . ")";
				$newAuthObj->data = ['id' => $project->id, 'dataaccessfilter' => 'project', 'right' => 'read'];
				$auth->add($newAuthObj);
			}
			$auth = Yii::$app->authManager;
			$newRoleOrPermName="project-".$project->id."-write";
			$checkPerm = $auth->getPermission($newRoleOrPermName);
			if (is_null($checkPerm)) {
				$newAuthObj = $auth->createPermission($newRoleOrPermName);
				$newAuthObj->description = "Read-Permission for project " . $project->name . " (" . $project->fkClient->name . ")";
				$newAuthObj->ruleName = "isNotAGuest";
				$newAuthObj->data = ['id' => $project->id, 'dataaccessfilter' => 'project', 'right' => 'write'];
				$auth->add($newAuthObj);
			}
		}
	}

	public function actionCreateprojectpermissions()
	{
		$this->createProjectPermissions();
		echo "Project permissions created if needed.";
		die;
	}
	
<?php endif; ?>

    /**
     * Lists all <?= $modelClass ?> models.
     * @return mixed
     */
    public function actionIndex()
    {
<?php if (!empty($generator->searchModelClass)): ?>
        $searchModel = new <?= isset($searchModelAlias) ? $searchModelAlias : $searchModelClass ?>();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
<?php else: ?>
        $dataProvider = new ActiveDataProvider([
            'query' => <?= $modelClass ?>::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
<?php endif; ?>
    }

    /**
     * Displays a single <?= $modelClass ?> model.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionView(<?= $actionParams ?>)
    {
		<?php if ($generator->modelClass === 'app\models\Bracket'): ?>
$modelBracket = $this->findModel($id); 
		$modelsBracketSearchPattern = $modelBracket->bracketSearchPatterns; 
		        
		return $this->render('view', [
			'model' => $modelBracket,
			'modelsBracketSearchPattern' => $modelsBracketSearchPattern,
		]);
		<?php else: ?>
        return $this->render('view', [
            'model' => $this->findModel(<?= $actionParams ?>),
        ]);
		<?php endif; ?>
}

    /**
     * Creates a new <?= $modelClass ?> model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		<?php if ($generator->modelClass === 'app\models\Bracket'): ?>
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

		<?php else: ?>
        $model = new <?= $modelClass ?>();

		if (Yii::$app->request->post())
		{
			$model->load(Yii::$app->request->post());
		 <?php 
		 // dynamisch auslesen ob es ein fk_project_id oder fk_client_id Feld gibt:
		 $tableSchema = $generator->getTableSchema();
		 $throwPart = "throw new \\yii\web\\ForbiddenHttpException(Yii::t('yii', 'You have no permission to edit this data.'));" . "\n" . "\treturn;" . "\t}";
		 foreach ($tableSchema->columns as $column)
		 {
		  	if ($column->name=="fk_client_id") {
				echo 'if (!in_array($model->fkClient->id, Yii::$app->User->identity->permClientsCanEdit)) {';
				echo $throwPart;
		  	};
		    if ($column->name=="fk_project_id") {
		  		echo 'if (!in_array($model->fkProject->id, Yii::$app->User->identity->permProjectsCanEdit)) {';
				echo $throwPart;
			};
		 }   
		  ?>    
    	}    
			
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
		<?php if ($generator->modelClass === 'app\models\Client'): ?>
        	$this->createClientPermissions();
		<?php endif; ?>
		<?php if ($generator->modelClass === 'app\models\Project'): ?>
        	$this->createProjectPermissions();			
		<?php endif; ?>
        	return $this->redirect(['view', <?= $urlParams ?>]);
        } else {
            return $this->render('create', [
                'model' => $model,
                <?= $actionCreateUpdateFkList ?>
            ]);
        }
		<?php endif; ?>
    }

    /**
     * Updates an existing <?= $modelClass ?> model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionUpdate(<?= $actionParams ?>)
    {
		<?php if ($generator->modelClass === 'app\models\Bracket'): ?>
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
		<?php else: ?>
		$model = $this->findModel(<?= $actionParams ?>);

		 <?php 
		 // dynamisch auslesen ob es ein fk_project_id oder fk_client_id Feld gibt:
		 $tableSchema = $generator->getTableSchema();
		 $throwPart = "throw new \\yii\web\\ForbiddenHttpException(Yii::t('yii', 'You have no permission to edit this data.'));" . "\n" . "\treturn;" . "\t}";
		 foreach ($tableSchema->columns as $column)
		 {
		  	if ($column->name=="fk_client_id") {
		  		echo 'if (!in_array($model->fkClient->id, Yii::$app->User->identity->permClientsCanEdit)) {';
				echo $throwPart;
			};
		    if ($column->name=="fk_project_id") {
		  		echo 'if (!in_array($model->fkProject->id, Yii::$app->User->identity->permProjectsCanEdit)) {';
				echo $throwPart;
			};
		 }   
		  ?>    
		
		
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
		<?php if ($generator->modelClass === 'app\models\Client'): ?>
        	$this->createClientPermissions();
		<?php endif; ?>
		<?php if ($generator->modelClass === 'app\models\Project'): ?>
        	$this->createProjectPermissions();			
		<?php endif; ?>
            return $this->redirect(['view', <?= $urlParams ?>]);
        } else {
            return $this->render('update', [
                'model' => $model,
                <?= $actionCreateUpdateFkList ?>
            ]);
        }
		<?php endif; ?>
    }

    /**
     * Deletes an existing <?= $modelClass ?> model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionDelete(<?= $actionParams ?>)
    {
		 <?php 
		 // dynamisch auslesen ob es ein fk_project_id oder fk_client_id Feld gibt:
		 $tableSchema = $generator->getTableSchema();
		 $throwPart = "throw new \\yii\web\\ForbiddenHttpException(Yii::t('yii', 'You have no permission to edit this data.'));" . "\n" . "\treturn;" . "\t}";
		 foreach ($tableSchema->columns as $column)
		 {
		  	if ($column->name=="fk_client_id") {
		  		echo 'if (!in_array($this->findModel($id)->fkClient->id, Yii::$app->User->identity->permClientsCanEdit)) {';
				echo $throwPart;
			};
		    if ($column->name=="fk_project_id") {
		  		echo 'if (!in_array($this->findModel($id)->fkProject->id, Yii::$app->User->identity->permProjectsCanEdit)) {';
				echo $throwPart;
			};
		 }   
		  ?>    
    
		try {
			$model = $this->findModel(<?= $actionParams ?>);
			$model->delete();
			return $this->redirect(['index']);
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
     * Finds the <?= $modelClass ?> model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return <?=                   $modelClass ?> the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(<?= $actionParams ?>)
    {
<?php
if (count($pks) === 1) {
    $condition = '$id';
} else {
    $condition = [];
    foreach ($pks as $pk) {
        $condition[] = "'$pk' => \$$pk";
    }
    $condition = '[' . implode(', ', $condition) . ']';
}
?>
        if (($model = <?= $modelClass ?>::findOne(<?= $condition ?>)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
<?php if ($generator->modelClass === "app\models\Keyfigure"): ?>
    // Custom Implementation for exporting to XML or JSON format
    private function exportBase($format,$queryResult)
    {
    	 
    	if ($format == "" || !($queryResult))
    	{
    		\Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
    		return "No format parameter given or what unknown.<br>Avaiable values for format are: XML,JSON.<br>Avaiable values for what are: main,comments,mappings";
    	}
    	else
    	{
    		$headers = Yii::$app->response->headers;
    		if ($format == "XML")
    		{
    			$headers->add('Content-Type', 'text/xml; charset=utf-8');
    			\Yii::$app->response->format = \yii\web\Response::FORMAT_XML;
    			$export_result = $queryResult;
    
    			return $export_result;
    		};
    
    		if ($format == "JSON")
    		{
    			$headers->add('Content-Type', 'text; charset=utf-8');
    			\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    			$export_result = $queryResult;
    			return $export_result;
    		};
    	}
    }
    
    // Example for call: http://localhost:81/dwh_meta_v2_DEV/frontend/yii/basic/web/index.php?r=keyfigure/export&format=XML&what=
    public function actionExport($format,$what)
    {
    	if ($what=="" || $what=="main")
    	{
    		$model =  new \app\models\Keyfigure();
    		return $this->exportBase($format, $model->find()->all());
    	}
    	else
    	{
    	    if ($what=="comments")
    		{
    			$model = new \app\models\Objectcomment;
    			$ot_model = new \app\models\VAllObjectsUnion;
    			$query_ot = $ot_model::find()->distinct(true)->select(['fk_object_type_id'])->where(['object_type_name' => 'keyfigure'])->one(); 
    			$query = $model::find()->where(['ref_fk_object_type_id' => $query_ot->fk_object_type_id])->all();
    			return $this->exportBase($format, $query);
    		}			    		
    	   	if ($what=="mappings")
    		{
    			$model = new \app\models\VAllMappingsUnion;
    			$ot_model = new \app\models\VAllObjectsUnion;
    			$query_ot = $ot_model::find()->distinct(true)->select(['fk_object_type_id'])->where(['object_type_name' => 'keyfigure'])->one();
    			$query = $model::find()->where(['filter_ref_fk_object_type_id' => $query_ot->fk_object_type_id])->all();
    			return $this->exportBase($format, $query);
    		}			    		
    	    if ($what!="!elsecasedummy!")
    		{
    			return $this->exportBase($format, null);
    		}			    		
    	}
    }
    
    public function actionExporttest($format)
    {
    	if ($format == "XML")
    	{
   			$headers->add('Content-Type', 'text/xml; charset=utf-8');
    		\Yii::$app->response->format = \yii\web\Response::FORMAT_XML;
    	}    	 

    	if ($format == "JSON")
    	{
    		$headers->add('Content-Type', 'text; charset=utf-8');
    		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	}
    	 
    	if ($format == "")
    	{
    		\Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
    		return "No format parameter given. Avaiable values are: XML,JSON";
    	}
    	 
		// SQL Query klappt auch
    	/*
			 SELECT 
				 k.*
				,c.uuid AS uuid_comment
				,c.comment
			FROM keyfigure k
			LEFT JOIN object_comment c ON
				c.ref_fk_object_id=k.id
					AND
				c.ref_fk_object_type_id=k.fk_object_type_id
			 * */
		$query = new \yii\db\Query;
		$query->select('k.*
					,c.uuid AS uuid_comment
					,c.comment')
 			->from('keyfigure k')
   			->leftJoin('object_comment c', 'c.ref_fk_object_id=k.id
						AND
					c.ref_fk_object_type_id=k.fk_object_type_id')
   			;
		$command = $query->createCommand();
		$export_result = $command->queryAll();
    			
  		return $export_result;

    }
<?php endif; ?>
<?php if ($generator->modelClass === "app\models\DbTableField"): ?>
	// Custom function.
	// Called from button in from views\dbtablefield\_index_external.php (used in db_table)
    public function actionCreateexternal($fk_db_table_id)
    {
    	$model = new DbTableField();
    
    	if ($model->load(Yii::$app->request->post()) && $model->save()) {
    		return $this->redirect(['view', 'id' => $model->id]);
    	} else {
    		return $this->render('create', [
    				'model' => $model,
    				'object_typeList' => $this->getObjectTypeList(),		// autogeneriert ueber gii/CRUD
    				'projectList' => $this->getProjectList(),		// autogeneriert ueber gii/CRUD
    				'db_tableList' => $this->getDbTableList(),		// autogeneriert ueber gii/CRUD
    				'fk_db_table_id' => $fk_db_table_id,
    		]);
    	}
    }
<?php endif; ?>
<?php if ($generator->modelClass === "app\models\MapObject2Object"): ?>
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
<?php endif; ?>
<?php if ($generator->modelClass === "app\models\Objectcomment"): ?>
	// Custom function.
	// Called from button "Create new comment" in all views of tab "Comments" 
	public function actionCreateexternal($ref_fk_object_id, $ref_fk_object_type_id)
        {
    
            $objectTypeModel = new \app\models\ObjectType ();
            $objectTypes = $objectTypeModel::find ()->all ();
            $objectTypesList = array ();
            foreach ( $objectTypes as $objectType ) {
                $objectTypesList [$objectType->id] = $objectType->name;
            }
                
            $model = new Objectcomment();
            $model->ref_fk_object_id=$ref_fk_object_id;
            $model->ref_fk_object_type_id=$ref_fk_object_type_id;
        
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                
                // zurueck woher man gekommen ist...
                $goBackToView = str_replace("_", "", $objectTypesList[$ref_fk_object_type_id])."/view"; 
                $goBackToId = $ref_fk_object_id;
                
                return $this->redirect([$goBackToView, 'id' => $goBackToId]);
            } else {
                return $this->render('_create_external', [
                        'model' => $model,
                ]);
            }
        }
<?php endif; ?>
}