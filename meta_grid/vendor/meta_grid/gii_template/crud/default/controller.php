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
<?php if ($generator->modelClass !== "app\models\Url"): ?>
use yii\helpers\Url;<?php endif; ?>

<?php if ($generator->modelClass === "app\models\DbTable" || ($generator->modelClass === "app\models\DbTableField")): ?>
use yii2tech\csvgrid\CsvGrid;<?php endif; ?>

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
				if ($newClass === "ContactGroup") $nullIsAnValidOption = 1;
				if ($newClass === "MappingQualifier") $nullIsAnValidOption = 1;

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
						'actions' => ['index','view'<?php if ($generator->modelClass === 'app\models\MapObject2Object'): ?>,'vallobjectsuniondepdrop'<?php endif; ?><?php if ($generator->modelClass === 'app\models\DbTable' || ($generator->modelClass === 'app\models\DbTableField')): ?>,'export_csv'<?php endif; ?><?php if ($generator->modelClass === 'app\models\Keyfigure'): ?>, 'export'<?php endif; ?>],
						'roles' => ['author', 'global-view', 'view' ."-" . Yii::$app->controller->id],
					],
					[
						'allow' => true,
						'actions' => ['create', 'update', 'createexternal'<?php if ($generator->modelClass === 'app\models\MapObject2Object'): ?>, 'changedirectionajax'<?php endif; ?>],
						'roles' => ['author', 'global-create', 'create' ."-" . Yii::$app->controller->id],
					],
					[
						'allow' => true,
						'actions' => ['delete'<?php if ($generator->modelClass === 'app\models\MapObject2Object'): ?>, 'deleteajax'<?php endif; ?>],
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
    
<?php if ($generator->modelClass === 'app\models\Client'): ?>
	private function createClientPermissions($named_client_id = NULL, $named_client_name = NULL)
	{	
		$result1 = false;
		$result2 = false;
		if ($named_client_id !== NULL && $named_client_name !== NULL)
		{
			$auth = Yii::$app->authManager;
			$newRoleOrPermName="client-".$named_client_id."-read";
			$checkPerm = $auth->getPermission($newRoleOrPermName);
			if (is_null($checkPerm)) {
				$newAuthObj = $auth->createPermission($newRoleOrPermName);
				$newAuthObj->ruleName = "isNotAGuest";
				$newAuthObj->description = "Read-Permission for client " . $named_client_name;
				$newAuthObj->data = ['id' => $named_client_id, 'dataaccessfilter' => 'client', 'right' => 'read'];
				$result1 = $auth->add($newAuthObj);
			}
			$auth = Yii::$app->authManager;
			$newRoleOrPermName="client-".$named_client_id."-write";
			$checkPerm = $auth->getPermission($newRoleOrPermName);
			if (is_null($checkPerm)) {
				$newAuthObj = $auth->createPermission($newRoleOrPermName);
				$newAuthObj->description = "Read-Permission for client " . $named_client_name;
				$newAuthObj->ruleName = "isNotAGuest";
				$newAuthObj->data = ['id' => $named_client_id, 'dataaccessfilter' => 'client', 'right' => 'write'];
				$result2 = $auth->add($newAuthObj);
			}
			return $result1 && $result2;
		}

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
				$result1 = $auth->add($newAuthObj);
			}
			$auth = Yii::$app->authManager;
			$newRoleOrPermName="client-".$client->id."-write";
			$checkPerm = $auth->getPermission($newRoleOrPermName);
			if (is_null($checkPerm)) {
				$newAuthObj = $auth->createPermission($newRoleOrPermName);
				$newAuthObj->description = "Read-Permission for client " . $client->name;
				$newAuthObj->ruleName = "isNotAGuest";
				$newAuthObj->data = ['id' => $client->id, 'dataaccessfilter' => 'client', 'right' => 'write'];
				$result2 = $auth->add($newAuthObj);
			}
		}
		return $result1 && $result2;
	}

	public function actionCreateclientpermissions()
  	{
  		$this->createClientPermissions();
  		echo "Client permissions created if needed.";
  		die;
	}  
	  
	protected function addPermissionToUser($client_id, $user_id)
	{
		$auth = Yii::$app->authManager;
		$newRoleOrPermName="client-".$client_id."-read";
		$perm=$auth->getPermission($newRoleOrPermName);
		$auth->assign($perm, $user_id);
		$newRoleOrPermName="client-".$client_id."-write";
		$perm=$auth->getPermission($newRoleOrPermName);
		$auth->assign($perm, $user_id);
	}
<?php endif; ?>

