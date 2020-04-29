<?php

namespace app\controllers;

use Yii;
use app\models\DbTable;
use app\models\DbTableField;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Project;
use app\models\DbTableContext;
use app\models\DbTableType;
use Da\User\Filter\AccessRuleFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use app\models\Model;
use PhpParser\Node\Stmt\Continue_;
use Symfony\Component\VarDumper\VarDumper;
use yii\helpers\Json;
use app\models\DeletedStatus;
use Exception;

class DbtablefieldmultipleeditController extends Controller
{


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

        private function getDbTableContextList($filter_on_fk_project_id = -1, $init = true)
        {
                if ($filter_on_fk_project_id == -1) 
                {
                    $filter_on_fk_project_id = array_key_first($this->getProjectList());
                }

                // autogeneriert ueber gii/CRUD
                $db_table_contextModel = new DbTableContext();
                $db_table_contexts = $db_table_contextModel::find()->where(['fk_project_id' => $filter_on_fk_project_id])->all();
                $db_table_contextList = array();
                $db_table_contextList[null] = null; // allow a NULL selection
                foreach($db_table_contexts as $db_table_context)
                {
                        $db_table_contextList[$db_table_context->id] = $db_table_context->name;
                }
                if ($init) {
                    return $db_table_contextList;
                }
                else{
                    return $db_table_contexts;
                }               
        }

        private function getDbTableTypeList()
        {
                // autogeneriert ueber gii/CRUD
                $db_table_typeModel = new DbTableType();
                $db_table_types = $db_table_typeModel::find()->all();
                $db_table_typeList = array();
                $db_table_typeList[null] = Yii::t('app', 'Select...'); // allow a NULL selection
                foreach($db_table_types as $db_table_type)
                {
                        $db_table_typeList[$db_table_type->id] = $db_table_type->name;
                }
                return $db_table_typeList;
        }

