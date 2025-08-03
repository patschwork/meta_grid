<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Project; 
use yii\helpers\ArrayHelper; 
use kartik\select2\Select2; 
use app\models\Client; 
use app\models\VAllObjectsUnion;
use app\models\Objectcomment;
use app\models\ObjectType;
use Symfony\Component\VarDumper\VarDumper;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $searchModel app\models\GlobalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('app', 'Mapping');
// $this->params['breadcrumbs'][] = $this->title;

// Prevent loading bootstrap.css v3.4.1 (see T212)
\Yii::$app->assetManager->bundles['yii\\bootstrap\\BootstrapAsset'] = [
    'css' => [],
    'js' => []
];
?>
<div class="vall-objects-union-index">

    <h1><?= Html::encode($this->title) ?></h1>
	


	<?php
	$session = Yii::$app->session;
	$session->set('already_mapped_listkey', $already_mapped_listkey);

	echo Html::a(Yii::t('app', 'Back to source'), $from_url, ['class' => 'btn btn-primary'], [
	]);
	?>
	
    <h2><?= Html::encode(Yii::t('app', 'From:')) ?></h2>
	<?= DetailView::widget([
        'model' => $from_model,
        'attributes' => [
            // 'id',
            // 'fk_object_type_id',
            'name:ntext',
            'object_type_name:ntext',
            [
             'label' => 'Client',
             'value' =>              	$from_model->fk_project_id == "" ? $from_model->fk_project_id : $from_model->fkProject->fkClient->name
            ],
            [
             'label' => Yii::t('app', 'Project'),
             'value' =>              	$from_model->fk_project_id == "" ? $from_model->fk_project_id : $from_model->fkProject->name
            ],
        ],
    ]) ?>

<h2><?= Html::encode(Yii::t('app', 'To:')) ?></h2>

<?php

