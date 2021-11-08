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
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MapObject2ObjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Map Object2 Objects');
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);
?>
<div class="map-object2-object-index">

    <h1><?= Html::encode($this->title) ?></h1>
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
		<?= Yii::$app->user->identity->isAdmin || Yii::$app->User->can('create-mapper')  ? Html::a(
		Yii::t('app', 'Create {modelClass}', ['modelClass' => Yii::t('app', 'Map Object2 Object'),]), ['create'], ['class' => 'btn btn-success']) : "" ?>
					</p>

	<?php
	$session = Yii::$app->session;
	
	// Inform user about set perspective_filter
	if (array_key_exists("fk_object_type_id",  $searchModel->attributes) === true && (isset($searchModel->find()->select(['fk_object_type_id'])->one()->fk_object_type_id) === true))
	{
		$fk_object_type_id=$searchModel->find()->select(['fk_object_type_id'])->one()->fk_object_type_id;
		if ($session->hasFlash('perspective_filter_for_' . $fk_object_type_id))
		{	
			echo yii\bootstrap\Alert::widget([
					'options' => [
									'class' => 'alert-info',
					],
					'body' => $session->getFlash('perspective_filter_for_' . $fk_object_type_id),
			]);
		}		
	}
	
	if ($session->hasFlash('deleteError'))
	{	
		echo yii\bootstrap\Alert::widget([
				'options' => [
					'class' => 'alert alert-danger alert-dismissable',
				],
				'body' => $session->getFlash('deleteError'),
		]);
	}

	Url::remember();
	?>
	    <?= GridView::widget([
		'tableOptions' => ['id' => 'grid-view-map-object2-object', 'class' => 'table table-striped table-bordered'],
		'dataProvider' => $dataProvider,
		'pager' => [
			'firstPageLabel' => '<span class="glyphicon glyphicon-chevron-left"></span><span class="glyphicon glyphicon-chevron-left"></span>',
			'lastPageLabel' => '<span class="glyphicon glyphicon-chevron-right"></span><span class="glyphicon glyphicon-chevron-right"></span>',
			'prevPageLabel' => '<span class="glyphicon glyphicon-chevron-left"></span>',
			'nextPageLabel' => '<span class="glyphicon glyphicon-chevron-right"></span>',
			'maxButtonCount' => 15,
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

            'ref_fk_object_id_1',
            'ref_fk_object_type_id_1',
            'ref_fk_object_id_2',
            'ref_fk_object_type_id_2',
            [
             'label' => Yii::t('app', 'Mapping Qualifier'),
             'value' => function($model) {
             		return $model->fk_mapping_qualifier_id == "" ? $model->fk_mapping_qualifier_id : (isset($_GET["searchShow"]) ? $model->fkMappingQualifier->name . ' [' . $model->fk_mapping_qualifier_id . ']' : $model->fkMappingQualifier->name);
             		},
            'filter' => Select2::widget([
            		'model' => $searchModel,
            		'attribute' => 'fk_mapping_qualifier_id',
            		'data' => ArrayHelper::map(app\models\MappingQualifier::find()->asArray()->all(), 'id', 'name'),
            		'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_fkMappingQualifier', 'multiple' => true],
            		'pluginOptions' => [
            				'allowClear' => true
            		],
			]),
            ],
/*            [
             'label' => Yii::t('app', 'Object Persistence Method'),
             'value' => function($model) {
             		return $model->fk_object_persistence_method_id == "" ? $model->fk_object_persistence_method_id : (isset($_GET["searchShow"]) ? $model->fkObjectPersistenceMethod->name . ' [' . $model->fk_object_persistence_method_id . ']' : $model->fkObjectPersistenceMethod->name);
             		},
            'filter' => Select2::widget([
            		'model' => $searchModel,
            		'attribute' => 'fk_object_persistence_method_id',
            		'data' => ArrayHelper::map(app\models\ObjectPersistenceMethod::find()->asArray()->all(), 'id', 'name'),
            		'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_fkObjectPersistenceMethod', 'multiple' => true],
            		'pluginOptions' => [
            				'allowClear' => true
            		],
			]),
            ],
*//*            [
             'label' => Yii::t('app', 'Datamanagement Process'),
             'value' => function($model) {
             		return $model->fk_datamanagement_process_id == "" ? $model->fk_datamanagement_process_id : (isset($_GET["searchShow"]) ? $model->fkDatamanagementProcess->name . ' [' . $model->fk_datamanagement_process_id . ']' : $model->fkDatamanagementProcess->name);
             		},
            'filter' => Select2::widget([
            		'model' => $searchModel,
            		'attribute' => 'fk_datamanagement_process_id',
            		'data' => ArrayHelper::map(app\models\DatamanagementProcess::find()->asArray()->all(), 'id', 'name'),
            		'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_fkDatamanagementProcess', 'multiple' => true],
            		'pluginOptions' => [
            				'allowClear' => true
            		],
			]),
            ],
*/        ],
    ]); ?>

	<?php 	if (\vendor\meta_grid\helper\Utils::get_app_config("floatthead_for_gridviews") == 1)
	{
		\bluezed\floatThead\FloatThead::widget(
			[
				'tableId' => 'grid-view-map-object2-object', 
				'options' => [
					'top'=>'50'
				]
			]
		);
	}
	?>

	
</div>
