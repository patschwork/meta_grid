
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
/* @var $searchModel app\models\DbTableFieldSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Db Table Fields');
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);
?>
<div class="db-table-field-index">

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
		<?= Yii::$app->user->identity->isAdmin || Yii::$app->User->can('create-dbtablefield')  ? Html::a(
		Yii::t('app', 'Create {modelClass}', ['modelClass' => Yii::t('app', 'Db Table Field'),]), ['create'], ['class' => 'btn btn-success']) : "" ?>
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
				'template' => RBACHelper::filterActionColumn_meta_grid('{view} {update} {update-dbtablefield-individual} {delete}'),

				'buttons' => [
					'update-dbtablefield-individual' => function ($url, $model) {

						$html_btn = Html::a('<span style="color: silver;" class="glyphicon glyphicon-pencil"></span>', $url, [
								'title' => Yii::t('app', 'Update dbtablefield individual'),
						]);

						$db_table_show_buttons_for_different_object_type_updates_arr = (new yii\db\Query())->from('app_config')->select(['valueINT'])->where(["key" => "db_table_show_buttons_for_different_object_type_updates"])->one();

						$db_table_show_buttons_for_different_object_type_updates = $db_table_show_buttons_for_different_object_type_updates_arr['valueINT'];
			
						if ($db_table_show_buttons_for_different_object_type_updates == 1) 
						{
							return $html_btn;
						}
					}
				],
				'urlCreator' => function ($action, $model, $key, $index) {
					if ($action === 'update') {
						$url = "?r=dbtablefieldmultipleedit/update&id=".$model->fk_db_table_id; // your own url generation logic
						return $url;
					}
					if ($action === 'update-dbtablefield-individual') {
						$url = "?r=dbtablefield/update&id=".$model->id; // your own url generation logic
						return $url;
					}
				}
            ],
        	
        	['class' => 'yii\grid\SerialColumn'],

            [
             'label' => Yii::t('app', 'Client'),
             'value' => function($model) {
             		return $model->fk_project_id == "" ? $model->fk_project_id : $model->fkProject->fkClient->name;
             		},
             		'filter' => Select2::widget([
             				'model' => $searchModel,
             				'attribute' => 'fk_project_id',
             				'data' => ArrayHelper::map(Project::find()->select('project.id, client.name, project.fk_client_id')->distinct()->joinWith('fkClient')->asArray()->all(), 'id', 'name'),
             				'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_client_id'],
             				'pluginOptions' => [
             						'allowClear' => true
             				],
             		]),
            ],
            [
             'label' => Yii::t('app', 'Project'),
             'value' => function($model) {
             		return $model->fk_project_id == "" ? $model->fk_project_id : (isset($_GET["searchShow"]) ? $model->fkProject->name . ' [' . $model->fk_project_id . ']' : $model->fkProject->name);
             		},
            'filter' => Select2::widget([
            		'model' => $searchModel,
            		'attribute' => 'fk_project_id',
            		'data' => ArrayHelper::map(app\models\Project::find()->asArray()->all(), 'id', 'name'),
            		'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_fkProject'],
            		'pluginOptions' => [
            				'allowClear' => true
            		],
			]),
            ],
            'name:ntext',
            'description:html',
            [
             'label' => Yii::t('app', 'Db Table'),
             'value' => function($model) {
             		return $model->fk_db_table_id == "" ? $model->fk_db_table_id : (isset($_GET["searchShow"]) ? $model->fkDbTable->name . ' [' . $model->fk_db_table_id . ']' : $model->fkDbTable->name);
             		},
            'filter' => Select2::widget([
            		'model' => $searchModel,
            		'attribute' => 'fk_db_table_id',
            		'data' => ArrayHelper::map(app\models\DbTable::find()->asArray()->all(), 'id', 'name'),
            		'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_fkDbTable', 'multiple' => true],
            		'pluginOptions' => [
            				'allowClear' => true
            		],
			]),
            ],
			'databaseInfoFromLocation:ntext',
			'datatype:ntext',
            // 'bulk_load_checksum:ntext',
/*            [
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
*/            // 'is_PrimaryKey:boolean',
            // 'is_BusinessKey:boolean',
            // 'is_GDPR_relevant:boolean',
        ],
    ]); ?>
	
</div>
