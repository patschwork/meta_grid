<style>
.thead_white table thead {
    background-color: #FFFFFF;
}
</style>

<?php
/**
 * THIS CONTROLLER IS NOT MANAGED BY META#GRID-GII
 */
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper; 
use kartik\select2\Select2; 
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\VTag2ObjectListSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'V Tag 2 Object Lists');
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);

// Prevent loading bootstrap.css v3.4.1 (see T212)
\Yii::$app->assetManager->bundles['yii\\bootstrap\\BootstrapAsset'] = [
    'css' => [],
    'js' => []
];

?>
<div class="vtag2-object-list-index">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->



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

	Url::remember();
	?>
	    <?= GridView::widget([
		'tableOptions' => ['id' => 'grid-view-vtag2-object-list', 'class' => 'table table-striped table-bordered'],
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
       	// 'rowOptions' => function ($model, $key, $index, $grid) {
       	// 	$controller = Yii::$app->controller->id;
       	// 	return [
       	// 			'ondblclick' => 'location.href="'
       	// 			. Yii::$app->urlManager->createUrl([$controller . '/view','id'=>$key])
       	// 			. '"',
       	// 	];
       	// },
		'options' => [
			'class' => 'thead_white',
		],    
        'filterModel' => $searchModel,
        'columns' => [
        	// ['class' => 'yii\grid\ActionColumn', 'contentOptions'=>[ 'style'=>'white-space: nowrap;']
            // ,
			// 	'template' => RBACHelper::filterActionColumn_meta_grid('{view} {update} {delete}'),
            // ],  	
        	['class' => 'yii\grid\SerialColumn'],
            'object_type_name:ntext',
			[
				'label' => Yii::t('app', 'Object name'),
				'format' => 'raw',
				'value' => function($model) {
					return Html::a($model->object_name, rawurldecode(Url::toRoute([$model->action])));
					},
			],
            [
             'label' => Yii::t('app', 'Tag'),
             'value' => function($model) {
						$tag_name = $model->fk_tag_id == "" ? $model->fk_tag_id : (isset($_GET["searchShow"]) ? $model->tag_name . ' [' . $model->fk_tag_id . ']' : $model->tag_name);
						$icon = $model->optgroup == 0 ? 'text-info' : ($model->optgroup == 1 ? 'text-danger' : "text-warning");
						$icon_html = '<i class="nav-icon far fa-circle ' . $icon . '"></i>';
             			return $icon_html . " " . $tag_name;
             		},
             'filter' => Select2::widget([
            		'model' => $searchModel,
            		'attribute' => 'fk_tag_id',
            		'data' => ArrayHelper::map(app\models\Tag::find()->asArray()->all(), 'id', 'name'),
            		'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_fkTag', 'multiple' => true],
            		'pluginOptions' => [
            				'allowClear' => true
            		],		
				]),
			'format' => 'html',
			'visible' => true,
            ],
        ],
    ]); ?>

	<?php 	$Utils = new \vendor\meta_grid\helper\Utils();
	if ($Utils->get_app_config("floatthead_for_gridviews") == 1)
	{
		\bluezed\floatThead\FloatThead::widget(
			[
				'tableId' => 'grid-view-vtag2-object-list', 
				'options' => [
					'top'=>'50'
				]
			]
		);
	}
	?>

	
</div>