<?php if ($generator->modelClass === 'app\models\Project'): ?>
	private function createProjectPermissions($named_project_id = NULL, $named_project_name = NULL, $named_client_name = NULL)
	{
		$result1 = false;
		$result2 = false;
		if ($named_project_id !== NULL && $named_project_name !== NULL && $named_client_name !== NULL)
		{
			$auth = Yii::$app->authManager;
			$newRoleOrPermName="project-".$named_project_id."-read";
			$checkPerm = $auth->getPermission($newRoleOrPermName);
			if (is_null($checkPerm)) {
				$newAuthObj = $auth->createPermission($newRoleOrPermName);
				$newAuthObj->ruleName = "isNotAGuest";
				$newAuthObj->description = "Read-Permission for project " . $named_project_name . " (" . $named_client_name . ")";
				$newAuthObj->data = ['id' => $named_project_id, 'dataaccessfilter' => 'project', 'right' => 'read'];
				$result1 = $auth->add($newAuthObj);
			}
			$auth = Yii::$app->authManager;
			$newRoleOrPermName="project-".$named_project_id."-write";
			$checkPerm = $auth->getPermission($newRoleOrPermName);
			if (is_null($checkPerm)) {
				$newAuthObj = $auth->createPermission($newRoleOrPermName);
				$newAuthObj->description = "Read-Permission for project " . $named_project_name . " (" . $named_client_name . ")";
				$newAuthObj->ruleName = "isNotAGuest";
				$newAuthObj->data = ['id' => $named_project_id, 'dataaccessfilter' => 'project', 'right' => 'write'];
				$result2 = $auth->add($newAuthObj);
			}
			return $result1 && $result2;
		}

		$projectModel = new Project();
		$projects = $projectModel::find()->all();
		foreach($projects as $project)
		{
			$auth = Yii::$app->authManager;
			$newRoleOrPermName="project-".$project->id."-read";
			$checkPerm = $auth->getPermission($newRoleOrPermName);
			if (is_null($checkPerm)) {
				$newAuthObj = $auth->createPermission($newRoleOrPermName);
				$newAuthObj->ruleName = "isNotAGuest";
				$newAuthObj->description = "Read-Permission for project " . $project->name . " (" . $project->fkClient->name . ")";
				$newAuthObj->data = ['id' => $project->id, 'dataaccessfilter' => 'project', 'right' => 'read'];
				$result1 = $auth->add($newAuthObj);
			}
			$auth = Yii::$app->authManager;
			$newRoleOrPermName="project-".$project->id."-write";
			$checkPerm = $auth->getPermission($newRoleOrPermName);
			if (is_null($checkPerm)) {
				$newAuthObj = $auth->createPermission($newRoleOrPermName);
				$newAuthObj->description = "Read-Permission for project " . $project->name . " (" . $project->fkClient->name . ")";
				$newAuthObj->ruleName = "isNotAGuest";
				$newAuthObj->data = ['id' => $project->id, 'dataaccessfilter' => 'project', 'right' => 'write'];
				$result2 = $auth->add($newAuthObj);
			}
		}
		return $result1 && $result2;
	}

	public function actionCreateprojectpermissions()
	{
		$this->createProjectPermissions();
		echo "Project permissions created if needed.";
		die;
	}
	
	protected function addPermissionToUser($project_id, $user_id)
	{
		$auth = Yii::$app->authManager;
		$newRoleOrPermName="project-".$project_id."-read";
		$perm=$auth->getPermission($newRoleOrPermName);
		$auth->assign($perm, $user_id);
		$newRoleOrPermName="project-".$project_id."-write";
		$perm=$auth->getPermission($newRoleOrPermName);
		$auth->assign($perm, $user_id);
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
<?php if ($generator->modelClass === 'app\models\DbDatabase'): ?>
            'bulkloaderExecutionString' => $this->buildBulkloaderExecutionString($id)
			<?php endif; ?>
<?php if ($generator->modelClass === 'app\models\DbTable'): ?>
		'SQLSelectStatement' => $this->buildSQLSelectStatement($id),
		'sameTableList' => $this->sameTableList($id),
			<?php endif; ?>
        ]);
		<?php endif; ?>
}

    /**
     * Creates a new <?= $modelClass ?> model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($isfrommodal = false, $modalparent = "", $refreshfield = "")
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
  				'object_persistence_methodList' => $this->getObjectPersistenceMethodList(),		// autogeneriert ueber gii/CRUD
  				'datamanagement_processList' => $this->getDatamanagementProcessList(),		// autogeneriert ueber gii/CRUD
  				'modalparent'                   => $modalparent,
  				'refreshfield'                  => $refreshfield,				
  				'modelsBracketSearchPattern' => (empty($modelsBracketSearchPattern)) ? [new BracketSearchPattern] : $modelsBracketSearchPattern
      		]);
		<?php else: ?>
<?php if ($generator->modelClass === 'app\models\DbTable'): ?>$Utils = new \vendor\meta_grid\helper\Utils();
	$db_table_show_buttons_for_different_object_type_updates=$Utils->get_app_config("db_table_show_buttons_for_different_object_type_updates");

		if ($db_table_show_buttons_for_different_object_type_updates != 1) 
		{
			return $this->redirect(['dbtablefieldmultipleedit/create']);
		}
		<?php endif; ?>		
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
			$this->createClientPermissions($model->id, $model->name);
			$userId = Yii::$app->User->Id;
			$this->addPermissionToUser($model->id, $userId);	
<?php endif; ?>
<?php if ($generator->modelClass === 'app\models\Project'): ?>
			$this->createProjectPermissions($model->id, $model->name, $model->fkClient->name);
			$userId = Yii::$app->User->Id;
			$this->addPermissionToUser($model->id, $userId);	
<?php endif; ?> 	
			if ($isfrommodal) {echo json_encode(['status' => 'Success', 'message' => $model->id]);}
			else {return $this->redirect(['view', <?= $urlParams ?>]);}

        } else {
			$params = [
                'model' => $model,
                <?= $actionCreateUpdateFkList ?>
				'modalparent'                   => $modalparent,
				'refreshfield'                  => $refreshfield,				
            ];
			return Yii::$app->request->isAjax ? $this->renderAjax('create', $params) : $this->render('create', $params);
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
  				'object_persistence_methodList' => $this->getObjectPersistenceMethodList(),		// autogeneriert ueber gii/CRUD
  				'datamanagement_processList' => $this->getDatamanagementProcessList(),		// autogeneriert ueber gii/CRUD
  				'modalparent'                   => '',
  				'refreshfield'                  => '',				
  				'modelsBracketSearchPattern' => (empty($modelsBracketSearchPattern)) ? [new BracketSearchPattern] : $modelsBracketSearchPattern
        ]);
		<?php else: ?>
<?php if ($generator->modelClass === 'app\models\DbTable'): ?>$Utils = new \vendor\meta_grid\helper\Utils();
	$db_table_show_buttons_for_different_object_type_updates = $Utils->get_app_config("db_table_show_buttons_for_different_object_type_updates");
		if ($db_table_show_buttons_for_different_object_type_updates != 1) 
		{
			return $this->redirect(['dbtablefieldmultipleedit/update', 'id' => $id]);
		}	
		<?php endif; ?>		
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
				'modalparent'                   => '',
				'refreshfield'                  => '',
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
    	return "";
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

	private function replaceKeys($oldKey, $newKey, array $input){
		$return = array(); 
		foreach ($input as $key => $value) {
			if ($key===$oldKey)
				$key = $newKey;
	
			if (is_array($value))
				$value = $this->replaceKeys( $oldKey, $newKey, $value);
	
			$return[$key] = $value;
		}
		return $return; 
	}

    protected function CreateCSV($sessionPrepKey, $exportFilename, $export_fk_ids = 0)
    {
		$searchModel = new \app\models\ExportFileDbTableFieldResultSearch();
		$queryParams = $this->replaceKeys( "DbTableFieldSearch", "ExportFileDbTableFieldResultSearch", Yii::$app->request->queryParams);
		$queryParams["ExportFileDbTableFieldResultSearch"]["session"]=$sessionPrepKey;
		$dataProvider = $searchModel->search($queryParams);

		$columns = [
			['attribute' => 'id'],
			['attribute' => 'project_name'],
			['attribute' => 'client_name'],
			['attribute' => 'name'],
			['attribute' => 'description'],
			['attribute' => 'datatype'],
			['attribute' => 'is_PrimaryKey'],
			['attribute' => 'is_BusinessKey'],
			['attribute' => 'is_GDPR_relevant'],
			['attribute' => 'databaseInfoFromLocation'],
			['attribute' => 'schemaInfoFromLocation'],
			['attribute' => 'db_table_name'],
			['attribute' => 'deleted_status_name'],
			['attribute' => 'comments'],
			['attribute' => 'mappings'],
		];
		
		if ($export_fk_ids === "1")
		{
			$columns = array_merge($columns, 				[
				['attribute' => 'uuid'],
				['attribute' => 'fk_object_type_id'],
				['attribute' => 'fk_client_id'],
				['attribute' => 'fk_project_id'],
				['attribute' => 'fk_db_table_id'],
				['attribute' => 'fk_deleted_status_id'],
				['attribute' => 'bulk_load_checksum'],
			]);
		}

		$exporter = new CsvGrid([
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
		$exporter->export()->saveAs($exportFilename);
	}

	protected function initDownload($exportFilePath) 
	{
		$file = $exportFilePath;
		if (file_exists($file)) {
			Yii::$app->response->sendFile($file);
		   } 
	}
	
	protected function prepareExportData($sessionPrepKey)
	{
		Yii::debug($sessionPrepKey, '$sessionPrepKey');
		$permProjectsCanSee = Yii::$app->User->identity->permProjectsCanSee;
		foreach($permProjectsCanSee as $key=>$value)
		{
			$model = new \app\models\base\ExportFileDbTableFieldParams();
			$model->session = $sessionPrepKey;
			$model->allowed_fk_project_id = $value;
			$model->save();
			unset($model);
		}

		$permClientsCanSee = Yii::$app->User->identity->permClientsCanSee;
		foreach($permClientsCanSee as $key=>$value)
		{
			$model = new \app\models\base\ExportFileDbTableFieldParams();
			$model->session = $sessionPrepKey;
			$model->allowed_fk_client_id = $value;
			$model->save();
			unset($model);
		}
		
		$model = new \app\models\base\ExportFileDbTableFieldQueue();
		$model->session = $sessionPrepKey;
		$model->save(); // fires DB-TRIGGER
		unset($model);
	}

	protected function cleanupResultTable($sessionPrepKey)
	{
		\app\models\base\ExportFileDbTableFieldResult::deleteAll(['session' => $sessionPrepKey]);
	}

	/**
	 * Checks if a folder exist and return canonicalized absolute pathname (sort version)
	 * @param string $folder the path being checked.
	 * @return mixed returns TRUE on success otherwise FALSE
	 */
	private function folder_exist($folder)
	{
		// Get canonicalized absolute pathname
		$path = realpath($folder);

		// If it exist, check if it's a directory
		return ($path !== false AND is_dir($path)) ? true : false;
	}

	protected function createOutputDir($path)
	{
		if (! $this->folder_exist($path))
		{
			\yii\helpers\FileHelper::createDirectory($path, $mode = 0775, $recursive = true);
		}
		return $this->folder_exist($path);
	}
	
	protected function cleanupResultFile($exportFilePath)
	{
		unlink($exportFilePath);
	}

	public function actionExport_csv($export_fk_ids = "0", $no_cleanup = "0")
	{
		$path = Yii::getAlias('@app') . "/exportfiles";
		$dt = date("Y-m-d_H-m-s");
		$dirExists=$this->createOutputDir($path);
		if (! $dirExists)
		{
			throw new \yii\base\UserException( Yii::t("app","Output directory couldn't be created!") );
		}
		$sessionPrepKey = Yii::$app->controller->id . "|" . $dt . "|" . Yii::$app->session->id . "|" . Yii::$app->user->id;
		$this->prepareExportData($sessionPrepKey);
		$exportFilePath = $path . '/'. Yii::$app->controller->id . '_export_' . date("Y-m-d_H-m-s") . '.csv';
		$this->CreateCSV($sessionPrepKey, $exportFilePath, $export_fk_ids);
		$this->initDownload($exportFilePath);
		if ($no_cleanup !== "1")
		{
			$this->cleanupResultTable($sessionPrepKey);
			$this->cleanupResultFile($exportFilePath);
		}
		return;
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
				$filter_on_client_or_project = $parents[1]; // values can be a value of fk_project_id (with prefix "fk_project_id;") or fk_client_id (with prefix "fk_client_id;") or -1 (if -1 then show all)
				$out = $this->getVallobjectsunionListDepDrop($objType_id, $filter_on_client_or_project);
				$selected="";
				echo Json::encode(['output'=>$out, 'selected'=>$selected]);
				return;
			}
		}
		echo Json::encode(['output'=>'', 'selected'=>'']);
	}
	
	private function getVallobjectsunionListDepDrop($objType_id, $filter_on_client_or_project)
	{
		// Create list of objects for DepDrop
		$model1 = new \app\models\VAllObjectsUnion();
		if ($filter_on_client_or_project != "-1")
		{
			// The format can be of: "fk_project_id;<id or fk_project_id>" or "fk_client_id;<id or fk_client_id>"
			$client_Or_Project = explode(";", $filter_on_client_or_project)[0];
			if ($client_Or_Project == "fk_project_id")
			{
				$filter_project_id_param = explode(";", $filter_on_client_or_project)[1];
				$filter_client_id = \app\models\Project::find()->select("fk_client_id")->where(["id" => $filter_project_id_param]);
				$filter_project_id = \app\models\Project::find()->select("id")->where(["id" => $filter_project_id_param]);
			}
			else
			{
				$filter_client_id_param = explode(";", $filter_on_client_or_project)[1];
				$filter_client_id = \app\models\Project::find()->select("fk_client_id")->where(["fk_client_id" => $filter_client_id_param]);
				$filter_project_id = \app\models\Project::find()->select("id")->where(["fk_client_id" => $filter_client_id_param]);
			}

			$all_objects = $model1::find()
			   ->where(['in', "fk_project_id", $filter_project_id])
			   ->orWhere(['in', "fk_client_id", $filter_client_id])
			   ->all();
		}
		else
		{
			$all_objects = $model1::find()->all();
		}
		$object_typeList = [];
		foreach($all_objects as $object_item)
		{
			if ($objType_id=="*")		// show all object types
			{
				// if all shall be shown, then as a grouped dropdown list
				if ($filter_on_client_or_project != "-1")
				{
					$object_typeList[$object_item->object_type_name][$object_item->listkey] = ['id' => $object_item->listkey, 'name' => $object_item->listvalue_1 ];
				}
				else
				{
					$object_typeList[$object_item->object_type_name][$object_item->listkey] = ['id' => $object_item->listkey, 'name' => $object_item->listvalue_1_with_client_or_project ];
				}
			}
			else
			{
				if ($object_item->fk_object_type_id==$objType_id)
				{
					if ($filter_on_client_or_project != "-1")
					{							
						array_push($object_typeList, ['id' => $object_item->listkey, 'name' => $object_item->listvalue_1 ]);
					}
					else
					{							
						array_push($object_typeList, ['id' => $object_item->listkey, 'name' => $object_item->listvalue_1_with_client_or_project ]);
					}
				}
			}
		}
		return $object_typeList;
	}
	
	public function actionCreateexternal($ref_fk_object_id, $ref_fk_object_type_id) {
	
		$Utils = new \vendor\meta_grid\helper\Utils();
		$app_config_mapper_createext_time_limit = $Utils->get_app_config("mapper_createext_time_limit");
		set_time_limit($app_config_mapper_createext_time_limit);
		$app_config_mapper_createext_memory_limit = $Utils->get_app_config("mapper_createext_memory_limit");
		ini_set('memory_limit', $app_config_mapper_createext_memory_limit."M");

		// Controller fuer die View, wenn ein Mapping ueber ein Objekt aufgerufen wird.		
		$objectTypeModel = new \app\models\ObjectType ();
		$objectTypes = $objectTypeModel::find ()->all ();
		$objectTypesList = array ();
		$objectTypesList [null] = Yii::t('app', "Select...");
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
			if (isset($_POST["VAllObjectsUnion"]["listkey"]))
			{
				$listkey = $_POST["VAllObjectsUnion"]["listkey"];

				if (! isset($listkey[1]))
				{
					$model->addError('ref_fk_object_type_id_2', '$listkey[1] may not be NULL!!'); // this message will not be seen. Prepared for future use!
				}
				else
				{
					$model->ref_fk_object_id_2 = explode(";", $listkey)[0];
					$model->ref_fk_object_type_id_2 = explode(";", $listkey)[1];
				}			
			}
			else
			{
				$model->addError('ref_fk_object_type_id_1', '$listkey not set!'); // this message will not be seen. Prepared for future use!
			}
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
		$SrcFilterValueClientOrProjekt = $SrcObjectInfo->fk_project_id === null ? ("fk_client_id;" . $SrcObjectInfo->fk_client_id) : ("fk_project_id;" . $SrcObjectInfo->fk_project_id);

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
					'TitleSrcInformation' => $TitleSrcInformation,
					'SrcFilterValueClientOrProjekt' => $SrcFilterValueClientOrProjekt
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
	
	public function actionChangedirectionajax()
	{
		$data = Yii::$app->request->post('id');
		if (isset($data)) {
			$returnValue = $this->actionChangedirection($data);
			$chk = $returnValue;
		} else {
			$chk = -500;
		}
		return \yii\helpers\Json::encode($chk);
	}

	private function actionChangedirection($id) {
		
		try{
			$model = MapObject2Object::findOne($id);
			if ($model == null) {
				return -100;
			}
			$ref_fk_object_id_1 = $model->ref_fk_object_id_1;
			$ref_fk_object_type_id_1 = $model->ref_fk_object_type_id_1;
			$ref_fk_object_id_2 = $model->ref_fk_object_id_2;
			$ref_fk_object_type_id_2 = $model->ref_fk_object_type_id_2;
			
			$model->ref_fk_object_id_1 = $ref_fk_object_id_2;
			$model->ref_fk_object_type_id_1 = $ref_fk_object_type_id_2;
			$model->ref_fk_object_id_2 = $ref_fk_object_id_1;
			$model->ref_fk_object_type_id_2 = $ref_fk_object_type_id_1;
			
			$model->save();
			return 100;
		}
		catch (\Exception $e) 
		{
			Yii::trace($e, 'MapperController -> actionChangedirection');
			return -999;
		}
	}

	public function actionDeleteajax()
	{
		$data = Yii::$app->request->post('id');
		if (isset($data)) {
			$returnValue = $this->delete($data);
			$chk = $returnValue;
		} else {
			$chk = -500;
		}
		return \yii\helpers\Json::encode($chk);
	}

	private function delete($id)
    {
		try {
			$model = $this->findModel($id);
			$model->delete();
			return 100;
		} catch (\Exception $e) {
			$model->addError(null, $e->getMessage());
			$errMsg = $e->getMessage();
			
			$errMsgAdd = "";
			try{$errMsgAdd = '"'. $model->name . '"';} catch(\Exception $e){}

			if (strpos($errMsg, "Integrity constraint violation")) $errMsg = Yii::t('yii',"The object {errMsgAdd} is still referenced by other objects.", ['errMsgAdd' => $errMsgAdd]);
			Yii::$app->session->setFlash('deleteError', Yii::t('yii','Object can\'t be deleted: ') . $errMsg);
			return -999;
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
<?php if ($generator->modelClass === "app\models\DbDatabase"): ?>

	private function bulkloadertemplate()
	{
		// Template text generated with 'kitchen.sh -file:"run_import.kjb" -listparam | grep "Parameter:"'
		$template_string_linux='
Parameter: changeset_path=, default=- : Path to scan for liquibase changesets
Parameter: filter_routine_names= : Filter routine names based on parameter value (like operation without wildcards). Attention: Case sensitive
Parameter: get_description_from_routine_definition=, default=Y : Extract the comment from the SQL Definition. Y=Yes | N=No
Parameter: get_description_from_routine_definition_SplitElemenent_1=, default=1 : Element to use for result of get_description_from_routine_definition_SplitOnString_1
Parameter: get_description_from_routine_definition_SplitElemenent_2=, default=0 : Element to use for result of get_description_from_routine_definition_SplitOnString_2
Parameter: get_description_from_routine_definition_SplitOnString_1=, default=Description:  : First substring to split the routine definition (e.g.  "Description: ") without quotations
Parameter: get_description_from_routine_definition_SplitOnString_2=, default=\n : Second substring to split the routine definition (e.g.  "\n") without quotations
Parameter: handle_descriptions=, default=3 : 1=Use only database object description | 2=Use only from routine description | 3=Use both
Parameter: location_lookup_database=, default=db_name : Database for liquibase changeset lookup db_table
Parameter: map_db_table_to_db_database_id=, default=11 : DB_Database ID which will be used to map (new) database tables found
Parameter: map_transfer_process_to_db_database_id=, default=11 : DB_Database ID which will be used to map (new) database routines found
Parameter: metagrid_jdbc_db_pwd= : JDBC Database Password for meta#grid
Parameter: metagrid_jdbc_db_user= : JDBC Database User for meta#grid
Parameter: metagrid_jdbc_driver_class=, default=org.sqlite.JDBC : JDBC Java Driver Class to use to write in meta#grid database
Parameter: metagrid_jdbc_url=, default=jdbc:sqlite:/home/patrick/Development_WorkingCopies/dwh_meta/dwh_meta_v2/dwh_meta.sqlite : JDBC URL of the source system to write in meta#grid database (including IP/DNS, Port, Databasename)
Parameter: project_id=, default=7 : Project ID which information belongs to
Parameter: source_db_schema_exclude=, default=sys,INFORMATION_SCHEMA,Backup,zz_Attic,information_schema,pg_catalog,pg_toast : Ignore special schemes in databse e.g. sys
Parameter: source_jdbc_db_pwd=, default=<PASSWORD> : JDBC Database Password
Parameter: source_jdbc_db_user=, default=<USER> : JDBC Database User for source
Parameter: source_jdbc_driver_class=, default=net.sourceforge.jtds.jdbc.Driver : JDBC Java Driver Class to use to read from source
Parameter: source_jdbc_url=, default=jdbc:jtds:sqlserver://127.0.0.1:1433/Chinook : JDBC URL of the source system to read from (including IP/DNS, Port, Databasename)
Parameter: filter_view_names= : Filter view names based on parameter value (like operation without wildcards). Attention: Case sensitive and only affects reading view descriptions
Parameter: overwrite_description_if_existing_differs=, default=Y : If there description is already filled and differs, shall it be overwritten. Y=Overwrite | N=Do not overwrite. Attention: only affects reading view descriptions
		';

		$arr = array();

		$line = explode("\n",str_replace("\t","", $template_string_linux))[3];
		$parameter = explode("=", explode(": ", $line)[1])[0];
		$default_value = "";
		if (strpos($line, "default") > 0)
		{	
			$default_value = explode("default=", explode(": ", $line)[1])[1];
		}

		foreach(explode("\n",str_replace("\t","", $template_string_linux)) as $key=>$value)
		{
			if (strpos($value, "=")>0 && strpos($value, ": ")>0)
			{
				$parameter = explode("=", explode(": ", $value)[1])[0];
				$default_value = "";
				if (strpos($value, "default") > 0)
				{	
					$default_value = rtrim(explode("default=", explode(": ", $value)[1])[1]);
				}
				$arr[$parameter]=$default_value;
			}
		}
		return $arr;
	}

	private function buildBulkloaderExecutionString($id)
	{

		$Utils = new \vendor\meta_grid\helper\Utils();
		$bulk_loader_executable = $Utils->get_app_config("bulk_loader_executable");
		$bulk_loader_metagrid_jdbc_url = $Utils->get_app_config("bulk_loader_metagrid_jdbc_url");
		$bulk_loader_java_home = $Utils->get_app_config("bulk_loader_java_home");

		$model = $this->findModel($id);
		$bulkLoaderParameterArr = $this->bulkloadertemplate();
		$bulkLoaderParameterArr["project_id"] = $model->fk_project_id;
		$bulkLoaderParameterArr["map_db_table_to_db_database_id"] = $model->id;
		$bulkLoaderParameterArr["map_transfer_process_to_db_database_id"] = $model->id;
		
		if (Yii::$app->db->getDriverName() == "sqlite")
		{
			$sqlitePath = str_replace("sqlite:", "", Yii::$app->db->dsn);
			$sqliteRealPath = realpath($sqlitePath);
			$bulkLoaderParameterArr["metagrid_jdbc_url"] = "jdbc:sqlite:" . $sqliteRealPath;
		}
		
		if ($bulk_loader_metagrid_jdbc_url !== NULL && $bulk_loader_metagrid_jdbc_url !== "")
		{
			$bulkLoaderParameterArr["metagrid_jdbc_url"] = $bulk_loader_metagrid_jdbc_url;
		}
		
		$bulkLoaderParameterArr["location_lookup_database"] = $model->name;
		$bulkLoaderParameterArr["source_jdbc_driver_class"] = "[--> JDBC DRIVER CLASS <--]";
		$bulkLoaderParameterArr["source_jdbc_url"] = "[--> JDBC URL <--]";

		if (stripos($model->fkTool->vendor, "Microsoft") !== false)
		{
			if (stripos($model->fkTool->tool_name, "SQL") !== false)
			{
				$bulkLoaderParameterArr["source_jdbc_driver_class"] = "net.sourceforge.jtds.jdbc.Driver";
				$bulkLoaderParameterArr["source_jdbc_url"] = "jdbc:jtds:sqlserver://127.0.0.1:1433/$model->name";
			}
		}
		if (stripos($model->fkTool->vendor, "Postgres") !== false)
		{
			if (stripos($model->fkTool->tool_name, "SQL") !== false)
			{
				$bulkLoaderParameterArr["source_jdbc_driver_class"] = "org.postgresql.Driver";
				$bulkLoaderParameterArr["source_jdbc_url"] = "jdbc:postgresql://127.0.0.1:5432/$model->name";
			}
		}		
		if (stripos($model->fkTool->vendor, "ORACLE") !== false)
		{
			if (stripos($model->fkTool->tool_name, "MySQL") !== false)
			{
				$bulkLoaderParameterArr["source_jdbc_driver_class"] = "com.mysql.jdbc.Driver";
				$bulkLoaderParameterArr["source_jdbc_url"] = "jdbc:mysql://127.0.0.1:3306/$model->name";
			}
		}		
		if (stripos($model->fkTool->vendor, "SQLite") !== false)
		{
			if (stripos($model->fkTool->tool_name, "SQLite") !== false)
			{
				$bulkLoaderParameterArr["source_jdbc_driver_class"] = "org.sqlite.JDBC";
				$bulkLoaderParameterArr["source_jdbc_url"] = "jdbc:sqlite:/myPath/$model->name";
			}
		}
		if (stripos($model->fkTool->vendor, "Intersystems") !== false)
		{
			if (stripos($model->fkTool->tool_name, "Intersystems Cach") !== false)
			{
				$bulkLoaderParameterArr["source_jdbc_driver_class"] = "com.intersys.jdbc.CacheDriver";
				$bulkLoaderParameterArr["source_jdbc_url"] = "jdbc:Cache://127.0.0.1:1972/$model->name";
			}
		}

		$exec = ($bulk_loader_executable !== NULL && $bulk_loader_executable !== "") ? $bulk_loader_executable : "kitchen.sh";
		$returnValue = "";
		$returnValue .= "## Linux". "\n";
		$returnValue .= "\n";
		$returnValue .= ($bulk_loader_java_home !== null && $bulk_loader_java_home !== "") ? "export JAVA_HOME=\"$bulk_loader_java_home\"" : "# export JAVA_HOME=\"&lt;You can set this parameter in the table app_config with the key='bulk_loader_java_home'&gt;\"";
		$returnValue .= "\n";
		$returnValue .= "\n";
		$returnValue .= $exec.' -file:"run_import.kjb" \\' . "\n";
		foreach($bulkLoaderParameterArr as $key=>$value)
		{
			$returnValue .= "-param:" . $key . "=" . '"' . $value . '"' . " \\" . "\n";
		}
		
		$returnValue .= "-----------------------------------------------------------------------------------". "\n";

		$exec = ($bulk_loader_executable !== NULL && $bulk_loader_executable !== "") ? $bulk_loader_executable : "kitchen.bat";
		$returnValue .= "\n";
		$returnValue .= "REM Windows". "\n";
		$returnValue .= "@echo off". "\n";
		$returnValue .= "\n";
		$returnValue .= ($bulk_loader_java_home !== NULL && $bulk_loader_java_home !== "") ? "SET JAVA_HOME=$bulk_loader_java_home" : "REM SET JAVA_HOME=&lt;You can set this parameter in the table app_config with the key='bulk_loader_java_home'&gt;";
		$returnValue .= "\n";
		$returnValue .= "\n";
		foreach($bulkLoaderParameterArr as $key=>$value)
		{
			$returnValue .= 'SET ' . $key . "=" . $value . "\n";
		}
		$returnValue .= "\n";
		$returnValue .= "pushd %cd%" . "\n";
		$returnValue .= "SET startcd=%cd%" . "\n";
		$returnValue .= "SET kettle_bin_path=" . dirname($exec) . "\n";
		$returnValue .= 'cd "%kettle_bin_path%' . "\n";
		$returnValue .= 'SET kitchen_bin=' . basename($exec) . "\n";
		$returnValue .= 'SET job_path="run_import.kjb"' . "\n";
		$returnValue .= 'start %kitchen_bin% /file:%job_path% ';
		foreach($bulkLoaderParameterArr as $key=>$value)
		{
			$returnValue .= '"/param:' . $key . "=%" . $key . '%" ' ;
		}
		$returnValue .= "\n";
		$returnValue .= 'cd %startcd%' . "\n";
		$returnValue .= 'popd' . "\n";

		return $returnValue;
	}
<?php endif; ?>
<?php if ($generator->modelClass === "app\models\DbTable"): ?>

	private function buildSQLSelectStatement($id)
	{
		$modelDbTableField = \app\models\DbTableField::find()->select(["name", "datatype", "is_PrimaryKey"])->where(["fk_db_table_id" => $id])->all();
		$model = $this->findModel($id);
		$returnValue = "";
		
		$returnValue .= "SELECT" . "\n";
		$i=0;
		foreach($modelDbTableField as $key=>$value)
		{
			$i++;
			$returnValue .= $i > 1 ? "   ," : "    ";
			$returnValue .= "\"". $value->name . "\"" . "\n";
		}
		$returnValue .= "FROM " . $model->location;
		$returnValue .= ";" . "\n";
		$returnValue .= "\n";
		
		$returnValue .= "CREATE TABLE" ." " . $model->location . "\n";
		$returnValue .= "(" . "\n";
		
		$j=0;
		foreach($modelDbTableField as $key=>$value)
		{
			$j++;
			$returnValue .= $j > 1 ? "   ," : "    ";
			$returnValue .= "\"". $value->name . "\"" ;
			$returnValue .= " " . $value->datatype;
			$returnValue .= $value->is_PrimaryKey === true ? " NOT NULL PRIMARY KEY" : "";
			$returnValue .= "\n";
		}
		$returnValue .= ")";
		$returnValue .= ";" . "\n";
		
		return $i === 0 ? "" : $returnValue;
	}

	private function replaceKeys($oldKey, $newKey, array $input){
		$return = array(); 
		foreach ($input as $key => $value) {
			if ($key===$oldKey)
				$key = $newKey;
	
			if (is_array($value))
				$value = $this->replaceKeys( $oldKey, $newKey, $value);
	
			$return[$key] = $value;
		}
		return $return; 
	}

    protected function CreateCSV($sessionPrepKey, $exportFilename, $export_fk_ids = 0)
    {
		$searchModel = new \app\models\ExportFileDbTableResultSearch();
		$queryParams = $this->replaceKeys( "DbTableSearch", "ExportFileDbTableResultSearch", Yii::$app->request->queryParams);
		$queryParams["ExportFileDbTableResultSearch"]["session"]=$sessionPrepKey;
		$dataProvider = $searchModel->search($queryParams);

		$columns = [
			['attribute' => 'id'],
			['attribute' => 'client_name'],
			['attribute' => 'project_name'],
			['attribute' => 'name'],
			['attribute' => 'description'],
			['attribute' => 'location'],
			['attribute' => 'db_table_context_name'],
			['attribute' => 'db_table_type_name'],
			['attribute' => 'deleted_status_name'],
			['attribute' => 'databaseInfoFromLocation'],
			['attribute' => 'schemaInfoFromLocation'],
			['attribute' => 'mappings'],
			['attribute' => 'comments'],
		];
		
		if ($export_fk_ids === "1")
		{
			$columns = array_merge($columns, 				[
				['attribute' => 'uuid'],
				['attribute' => 'fk_object_type_id'],
				['attribute' => 'fk_client_id'],
				['attribute' => 'fk_project_id'],
				['attribute' => 'fk_db_table_type_id'],
				['attribute' => 'fk_db_table_context_id'],
				['attribute' => 'fk_deleted_status_id'],
			]);
		}

		$exporter = new CsvGrid([
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
		$exporter->export()->saveAs($exportFilename);
	}

	protected function initDownload($exportFilePath) 
	{
		$file = $exportFilePath;
		if (file_exists($file)) {
			Yii::$app->response->sendFile($file);
		   } 
		}
	
	protected function prepareExportData($sessionPrepKey)
	{
		Yii::trace($sessionPrepKey, '$sessionPrepKey');
		$permProjectsCanSee = Yii::$app->User->identity->permProjectsCanSee;
		foreach($permProjectsCanSee as $key=>$value)
		{
			$model = new \app\models\base\ExportFileDbTableParams();
			$model->session = $sessionPrepKey;
			$model->allowed_fk_project_id = $value;
			$model->save();
			unset($model);
		}

		$permClientsCanSee = Yii::$app->User->identity->permClientsCanSee;
		foreach($permClientsCanSee as $key=>$value)
		{
			$model = new \app\models\base\ExportFileDbTableParams();
			$model->session = $sessionPrepKey;
			$model->allowed_fk_client_id = $value;
			$model->save();
			unset($model);
		}
		
		$model = new \app\models\base\ExportFileDbTableQueue();
		$model->session = $sessionPrepKey;
		$model->save(); // fires DB-TRIGGER
		unset($model);
	}

	protected function cleanupResultTable($sessionPrepKey)
	{
		\app\models\base\ExportFileDbTableResult::deleteAll(['session' => $sessionPrepKey]);
	}

	/**
	 * Checks if a folder exist and return canonicalized absolute pathname (sort version)
	 * @param string $folder the path being checked.
	 * @return mixed returns TRUE on success otherwise FALSE
	 */
	private function folder_exist($folder)
	{
		// Get canonicalized absolute pathname
		$path = realpath($folder);

		// If it exist, check if it's a directory
		return ($path !== false AND is_dir($path)) ? true : false;
	}

	protected function createOutputDir($path)
	{
		if (! $this->folder_exist($path))
		{
			\yii\helpers\FileHelper::createDirectory($path, $mode = 0775, $recursive = true);
		}
		return $this->folder_exist($path);
	}
	
	protected function cleanupResultFile($exportFilePath)
	{
		unlink($exportFilePath);
	}

	public function actionExport_csv($export_fk_ids = "0", $no_cleanup = "0")
	{
		$path = Yii::getAlias('@app') . "/exportfiles";
		$dt = date("Y-m-d_H-m-s");
		$dirExists=$this->createOutputDir($path);
		if (! $dirExists)
		{
			throw new \yii\base\UserException( Yii::t("app","Output directory couldn't be created!") );
		}
		$sessionPrepKey = Yii::$app->controller->id . "|" . $dt . "|" . Yii::$app->session->id . "|" . Yii::$app->user->id;
		$this->prepareExportData($sessionPrepKey);
		$exportFilePath = $path . '/'. Yii::$app->controller->id . '_export_' . date("Y-m-d_H-m-s") . '.csv';
		$this->CreateCSV($sessionPrepKey, $exportFilePath, $export_fk_ids);
		$this->initDownload($exportFilePath);
		if ($no_cleanup !== "1")
		{
			$this->cleanupResultTable($sessionPrepKey);
			$this->cleanupResultFile($exportFilePath);
		}
		return;
	}

	public function sameTableList($id)
	{
		$permProjectsCanSee = Yii::$app->User->identity->permProjectsCanSee;
		$model = \app\models\base\VDbTableLocation4SameTableLookup::findOne(['id' => $id]);
	    if (($model !== null)) 
		{
			$allRelatedByName = \app\models\base\VDbTableLocation4SameTableLookup::find()
				->where(['db_table_location_normalized' => $model->db_table_location_normalized])
				->andFilterWhere(['<>','id', $id]) // exclude the actual id
				->andFilterWhere(['in','fk_project_id', $permProjectsCanSee]) // only for the permission given to the user (@TODO, model class contains checks, too. But not working... )
				->all();
			if (($allRelatedByName !== null))
			{
				return $allRelatedByName;
			}
		}
		return null;
	}

<?php endif; ?>
}