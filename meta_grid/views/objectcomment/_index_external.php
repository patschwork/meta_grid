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

		echo Html::a(Yii::t('app', 'Create new comment'), ['objectcomment/createexternal', 'ref_fk_object_id' => $dataProvider->query->where["ref_fk_object_id"], 'ref_fk_object_type_id' => $dataProvider->query->where["ref_fk_object_type_id"]],['class' => 'btn btn-primary'], [
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

//             'ref_fk_object_id',			// auskommentiert
//             'ref_fk_object_type_id',		// auskommentiert
            'comment:html',
            // 'created_at_datetime:ntext',	// auskommentiert

            //['class' => 'yii\grid\ActionColumn'],	// auskommentiert
        ],
    ]); ?>

</div>
