<style>
.thead_white table thead {
    background-color: #FFFFFF;
}
</style>

<?php

// use Yii;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper; 
use kartik\select2\Select2;
use Symfony\Component\VarDumper\VarDumper;
use vendor\meta_grid\helper\RBACHelper;
use yii\helpers\Url;



/* @var $this yii\web\View */
/* @var $searchModel app\models\ToolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'TEST');
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);

// Prevent loading bootstrap.css v3.4.1 (see T212)
\Yii::$app->assetManager->bundles['yii\\bootstrap\\BootstrapAsset'] = [
    'css' => [],
    'js' => []
];

?>
<div class="test-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


<?php




// public function getPermProjectsCanEditByUserId($userId)
// {
$auth = Yii::$app->authManager;
$userId = Yii::$app->User->Id;
$userPerms = $auth->getPermissionsByUser($userId);
$arrCanView = array();
$prefix = "view-";
$strip_prefix_on_result = true; // remove prefix in result array e.g. view-keyfigure to keyfigure
foreach($userPerms as $key => $perm){
	if (substr($perm->name,0,strlen($prefix)) === $prefix)
	{
		if ($strip_prefix_on_result) {array_push($arrCanView, substr($perm->name,strlen($prefix),strlen($perm->name)));}
		else {array_push($arrCanView, $perm->name);}
	}
   } 

   VarDumper::dump($arrCanView);
	// return $arrCanView;
// }    


?>

	
</div>
