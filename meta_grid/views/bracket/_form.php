		
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget; 
/* @var $this yii\web\View */
/* @var $model app\models\Bracket */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bracket-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
<!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'uuid') ?>  -->

<!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'fk_object_type_id')->textInput() ?>  -->

	<?php
		// autogeneriert ueber gii/CRUD
		echo $form->field($model, 'fk_project_id')->dropDownList($projectList, ['id'=>'name']);
	?>
 <!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'fk_project_id')->textInput() ?>  -->

    <?= $form->field($model, 'name') ?>

	<?php
		echo $form->field($model, 'description')->widget(\yii\redactor\widgets\Redactor::className());	?>
 <!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'description') ?>  -->

	<?php
		// autogeneriert ueber gii/CRUD
		echo $form->field($model, 'fk_attribute_id')->widget(\vendor\meta_grid\attribute_select\AttributeSelectWidget::className());
	?>
 <!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'fk_attribute_id')->textInput() ?>  -->

	<?php
		// autogeneriert ueber gii/CRUD
		echo $form->field($model, 'fk_object_type_id_as_searchFilter')->dropDownList($object_type_as_searchFilterList, ['id'=>'name', 'options' => [ $model->fk_object_type_id_as_searchFilter => ['selected ' => true]]]);
	?>
 <!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'fk_object_type_id_as_searchFilter')->textInput() ?>  -->

	<?php
		// autogeneriert ueber gii/CRUD
		echo $form->field($model, 'fk_object_persistence_method_id')->dropDownList($object_persistence_methodList, ['id'=>'name']);
	?>
 <!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'fk_object_persistence_method_id')->textInput() ?>  -->

	<?php
		// autogeneriert ueber gii/CRUD
		echo $form->field($model, 'fk_datamanagement_process_id')->dropDownList($datamanagement_processList, ['id'=>'name']);
	?>
 <!--  	// automatisch auskommentiert ueber gii/CRUD    <?= $form->field($model, 'fk_datamanagement_process_id')->textInput() ?>  -->


	<div class="row">
	
		<div class="panel panel-default">
	        <div class="panel-heading"><h4><i class="glyphicon glyphicon-th-list"></i> SearchPattern</h4></div>
	        <div class="panel-body">
	             <?php DynamicFormWidget::begin([
	                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
	                'widgetBody' => '.container-items', // required: css class selector
	                'widgetItem' => '.item', // required: css class
	                // 'limit' => 4, // the maximum times, an element can be cloned (default 999)
	                'min' => 1, // 0 or 1 (default 1)
	                'insertButton' => '.add-item', // css class
	                'deleteButton' => '.remove-item', // css class
	                'model' => $modelsBracketSearchPattern[0],
	                'formId' => 'dynamic-form',
	                'formFields' => [
	                    'searchPattern',
	                ],
	            ]); ?>
	
	            <div class="container-items"><!-- widgetContainer -->
	            <?php foreach ($modelsBracketSearchPattern as $i => $modelBracketSearchPattern): ?>
	                <div class="item panel panel-default"><!-- widgetBody -->
	                    <div class="panel-heading">
	                        <h3 class="panel-title pull-left">SearchPattern</h3>
	                        <div class="pull-right">
	                            <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
	                            <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
	                        </div>
	                        <div class="clearfix"></div>
	                    </div>
	                    <div class="panel-body">
	                        <?php
	                            // necessary for update action.
	                            if (! $modelBracketSearchPattern->isNewRecord) {
	                                echo Html::activeHiddenInput($modelBracketSearchPattern, "[{$i}]fk_bracket_id");
	                            }
	                        ?>
	                        <?= $form->field($modelBracketSearchPattern, "[{$i}]searchPattern")->textInput(['maxlength' => true]) ?>
	                    </div>
	                </div>
	            <?php endforeach; ?>
	            </div>
	            <?php DynamicFormWidget::end(); ?>
	        </div>
	    </div>
	
	</div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
