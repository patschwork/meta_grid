<?php

use app\models\DbTableField;
use app\models\Objectcomment;
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
		$Utils = new \vendor\meta_grid\helper\Utils();
		$db_table_show_buttons_for_different_object_type_updates = $Utils->get_app_config("db_table_show_buttons_for_different_object_type_updates");
		if ($db_table_show_buttons_for_different_object_type_updates == 1) 
		{
			echo Html::a(Yii::t('app', 'Create new field'), ['dbtablefield/createexternal', 'fk_db_table_id' => $fk_db_table_id], ['class' => 'btn btn-primary'], [
					'data' => [
							'method' => 'post',
					],
			]);
		}
        ?>
	<p></p>
	<?php
		$modelBracketDefinitions = new app\models\VBracketDefinitions();
		$bracketDefinitionsCountResultQry = $modelBracketDefinitions::find()->select(['db_table_field_id', 'COUNT(db_table_field_id) AS cnt'])->where(['db_table_id' => $fk_db_table_id])->groupBy(['db_table_field_id'])->createCommand()->queryAll();
		$bracketDefinitionsCountResult = array();
		// tramsform it to a more accessable format
		// from: $bracketDefinitionsCountResultQry[0 => ['db_table_field_id' => 4711, 'cnt' => 2], 2 => ['db_table_field_id' => 815, 'cnt' => 1]]
		// to:   $bracketDefinitionsCountResult[4711] = 2 and $bracketDefinitionsCountResult[815] = 1
		foreach ($bracketDefinitionsCountResultQry as $key => $value)
		{
			$bracketDefinitionsCountResult[$value['db_table_field_id']] = $value['cnt'];
		}

		$db_table_field_idQry = DbTableField::find()->select('id')->where(['fk_db_table_id' => $fk_db_table_id]);
		$db_table_field_objecttype_id = DbTableField::find()->select('fk_object_type_id')->where(['fk_db_table_id' => $fk_db_table_id])->one();
		$commentsCountResultQry = Objectcomment::find()->select(['ref_fk_object_id', 'COUNT(ref_fk_object_id) as cnt'])->where(['in', 'ref_fk_object_id', $db_table_field_idQry])->andWhere(['ref_fk_object_type_id' => $db_table_field_objecttype_id])->groupBy(['ref_fk_object_id'])->createCommand()->queryAll();

		$commentsCountResult = array();
		foreach ($commentsCountResultQry as $key => $value)
		{
			$commentsCountResult[$value['ref_fk_object_id']] = $value['cnt'];
		}

echo GridView::widget([
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
				            'template' => '{view} {comments} {brackets}',
				            'buttons' => [
				            		'comments' => function ($url, $model) use ($commentsCountResult) {
										$commentsCount = 0;
										$noComments="";
										if (isset($commentsCountResult[$model->id])) $commentsCount = $commentsCountResult[$model->id];
										if ($commentsCount == 0) $noComments=" style=\"color: silver;\" ";
				            			return Html::a('<span ' . $noComments . ' class="glyphicon glyphicon-comment"><small style="font-family:\'Courier New\', Arial;">'.$commentsCount.'</small></span>', $url, [
				            				'title' => Yii::t('app', 'There are {commentsCount} comment items', ['commentsCount' => $commentsCount]),
				            			]);
									},
				            		'brackets' => function ($url, $model) use ($bracketDefinitionsCountResult)  {
										$bracketDefinitionsCount = 0;
										if (isset($bracketDefinitionsCountResult[$model->id])) $bracketDefinitionsCount = $bracketDefinitionsCountResult[$model->id];
										$noComments="";
										if ($bracketDefinitionsCount == 0) $noComments=" style=\"color: silver;\" ";
				            			return Html::a('<span ' . $noComments . ' class="glyphicon">{}<small style="font-family:\'Courier New\', Arial;">'.$bracketDefinitionsCount.'</small></span>', $url, [
				            				'title' => Yii::t('app', 'There are {bracketDefinitionsCount} brackets each pattern associated', ['bracketDefinitionsCount' => $bracketDefinitionsCount]),
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