echo $this->render('_search', ['model' =>$searchModel, 'from_model' => $from_model]);
?>
		<?php // {... mapperv2 ?>

		<?php $form = ActiveForm::begin(['id' => 'item-form']); ?>
		
		<div class="form-group">
		<?php $translation_addmapping =  Yii::t('app', 'Add mapping');?>
    	<?= Html::button($translation_addmapping, ['class' => 'btn btn-success', 'id' => 'submit-button']) ?>
		</div>
		
		<?php // ...} ?>

		<?=Html::hiddenInput($name="from_id", $value=$from_model->id);?>
		<?=Html::hiddenInput($name="from_fk_object_type_id", $value=$from_model->fk_object_type_id);?>
		<?=Html::hiddenInput($name="from_url", $value=$from_url);?>
		
	    <?php
		$dependency = new yii\caching\DbDependency();
		$dependency->sql='SELECT max(log_datetime) FROM "v_LastChangesLog_List"';
				
		echo GridView::widget([
			'id' => 'gridview_mappings',
        'dataProvider' => $dataProvider,
		'pager' => [
			'firstPageLabel' => '<span class="glyphicon glyphicon-chevron-left"></span><span class="glyphicon glyphicon-chevron-left"></span>',
			'lastPageLabel' => '<span class="glyphicon glyphicon-chevron-right"></span><span class="glyphicon glyphicon-chevron-right"></span>',
			'prevPageLabel' => '<span class="glyphicon glyphicon-chevron-left"></span>',
			'nextPageLabel' => '<span class="glyphicon glyphicon-chevron-right"></span>',
			'maxButtonCount' => 15,
		],
		'layout' => "{pager}\n{summary}{items}\n{pager}",
        'filterModel' => $searchModel,
        'columns' => [
			// {... mapperv2 
			[
				'class' => 'yii\grid\CheckboxColumn'
						, 'checkboxOptions' => function($model) {
					  return ['value' => $model->listkey];
				  		},
			], 
			// ...} 
            [
				// erzeugt einen Link zu dem Mappingobjekt
				// siehe auch: http://www.yiiframework.com/forum/index.php/topic/49595-how-to-change-buttons-in-actioncolumn/
	            'class' => 'yii\grid\ActionColumn',
	            'template' => '{view}',
	            'buttons' => [
	            		'view' => function ($url, $model) {
	            			return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
									'target' => '_blank',
	            					'title' => Yii::t('app', "Open detail view of object"),
	            			]);
	            		}
	            ],
	            'urlCreator' => function ($action, $model, $key, $index) {
	            	if ($action === 'view') {
	            		$cntrl="";
	            		$link_to_objId=explode(";",$model->listkey)[0];
	            		$link_to_objTypeId=explode(";",$model->listkey)[1];
	            		
	            		$cntrl=str_replace("_","",$model->object_type_name);
	            		
	            		// Wenn es ein Kommentar ist, dann zu dem ObjectTyp des Kommentars linken.
	            		if ($link_to_objTypeId==12)
	            		{
	            			$comment_model  = Objectcomment::findOne($link_to_objId);
	            			
	            			$objIdFromComment = $comment_model->ref_fk_object_id;
	            			$objIdTypeFromComment = $comment_model->ref_fk_object_type_id;
	            			
	            			$object_type_model = ObjectType::findOne($objIdTypeFromComment);
	            			
	            			// Variablen f�r Link URL neu setzen
	            			$link_to_objId = $objIdFromComment;
	            			$cntrl=str_replace("_","",$object_type_model->name);
	            		}
	            		
	            		$url = "?r=$cntrl/view&id=".$link_to_objId; // your own url generation logic
	            		return $url;
	            	}
	            }
	             
            ],    
        	
        	['class' => 'yii\grid\SerialColumn'],

			[
				'label' => Yii::t('app', 'name'),
				'value' => function($model) {
					$value = $model->name;
					// $value = $model->name."<br>"."<small>".$model->detail_1_content."</small>";
					if ($model->detail_1_content !== NULL)
					{
						$value .= "<br>"."<small>"."<b>".$model->detail_1_name.": "."</b>".$model->detail_1_content."</small>";
					}
					if ($model->detail_2_content !== NULL)
					{
						$value .= "<br>"."<small>"."<b>".$model->detail_2_name.": "."</b>".$model->detail_2_content."</small>";
					}
					if ($model->detail_3_content !== NULL)
					{
						$value .= "<br>"."<small>"."<b>".$model->detail_3_name.": "."</b>".$model->detail_3_content."</small>";
					}
					if ($model->detail_4_content !== NULL)
					{
						$value .= "<br>"."<small>"."<b>".$model->detail_4_name.": "."</b>".$model->detail_4_content."</small>";
					}
					if ($model->detail_5_content !== NULL)
					{
						$value .= "<br>"."<small>"."<b>".$model->detail_5_name.": "."</b>".$model->detail_5_content."</small>";
					}

					return $value;
					},
				'format' => 'html',
                'contentOptions' => function($model) {
                    // needs to be closure because of title
					if (($model->description !== NULL) && ($model->description !== '(Added via Bulk Import)'))
					{
                    return [
                        'class' => 'cell-with-tooltip',
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'top', // top, bottom, left, right
                        'data-container' => 'body', // to prevent breaking table on hover
                        // 'title' => strip_tags($data->description),
                        'title' => "<b><i>".Yii::t('app', 'Description')."</i></b>:<br>".$model->description, // use html
					];
					} else return [];
                }
			],
			[
				'header' => '<i class="fa fa-fw fa-info">',
				'value' => function($model) {
					$session = Yii::$app->session;
					$already_mapped_listkey = $session->get('already_mapped_listkey');					

					if (array_key_exists($model->listkey, $already_mapped_listkey))
					{return Yii::t('app', $already_mapped_listkey[$model->listkey]);}
					else
					{return "";}
					},				
			],
			[
			    'label' => Yii::t('app', 'object_type_name'),
				'filter' => Select2::widget([
						'model' => $searchModel,
						'attribute' => 'object_type_name',
						'data' => ArrayHelper::map(VAllObjectsUnion::find()->select(['object_type_name'])->distinct()->cache(NULL, $dependency)->asArray()->all(), 'object_type_name', 'object_type_name'),
						'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_object_type_name'],
						'pluginOptions' => [
								'allowClear' => true,
								'multiple' => true
							],
				 ]),
				 'value' => function($model) {
             		return $model->object_type_name;
             		},
			],
            // 'object_type_name:ntext',
            // 'listvalue_1',
            // 'listvalue_2',
            // 'listkey',
