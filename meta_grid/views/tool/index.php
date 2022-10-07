<style>
.thead_white table thead {
    background-color: #FFFFFF;
}
</style>

<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper; 
use kartik\select2\Select2; 
use vendor\meta_grid\helper\RBACHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ToolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tools');
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);

// Prevent loading bootstrap.css v3.4.1 (see T212)
\Yii::$app->assetManager->bundles['yii\\bootstrap\\BootstrapAsset'] = [
    'css' => [],
    'js' => []
];

?>
<div class="tool-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<?php
// Das ist nicht der Yii2-Way, ... @ToDo
if (isset($_GET["searchShow"]))
{
	echo $this->render('_search', ['model' =>$searchModel]);
}
else
{
	echo "<a class='btn btn-default' href='index.php?r=".$_GET["r"]."&searchShow=1'>".Yii::t('app', 'Advanced Search')."</a></br></br>";
}
?>

    <p>
		<?= Yii::$app->user->identity->isAdmin || Yii::$app->User->can('create-tool')  ? Html::a(
		Yii::t('app', 'Create {modelClass}', ['modelClass' => Yii::t('app', 'Tool'),]), ['create'], ['class' => 'btn btn-success']) : "" ?>
					</p>

	<?php
	$session = Yii::$app->session;
	
	// Inform user about set perspective_filter
	if (array_key_exists("fk_object_type_id",  $searchModel->attributes) === true && (isset($searchModel->find()->select(['fk_object_type_id'])->one()->fk_object_type_id) === true))
	{
		$fk_object_type_id=$searchModel->find()->select(['fk_object_type_id'])->one()->fk_object_type_id;
		if ($session->hasFlash('perspective_filter_for_' . $fk_object_type_id))
		{	
			echo yii\bootstrap4\Alert::widget([
					'options' => [
									'class' => 'alert-info',
					],
					'body' => $session->getFlash('perspective_filter_for_' . $fk_object_type_id),
			]);
		}		
	}
	
	if ($session->hasFlash('deleteError'))
	{	
		echo yii\bootstrap4\Alert::widget([
				'options' => [
					'class' => 'alert alert-danger alert-dismissable',
				],
				'body' => $session->getFlash('deleteError'),
		]);
	}

	\yii\helpers\Url::remember($url = '', $name = Yii::$app->controller->id."/INDEX");
	?>
	    <?= GridView::widget([
		'tableOptions' => ['id' => 'grid-view-tool', 'class' => 'table table-striped table-bordered'],
		'dataProvider' => $dataProvider,
		'pager' => [
			'firstPageLabel' => '<span class="glyphicon glyphicon-chevron-left"></span><span class="glyphicon glyphicon-chevron-left"></span>',
			'lastPageLabel' => '<span class="glyphicon glyphicon-chevron-right"></span><span class="glyphicon glyphicon-chevron-right"></span>',
			'prevPageLabel' => '<span class="glyphicon glyphicon-chevron-left"></span>',
			'nextPageLabel' => '<span class="glyphicon glyphicon-chevron-right"></span>',
			'maxButtonCount' => 15,
			'class' => 'yii\bootstrap4\LinkPager'
		],
		'layout' => "{pager}\n{summary}{items}\n{pager}",
       	'rowOptions' => function ($model, $key, $index, $grid) {
       		$controller = Yii::$app->controller->id;
       		return [
       				'ondblclick' => 'location.href="'
       				. Yii::$app->urlManager->createUrl([$controller . '/view','id'=>$key])
       				. '"',
       		];
       	},
		'options' => [
			'class' => 'thead_white',
		],    
        'filterModel' => $searchModel,
        'columns' => [
        	['class' => 'yii\grid\ActionColumn', 'contentOptions'=>[ 'style'=>'white-space: nowrap;']
            ,
				'template' => RBACHelper::filterActionColumn_meta_grid('{view} {update} {delete}'),
            ],
        	
        	['class' => 'yii\grid\SerialColumn'],

            [
             'label' => Yii::t('app', 'Tool Type'),
             'value' => function($model) {
             		return $model->fk_tool_type_id == "" ? $model->fk_tool_type_id : (isset($_GET["searchShow"]) ? $model->fkToolType->name . ' [' . $model->fk_tool_type_id . ']' : ($model->fkToolType=== NULL ? Yii::t('app', "Can't lookup the {relFieldname} name (for id {this_id})", ['relFieldname' => 'fkToolType', 'this_id' => $model->fk_tool_type_id]) : $model->fkToolType->name));
             		},
            'filter' => Select2::widget([
            		'model' => $searchModel,
            		'attribute' => 'fk_tool_type_id',
            		'data' => ArrayHelper::map(app\models\ToolType::find()->asArray()->all(), 'id', 'name'),
            		'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_fkToolType', 'multiple' => true],
            		'pluginOptions' => [
            				'allowClear' => true
            		],
			]),
			'contentOptions' => function ($model, $key, $index, $column) {
			     return $model->fkToolType === NULL ? ['style' => 'color: red'] : [];
			 },
            ],
            'tool_name:ntext',
            'vendor:ntext',
            'version:ntext',
            'comment:html',
        ],
    ]); ?>

	<?php 	$Utils = new \vendor\meta_grid\helper\Utils();
	if ($Utils->get_app_config("floatthead_for_gridviews") == 1)
	{
		\bluezed\floatThead\FloatThead::widget(
			[
				'tableId' => 'grid-view-tool', 
				'options' => [
					'top'=>'50'
				]
			]
		);
	}
	?>

	
</div>
