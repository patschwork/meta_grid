<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\VarDumper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DbTableFieldSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="db-table-field-index">


    <br>

        <?php
			echo Html::a(Yii::t('app', 'Create new field'), ['dbtablefield/createexternal', 'fk_db_table_id' => $fk_db_table_id], ['class' => 'btn btn-primary'], [
					'data' => [
							'method' => 'post',
					],
			])
					
        ?>
	<p></p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'pager' => [
			'firstPageLabel' => '<span class="glyphicon glyphicon-chevron-left"></span><span class="glyphicon glyphicon-chevron-left"></span>',
			'lastPageLabel' => '<span class="glyphicon glyphicon-chevron-right"></span><span class="glyphicon glyphicon-chevron-right"></span>',
			'prevPageLabel' => '<span class="glyphicon glyphicon-chevron-left"></span>',
			'nextPageLabel' => '<span class="glyphicon glyphicon-chevron-right"></span>',
			'maxButtonCount' => 15,
		],
        'filterModel' => $searchModel,
        'columns' => [
						[
				            'class' => 'yii\grid\ActionColumn',
				            'template' => '{view}',
				            'buttons' => [
				            		'info' => function ($url, $model) {
				            			return Html::a('<span class="glyphicon glyphicon-info-sign"></span>', $url, [
				            					'title' => Yii::t('app', 'Info'),
				            			]);
				            		}
				            ],
				            'urlCreator' => function ($action, $model, $key, $index) {
				            	if ($action === 'view') {
				            		$url = "?r=dbtablefield/view&id=".$model->id; // your own url generation logic
				            		return $url;
				            	}
				            }
				             
			            ],
			            ['class' => 'yii\grid\SerialColumn'],
			            'name:ntext',
			            'description:html',
			            'datatype:ntext',
                    ],
    ]); ?>

		            
</div>
