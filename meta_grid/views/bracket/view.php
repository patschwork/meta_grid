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



$this->title = $modelBracket->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Brackets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bracket-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php 
        	echo Html::a(Yii::t('app', 'Update'), ['update', 'id' => $modelBracket->id], ['class' => 'btn btn-primary']) 
        ?>
        <?php 
//         	Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $modelBracket->id], [
//             'class' => 'btn btn-danger',
//             'data' => [
//                 'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
//                 'method' => 'post',
//             ],
//         ]) 
        ?>
    </p>

    <?php
    $model = $modelBracket;
     echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'uuid:ntext',
            'fk_object_type_id',
            [
             'label' => 'Client',
             'value' =>              		$model->fk_project_id == "" ? $model->fk_project_id : $model->fkProject->fkClient->name
            ],
            [
             'label' => 'Project',
             'value' =>              		$model->fk_project_id == "" ? $model->fk_project_id : $model->fkProject->name
            ],
            'name:ntext',
            'description:html',
            [
             'label' => 'Attribute',
             'value' =>              		$model->fk_attribute_id == "" ? $model->fk_attribute_id : $model->fkAttribute->name
            ],
            [
             'label' => 'Object Type As Search Filter',
             'value' =>              		$model->fk_object_type_id_as_searchFilter == "" ? $model->fk_object_type_id_as_searchFilter : $model->fkObjectTypeIdAsSearchFilter->name
            ],
        ],
    ]) 
    
    ?>

    
    

    
    
    
    
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
                    <div class="panel-heading">
                        <span class="panel-title-bracketsearchpattern">bracketsearchpattern_: <?= ($index + 1) ?></span>
<!--                         <button type="button" class="pull-right remove-item btn btn-danger btn-xs"><i class="fa fa-minus"></i></button> -->
                        <div class="clearfix"></div>
                    </div>

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
        ]);
        
        $query->andFilterWhere([
        		'ref_fk_object_id' => $modelBracket->id,
        		'ref_fk_object_type_id' => $modelBracket->fk_object_type_id,
        ]);
        
        $mapObject2ObjectSearchModel = new app\models\VAllMappingsUnion();
        $queryMapObject2Object = app\models\VAllMappingsUnion::find();
        $mapObject2ObjectDataProvider = new ActiveDataProvider([
        		'query' => $queryMapObject2Object,
        ]);
        $queryMapObject2Object->andFilterWhere([
        		'filter_ref_fk_object_id' => $modelBracket->id,
        		'filter_ref_fk_object_type_id' => $modelBracket->fk_object_type_id,
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
					'active' => true
				],		
				[
					'label' => Yii::t('app', 'Mapping'),
					'content' => $this->render('../mapobject2object/_index_external', [
							'searchModel' => $mapObject2ObjectSearchModel,
							'dataProvider' => $mapObject2ObjectDataProvider,
					]),
					'active' => false
				],				
			],
		]);
		// Kommentierung pro Object ...}

	    		
	?>  
    
    
</div>