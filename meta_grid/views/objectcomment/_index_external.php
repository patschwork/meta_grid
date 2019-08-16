<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ObjectcommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

// $this->title = Yii::t('app', 'Objectcomments');	// auskommentiert
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="objectcomment-index">

<br>
    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
    <?php // echo $this->render('_search', ['model' => $searchModel]);
		
// 		echo "<pre>";
// 		print_r($dataProvider->query->where["ref_fk_object_id"]);
// 		print_r($dataProvider->query->where["ref_fk_object_type_id"]);
// 		echo "</pre>";

		echo Html::a(Yii::t('app', 'Create new comment'), ['objectcomment/createexternal', 'ref_fk_object_id' => $dataProvider->query->where["ref_fk_object_id"], 'ref_fk_object_type_id' => $dataProvider->query->where["ref_fk_object_type_id"]],['class' => 'btn btn-primary', 'id' => 'btnCreateNewComment'], [
		'data' => [
		'method' => 'post',
		],
		])
	?>

    <p>
        <!-- // auskommentiert -->
        <!-- <?= Html::a(Yii::t('app', 'Create {modelClass}', [
			    'modelClass' => 'Objectcomment',
			]), ['create'], ['class' => 'btn btn-success']) 
		?> -->
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'comment:html',
			[
				// erzeugt einen Link Kommentareintrag
				// siehe auch: http://www.yiiframework.com/forum/index.php/topic/49595-how-to-change-buttons-in-actioncolumn/
				'class' => 'yii\grid\ActionColumn',
				'template' => '{view}',
				'buttons' => [
						'info' => function ($url, $model) {
							return Html::a('<span class="glyphicon glyphicon-info-sign"></span>', $url, [
									'title' => Yii::t('app', 'Go to comment detail'),
							]);
						}
					],
				'urlCreator' => function ($action, $model, $key, $index) {
					if ($action === 'view') {	            		
						$url = "?r=objectcomment/view&id=".$key;
						return $url;
						}
					}
			]
        ],
    ]); ?>

</div>
