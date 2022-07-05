<?php

use yii\helpers\Html;
use yii\widgets\DetailView;


// {... Beginn mit Tab Kommentierung pro Object
// 		autogeneriert ueber gii/CRUD
use yii\bootstrap\Tabs;
use yii\data\ActiveDataProvider;
// Kommentierung pro Object ...}

use yii\bootstrap\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use vendor\meta_grid\mermaid_js_asset\MermaidJSAsset;
MermaidJSAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\Bracket */

$js = '

jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {

    jQuery(".dynamicform_wrapper .panel-title-bracketsearchpattern").each(function(index) {

        jQuery(this).html("BracketSearchPattern: " + (index + 1))

    });

});


jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {

    jQuery(".dynamicform_wrapper .panel-title-bracketsearchpattern").each(function(index) {

        jQuery(this).html("BracketSearchPattern: " + (index + 1))

    });

});

';

$this->registerJs($js);

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Brackets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bracket-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Yii::$app->user->identity->isAdmin || Yii::$app->User->can('create-bracket')  ? Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) : "" ?>

	
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'uuid:ntext',
            'fk_object_type_id',
            [
             'label' => Yii::t('app', 'Client'),
             'value' =>              		$model->fk_project_id == "" ? $model->fk_project_id : $model->fkProject->fkClient->name
            ],
            [
             'label' => Yii::t('app', 'Project'),
             'value' =>              	$model->fk_project_id == "" ? $model->fk_project_id : $model->fkProject->name
            ],
            'name:ntext',
            'description:html',
            [
             'label' => Yii::t('app', 'Attribute'),
             'value' =>              	$model->fk_attribute_id == "" ? $model->fk_attribute_id : $model->fkAttribute->name
            ],
            [
             'label' => Yii::t('app', 'Object Type As Search Filter'),
             'value' =>              	$model->fk_object_type_id_as_searchFilter == "" ? $model->fk_object_type_id_as_searchFilter : $model->fkObjectTypeIdAsSearchFilter->name
            ],
        ],
    ]) ?>

	
    
    <div class="customer-form">
    <?php 
    	$form = ActiveForm::begin(['id' => 'dynamic-form']); 
    ?>
    <div class="row">
        <div class="col-sm-6">
            <?php
//             	 echo $form->field($modelBracket, 'name')->textInput(['maxlength' => true]) 
           	?>
        </div>
        <div class="col-sm-6">
            <?php
//             	 $form->field($modelBracket, 'description')->textInput(['maxlength' => true]) 
            ?>
        </div>
    </div>


    <div class="padding-v-md">
        <div class="line line-dashed"></div>
    </div>

    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
        'widgetBody' => '.container-items', // required: css class selector
        'widgetItem' => '.item', // required: css class
        'limit' => 4, // the maximum times, an element can be cloned (default 999)
        'min' => 0, // 0 or 1 (default 1)
        'insertButton' => '.add-item', // css class
        'deleteButton' => '.remove-item', // css class
        'model' => $modelsBracketSearchPattern[0],
        'formId' => 'dynamic-form',
        'formFields' => [
            'searchPattern',
        ],

    ]); ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <i class="fa fa-envelope"></i> Bracket Search Pattern Definitions
<!--             <button type="button" class="pull-right add-item btn btn-success btn-xs"><i class="fa fa-plus"></i> Add Definition</button> -->
            <div class="clearfix"></div>
        </div>

        <div class="panel-body container-items"><!-- widgetContainer -->
            <?php foreach ($modelsBracketSearchPattern as $index => $modelBracketSearchPattern): ?>
                <div class="item panel panel-default"><!-- widgetBody -->
                    <div class="panel-body">
                        <?php
                            // necessary for update action.
                            if (!$modelBracketSearchPattern->isNewRecord) {
                                echo Html::activeHiddenInput($modelBracketSearchPattern, "[{$index}]id");
                            }
                        ?>

                        <?= $form->field($modelBracketSearchPattern, "[{$index}]searchPattern")->textInput(['maxlength' => true, 'readonly' => true]) ?>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php DynamicFormWidget::end(); ?>
    <div class="form-group">
        <?php
//          Html::submitButton($modelBracketSearchPattern->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-primary']) 
         ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>
	
	
	
	
    	<?php
	
			
			// {... Kommentierung pro Object
		// 		autogeneriert ueber gii/CRUD
		$objectcommentSearchModel = new app\models\ObjectcommentSearch();
        
        $query = app\models\Objectcomment::find();
        $objectcommentDataProvider = new ActiveDataProvider([
        		'query' => $query,
				'pagination' => false,
        ]);
        
        $query->andFilterWhere([
        		'ref_fk_object_id' => $model->id,
        		'ref_fk_object_type_id' => $model->fk_object_type_id,
        ]);
        
        $mapObject2ObjectSearchModel = new app\models\VAllMappingsUnion();
        $queryMapObject2Object = app\models\VAllMappingsUnion::find();
        $mapObject2ObjectDataProvider = new ActiveDataProvider([
        		'query' => $queryMapObject2Object,
				'pagination' => false,
        ]);
        $queryMapObject2Object->andFilterWhere([
        		'filter_ref_fk_object_id' => $model->id,
        		'filter_ref_fk_object_type_id' => $model->fk_object_type_id,
        ]);

		     
        
		echo Tabs::widget([
			'items' => 
			[
				[
					'label' => Yii::t('app', 'Comments'),
					'content' => $this->render('../objectcomment/_index_external', [
								    'searchModel' => $objectcommentSearchModel,
            						'dataProvider' => $objectcommentDataProvider,
								    ]),
					'active' => true,
					'options' => ['id' => 'tabComments']  // important for shortcut
				],		
				[
					'label' => Yii::t('app', 'Mapping'),
					'content' => $this->render('../mapper/_index_external', [
							'searchModel' => $mapObject2ObjectSearchModel,
							'dataProvider' => $mapObject2ObjectDataProvider,
					]),
					'active' => false,
					'options' => ['id' => 'tabMapping']  // important for shortcut
				],
						],
		]);
		// Kommentierung pro Object ...}
	
	    		
	?>  
    
    
</div>