/*            [
             'label' => Yii::t('app', 'Client'),
             'value' => function($model) {
             		return $model->fk_client_id == "" ? $model->fk_client_id : (isset($_GET["searchShow"]) ? $model->fkClient->name . ' [' . $model->fk_client_id . ']' : $model->fkClient->name);
             		},
            'filter' => Select2::widget([
            		'model' => $searchModel,
            		'attribute' => 'fk_client_id',
            		'data' => ArrayHelper::map(app\models\Client::find()->asArray()->all(), 'id', 'name'),
            		'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_fkClient'],
            		'pluginOptions' => [
            				'allowClear' => true
            		],
			]),
            ],            
*/
            [
             'label' => Yii::t('app', 'Project'),
             'value' => function($model) {
					 return $model->fk_project_id == "" ? $model->fk_project_id : $model->fkProject->name . " (" . $model->fkProject->fkClient->name .")";
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
        ],
    ]); ?>

	<?php // {... mapperv2 ?>
	<?php ActiveForm::end(); ?>
	<?php // ...} ?>

</div>

<?php
// Prepare needed translations
$translations = [
    'processed' => Yii::t('app', 'Selection has been processed'),
    'error' => Yii::t('app', 'Error occured while processing the selected items!'),
    'already_mapped' => Yii::t('app', 'Already mapped'),
    'just_mapped' => Yii::t('app', 'just mapped'),
];
$translations_json =  json_encode($translations);
?>

<?php 
$js = <<<SCRIPT

function updateGridView(response) {
    if (response.success) {
        // Angenommen, die GridView hat eine Spalte mit der Klasse 'info'
        response.markedRows.forEach(function(rowId) {

			var inputValue = rowId.id + ';' + rowId.fk_object_type_id;

			// Selector for the input element with the corresponding value
			var selector = 'input[type="checkbox"][value="' + inputValue + '"]';

			// Find the input element and then the corresponding row
			var row = $(selector).closest('tr'); // Go to the next <tr> over the <input>

			// Function to briefly flash the background color, set the text color to green, and change the text
			function flashRow(row) {
				// Set the background color to #A5FF7F
				row.css('background-color', '#A5FF7F');

				// Reset the background color after 1500ms
				setTimeout(function() {
					row.css('background-color', ''); // Reset the background color
					row.css('color', 'green'); // Set the text color to green
					
					// Change the text in the second to last column (assuming it is the 5th column, index 4)
					var cell = row.find('td').eq(4); // Find the second to last column
					if (cell.text() !== translations.already_mapped) { // Check if the text is not "Already mapped"
						cell.text(translations.just_mapped); // Change the text
					}
				}, 1500); // Duration of the flash in milliseconds
			}

			// Call the function
			flashRow(row);
        });
    }
}

var translations = $translations_json;

/* To initialize BS3 tooltips set this below */
$(function () { 
   $('body').tooltip({
    selector: '[data-toggle="tooltip"]',
        html:true
    });
});


$('#submit-button').on('click', function() {
    var keys = $('#gridview_mappings').yiiGridView('getSelectedRows');

    $.ajax({
        url: 'index.php?r=mapper-v2/process', // URL to process
        type: 'POST',
        data: {selection: keys, from_id: $from_model->id, from_fk_object_type_id: $from_model->fk_object_type_id},
        success: function(response) {
            // alert(translations.processed);
            // Optional: Seite neu laden oder andere Aktionen durchführen
			updateGridView(response);
        },
        error: function() {
            alert(translations.error);
        }
    });
});

SCRIPT;
// Register tooltip/popover initialization javascript
$this->registerJs ( $js );
?>