        private function getDeletedStatusList()
        {
            // autogeneriert ueber gii/CRUD
            $deleted_statusModel = new DeletedStatus();
            $deleted_statuss = $deleted_statusModel::find()->all();
            $deleted_statusList = array();
            $deleted_statusList[null] = Yii::t('app', 'Select...'); // allow a NULL selection
            foreach($deleted_statuss as $deleted_status)
            {
                $deleted_statusList[$deleted_status->id] = $deleted_status->name;
            }
            return $deleted_statusList;
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
                            'allow' => false,
                            'actions' => ['index','view'],
                            'roles' => ['author', 'global-view', 'view' ."-" . Yii::$app->controller->id],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['create', 'update'],
                            // 'roles' => ['author', 'global-create', 'create' ."-" . Yii::$app->controller->id],
                            // 'roles' => ['author', 'global-create', 'create' ."-" . 'dbtable'],
                            'roles' => ['author', 'global-create', 'create' ."-" . 'dbtable'],
                            'matchCallback' => function ($rule, $action) {
                                if (Yii::$app->user->identity->isAdmin || (Yii::$app->User->can('create-dbtable') && Yii::$app->User->can('create-dbtablefield'))) {
                                    return true;
                                }
                                return false;
                            }
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
   
        private function registerControllerRole()
        {
            return null;
        }

        public function actionDbtablecontextlistdepdrop() {
	
            // Methode fuer Ajaxcall aus der View um die Liste in Abh?ngigkeit der Dropdownliste "Objekttyp" zu liefern
            if (isset($_POST['depdrop_parents'])) 
            {
                $parents = $_POST['depdrop_parents'];
                if ($parents != null)
                {
                    $objType_id = $parents[0];
                    $out = $this->getDbTableContextList($objType_id, false);
                    $selected="";
                    echo Json::encode(['output'=>$out, 'selected'=>$selected]);
                    return;
                }
            }
            echo Json::encode(['output'=>'', 'selected'=>'']);
        }


    /**
     * Displays a single Customer model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        // $dbTable = $this->findModel($id);
        // $dbTableFields = $model->dbtablefields;

        // return $this->render('view', [
        //     'model' => $model,
        //     'dbTableFields' => $dbTableFields,
        // ]);
    }

    /**
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if ($this->checkVariableLimitPerSubmitReached(Yii::$app->request->post()))
        {
            throw new \yii\web\HttpException(500, Yii::t('app', 'To many elements for max_input_vars (actual limit: {max_input_vars}). Because of possibility for data loss was the action stopped! Please consider to increase the value.', ['max_input_vars' => ini_get('max_input_vars')]));
            return;
        }

        $modelDbTable = new DbTable;
        $modelsDbTableField = [new DbTableField];

        if ($modelDbTable->load(Yii::$app->request->post())) {

            $modelsDbTableField = Model::createMultiple(DbTableField::classname());
            Model::loadMultiple($modelsDbTableField, Yii::$app->request->post());

            // validate all models
            $valid = $modelDbTable->validate();
            $valid = Model::validateMultiple($modelsDbTableField) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();

                try {
                    if ($flag = $modelDbTable->save(false)) {
                        foreach ($modelsDbTableField as $modelDbTableField) {
                            $modelDbTableField->fk_db_table_id = $modelDbTable->id;
                            $modelDbTableField->fk_project_id = $modelDbTable->fk_project_id;
                            if (! ($flag = $modelDbTableField->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['/dbtable/view', 'id' => $modelDbTable->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('create', [
            'modelDbTable' => $modelDbTable,
            'modelsDbTableField' => (empty($modelsDbTableField)) ? [new DbTableField] : $modelsDbTableField

,
'projectList' => $this->getProjectList(),               // autogeneriert ueber gii/CRUD
'db_table_contextList' => $this->getDbTableContextList(),               // autogeneriert ueber gii/CRUD
'db_table_typeList' => $this->getDbTableTypeList(),             // autogeneriert ueber gii/CRUD
'deleted_statusList' => $this->getDeletedStatusList(),		// autogeneriert ueber gii/CRUD
        ]);
    }

    /**
     * Checks if a array has subarrays.
     * @param array $arr
     * @return boolean
     */
    private function has_array($arr) {
        $has_array = false;
        try {         // Needed a try-catch for {T60}(?)
            foreach ($arr as $item):
                if (is_array($item)):
                    $has_array = true;
                    break;
                endif;
             endforeach;
        }
        catch (Exception $e) {
        }
        return $has_array;
     }

     /**
      * Checks if a post via submit exceeds the limit of max_input_vars (default 1000)
      * Needs as parameter the postvariable with the submitted model data.
      * Returns false if everything is fine (tolerance limit not reached). Returns true if not.
      * @param array $postRequest
      * @return boolean
      */
     private function checkVariableLimitPerSubmitReached($postRequest)
     {
        /**
         * $countOfElements holds the count of all subarray items
         * E.g.
         * array:2 [▼
         *    "DbTable" => 1
         *    "DbTableField" => 199
         *  ]
         */
        $countOfElements = array();

        /**
         * $countOfAttributesOfEachElement holds the count of each invidual model array
         * E.g.
         * array:2 [▼
         *    "DbTable" => 6
         *    "DbTableField" => 5
         *  ]
         * DbTable has 6 properties which shall be saved
         * DbTableField has (each) 5 properties which shall be saved
         */
        $countOfAttributesOfEachElement = array();
        
        /**
         * $sumOfEachModel holds the sum, which the model arrays consumes in combination of $countOfElements and $countOfAttributesOfEachElement
         * E.g.
         * array:2 [▼
         *    "DbTable" => 6
         *    "DbTableField" => 995
         *  ]
         */
        $sumOfEachModel = array();
        foreach($postRequest as $key => $value)
        {
            if ($key=="_csrf") continue;
            
            /**
             * The keys are: DbTable and DbTableField
             * The key DbTable will be static at 1 dataset
             * The key DbTableField depends on the fields for the table e.g. 50 fields of a table
             */
            $countOfElements[$key] = count($postRequest[$key]);
            if (! $this->has_array($postRequest[$key])) $countOfElements[$key] = 1; // no subarrays

            if ($this->has_array($postRequest[$key]))
            {
                foreach($postRequest[$key] as $element_key => $element_value)
                {
                    // VarDumper::dump(count($postRequest[$key][$element_key]));
                    $countOfAttributesOfEachElement[$key] = count($postRequest[$key][$element_key]);
                    $sumOfEachModel[$key] = $countOfAttributesOfEachElement[$key] * $countOfElements[$key];
                    break;
                }
            }
            else {
                $countOfAttributesOfEachElement[$key] = count($postRequest[$key]);
                $sumOfEachModel[$key] = $countOfAttributesOfEachElement[$key] * $countOfElements[$key];
            }
        }

        $sumAllAttributes = 0;
        $sumAttributes = 0;
        foreach($sumOfEachModel as $key => $value)
        {
            $sumAllAttributes +=  $value;
        }
        foreach($countOfAttributesOfEachElement as $key => $value)
        {
            $sumAttributes +=  $value;
        }
        // VarDumper::dump($sumAllAttributes);
        // VarDumper::dump(intval(ini_get('max_input_vars')));
        // VarDumper::dump($sumAttributes);
        // VarDumper::dump($sumAllAttributes-$sumAttributes);

        if (intval(ini_get('max_input_vars')-$sumAttributes)<($sumAllAttributes))
        {
            return true; // not ok
        }
        return false; // ok
     }

     /**
      * Returns true if something to save because of a change
      * Returns false if nothing changed of relevance
      * @param array $postRequest
      * @return boolean
     */
     public function somethingChanged($modelWithPostedData)
     {
        $somethingChanged = false;

        if (count($modelWithPostedData->getDirtyAttributes()) == 0) return $somethingChanged; // if the framework says there is nothing changed, accept the choice
 
        if (empty($modelWithPostedData->getOldAttributes())) return true; // this is an INSERT
        
        foreach ($modelWithPostedData->attributes() as $key => $value)
        {         
            if ($modelWithPostedData->getAttributes()[$value] != $modelWithPostedData->getOldAttributes()[$value]) return true;
        }
        return $somethingChanged;
     }

    /**
     * Updates an existing Customer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if ($this->checkVariableLimitPerSubmitReached(Yii::$app->request->post()))
        {
            throw new \yii\web\HttpException(500, Yii::t('app', 'To many elements for max_input_vars (actual limit: {max_input_vars}). Because of possibility for data loss was the action stopped! Please consider to increase the value.', ['max_input_vars' => ini_get('max_input_vars')]));
            return;
        }

        $modelDbTable = $this->findModel($id);

		if (!in_array($modelDbTable->fkProject->id, Yii::$app->User->identity->permProjectsCanEdit)) {	
			throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You have no permission to edit this data.'));
			return;
        }
        
        $modelsDbTableField = $modelDbTable->dbTableFields;

        if ($modelDbTable->load(Yii::$app->request->post())) {

            // {...  Handle false dirtyAttribute Handling ({T56})
            $ignoreUpdateDbTable=0;
            if (! $this->somethingChanged($modelDbTable)) $ignoreUpdateDbTable=1;
            // ({T56}) ...}

            $oldIDs = ArrayHelper::map($modelsDbTableField, 'id', 'id');

            $modelsDbTableField = Model::createMultiple(DbTableField::classname(), $modelsDbTableField);
            Model::loadMultiple($modelsDbTableField, Yii::$app->request->post());

            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsDbTableField, 'id', 'id'))); // ***1***
            
            // {...  Handle false dirtyAttribute Handling ({T56})
            foreach($modelsDbTableField as $key => $datarow)
            {
                if (! $this->somethingChanged($datarow))
                {
                    // It is listed, because int not equal to string (see mentioned here: https://github.com/yiisoft/yii2/issues/5375 / https://github.com/yiisoft/yii2/issues/2790 ) 
                    // ignore the update, means killing the array element just here
                    unset($modelsDbTableField[$key]);
                }
            }
            // ({T56}) ...}

            // validate all models
            $valid = $modelDbTable->validate();
            $valid = Model::validateMultiple($modelsDbTableField) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    
                    // {...  Handle false dirtyAttribute Handling ({T56})
                    $flag = false;

                    if ($ignoreUpdateDbTable==1) $flag = true;
                    else $flag = $modelDbTable->save(false);
                    // ({T56}) ...}

                    if ($flag) {
                        if (!empty($deletedIDs)) {
                            DbTableField::deleteAll(['id' => $deletedIDs]);
                        }
                        foreach ($modelsDbTableField as $modelDbTableField) {
                            $modelDbTableField->fk_db_table_id = $modelDbTable->id;
                            $modelDbTableField->fk_project_id = $modelDbTable->fk_project_id;
                            if (! ($flag = $modelDbTableField->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['/dbtable/view', 'id' => $modelDbTable->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('update', [
            'modelDbTable' => $modelDbTable,
            'modelsDbTableField' => (empty($modelsDbTableField)) ? [new DbTableField] : $modelsDbTableField
,
'projectList' => $this->getProjectList(),               // autogeneriert ueber gii/CRUD
'db_table_contextList' => $this->getDbTableContextList($modelDbTable->fk_project_id),               // autogeneriert ueber gii/CRUD
'db_table_typeList' => $this->getDbTableTypeList(),             // autogeneriert ueber gii/CRUD
'deleted_statusList' => $this->getDeletedStatusList(),		// autogeneriert ueber gii/CRUD
        ]);
    }

    /**
     * Deletes an existing Customer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
    }


    /**
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Customer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DbTable::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }	



}

?>

<?php
    // https://www.php.net/manual/de/function.array-key-first.php
    if (!function_exists('array_key_first')) {
        function array_key_first(array $arr) {
            foreach($arr as $key => $unused) {
                return $key;
            }
            return NULL;
        }
    }
?>
