<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

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
			
// 		echo "/*";
		//VarDumper::dump($generator->getColumnNames());
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
	        	
	        	echo "		$$fk_model_variable"."Model = new $newClass();\n";
				echo "		$$fk_model_variable"."s = $$fk_model_variable"."Model::find()->all();\n";
				echo "		$$fk_model_variable"."List = array();\n";
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
// 	    echo "*/";
// 	    echo "*/";
	?>
	
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
        return $this->render('view', [
            'model' => $this->findModel(<?= $actionParams ?>),
        ]);
    }

    /**
     * Creates a new <?= $modelClass ?> model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new <?= $modelClass ?>();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', <?= $urlParams ?>]);
        } else {
            return $this->render('create', [
                'model' => $model,
                <?= $actionCreateUpdateFkList ?>
            ]);
        }
    }

    /**
     * Updates an existing <?= $modelClass ?> model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionUpdate(<?= $actionParams ?>)
    {
        $model = $this->findModel(<?= $actionParams ?>);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', <?= $urlParams ?>]);
        } else {
            return $this->render('update', [
                'model' => $model,
                <?= $actionCreateUpdateFkList ?>
            ]);
        }
    }

    /**
     * Deletes an existing <?= $modelClass ?> model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionDelete(<?= $actionParams ?>)
    {
        $this->findModel(<?= $actionParams ?>)->delete();

        return $this->redirect(['index']);
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
}
