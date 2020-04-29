
<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Project; 
use yii\helpers\ArrayHelper; 
use kartik\select2\Select2; 
use app\models\Client; 
use vendor\meta_grid\helper\RBACHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ContactGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Contact Groups');
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);
?>
<div class="contact-group-index">

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
		<?= Yii::$app->user->identity->isAdmin || Yii::$app->User->can('create-contactgroup')  ? Html::a(
		Yii::t('app', 'Create {modelClass}', ['modelClass' => Yii::t('app', 'Contact Group'),]), ['create'], ['class' => 'btn btn-success']) : "" ?>
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
        'filterModel' => $searchModel,
        'columns' => [
        	['class' => 'yii\grid\ActionColumn', 'contentOptions'=>[ 'style'=>'white-space: nowrap;']
            ,
				'template' => RBACHelper::filterActionColumn_meta_grid('{view} {update} {delete}'),
            ],
        	
        	['class' => 'yii\grid\SerialColumn'],

            [
             'label' => Yii::t('app', 'Client'),
             'value' => function($model) {
             		return $model->fk_client_id == "" ? $model->fk_client_id : (isset($_GET["searchShow"]) ? $model->fkClient->name . ' [' . $model->fk_client_id . ']' : $model->fkClient->name);
             		},
            'filter' => Select2::widget([
            		'model' => $searchModel,
            		'attribute' => 'fk_client_id',
            		'data' => ArrayHelper::map(app\models\Client::find()->asArray()->all(), 'id', 'name'),
            		'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_fkClient', 'multiple' => true],
            		'pluginOptions' => [
            				'allowClear' => true
            		],
			]),
            ],
            'name:ntext',
            'description:html',
            'short_name:ntext',
            [
             'label' => Yii::t('app', 'Deleted Status'),
             'value' => function($model) {
             		return $model->fk_deleted_status_id == "" ? $model->fk_deleted_status_id : (isset($_GET["searchShow"]) ? $model->fkDeletedStatus->name . ' [' . $model->fk_deleted_status_id . ']' : $model->fkDeletedStatus->name);
             		},
            'filter' => Select2::widget([
            		'model' => $searchModel,
            		'attribute' => 'fk_deleted_status_id',
            		'data' => ArrayHelper::map(app\models\DeletedStatus::find()->asArray()->all(), 'id', 'name'),
            		'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_fkDeletedStatus', 'multiple' => true],
            		'pluginOptions' => [
            				'allowClear' => true
            		],
			]),
            ],
        ],
    ]); ?>
	
</div>
