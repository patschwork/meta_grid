<style>
a.anchor {
    display: block;
    position: relative;
    top: -250px;
    visibility: hidden;
}
</style>

<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget; 
use yii\helpers\Url;				// Patrick, 2020-03-21
use kartik\depdrop\DepDrop;			// Patrick, 2020-03-21
/* @var $this yii\web\View */
/* @var $model app\models\Bracket */
/* @var $form yii\widgets\ActiveForm */



// Prevent loading bootstrap.css v3.4.1 (see T212)
\Yii::$app->assetManager->bundles['yii\\bootstrap\\BootstrapAsset'] = [
    'css' => [],
    'js' => []
];
?>


<div class="dbtablefieldmultiple-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

	<div class="form-group">
        <?= Html::submitButton($modelDbTable->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $modelDbTable->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>


	<?php
		// autogeneriert ueber gii/CRUD
        echo $form->field($modelDbTable, 'fk_project_id')->dropDownList($projectList, ['id'=>'name_project']);
	?>

    <?= $form->field($modelDbTable, 'name') ?>

	<?php
			// echo $form->field($modelDbTable, 'description')->widget(floor12\summernote\Summernote::class);  
			echo $form->field($modelDbTable, 'description')->widget(\yii\redactor\widgets\Redactor::className());  
	?>

    <?= $form->field($modelDbTable, 'location') ?>

    <?php
		// autogeneriert ueber gii/CRUD
		// echo $form->field($modelDbTable, 'fk_db_table_context_id')->dropDownList($db_table_contextList, ['id'=>'name']);
		
		echo $form->field($modelDbTable, 'fk_db_table_context_id')->widget(DepDrop::classname(), 
		[
			'type'=>DepDrop::TYPE_SELECT2
			,'data'=>$db_table_contextList
			,'select2Options' => ['pluginOptions' => ['allowClear' => true]]
			,'pluginOptions'=>
			[
				'depends'=>['name_project'],
				'placeholder'=>Yii::t('app', 'Select...'),
				'url'=>Url::to(['/dbtablefieldmultipleedit/dbtablecontextlistdepdrop'])
			]
		]);
    ?>

	<?php
			// autogeneriert ueber gii/CRUD
			echo $form->field($modelDbTable, 'fk_db_table_type_id')->dropDownList($db_table_typeList, ['id'=>'name']);
	?>
	
	<?php
		// autogeneriert ueber gii/CRUD
		echo $form->field($modelDbTable, 'fk_deleted_status_id')->dropDownList($deleted_statusList, ['id'=>'name']);
	?>

	<div class="row">
	
		<div class="card" style="width: 100%; margin: 10px;">
	        <div class="card-body">
	             <?php DynamicFormWidget::begin([
	                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
	                'widgetBody' => '.container-items', // required: css class selector
	                'widgetItem' => '.item', // required: css class
	                // 'limit' => 4, // the maximum times, an element can be cloned (default 999)
	                'min' => 1, // 0 or 1 (default 1)
	                'insertButton' => '.add-item', // css class
	                'deleteButton' => '.remove-item', // css class
	                'model' => $modelsDbTableField[0],
	                'formId' => 'dynamic-form',
	                'formFields' => [
						'name',
						'description',
						'datatype',
	                ],
	            ]); ?>
	
	<button type="button" class="add-item btn btn-success btn-sm float-right"><i class="glyphicon glyphicon-plus"></i><?= Yii::t('app', 'Add field') ?> </button>	
	<div class="card-heading">
		<h4><i class="glyphicon glyphicon-th-list"></i> <?= Yii::t('app', 'Fields') ?>
		</h4>
	</div>


        <div class="card-body">
            <div class="container-items"><!-- widgetBody -->

	            <?php foreach ($modelsDbTableField as $i => $modelDbTableField): ?>
					<a class="anchor" id="<?= $modelDbTableField->id ?>"></a>
	                <div class="item card card-default"><!-- widgetBody -->
	                    <?php
							//     name="card_heading_name" 
							// and name="field_details_h3_name" 
							// are needed for JS @web/js/dbtablefieldmultipleedit.js
						?>
						<div class="card-heading" name="panel_heading_name" id="phn_<?= $modelDbTableField->id ?>">
						<h3 class="card-title float-left" name="field_details_h3_name"><?= Yii::t('app', 'Field details') ?></h3>


						<div class="float-right">
								<?php
									echo Html::a(Yii::t('app', 'Add') . "  " . '<i class="glyphicon glyphicon-comment"></i>', ['objectcomment/createexternal', 'ref_fk_object_id' => $modelDbTableField->id, 'ref_fk_object_type_id' => $modelDbTableField->fk_object_type_id],['name' => 'btnCreateNewComment', 'class' => 'btn btn-primary btn-xs', 'id' => 'btnCreateNewComment' . $modelDbTableField->id], 
									[
										'data' => [
										'method' => 'post',
										],
										]);	
								?>

	                            <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
	                            <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
	                        </div>
	                        <div class="clearfix"></div>
	                    </div>
	                    <div class="card-body">
	                        <?php
	                            // necessary for update action.
	                            if (! $modelDbTableField->isNewRecord) {
	                                echo Html::activeHiddenInput($modelDbTableField, "[{$i}]fk_db_table_id");
	                                echo Html::activeHiddenInput($modelDbTableField, "[{$i}]id"); // WICHTIG für Deltaabgleich (Performance ***1***)
	                            }
	                        ?>

						<div class="row">
                            <div class="col-sm-2">
								<?php 
								 	echo $form->field($modelDbTableField, "[{$i}]is_PrimaryKey")->checkbox(['class' => 'checkBox_is_PrimaryKey']); // {T60}
    							?>
                            </div>
                            <div class="col-sm-2">
								<?php 
									echo $form->field($modelDbTableField, "[{$i}]is_BusinessKey")->checkbox(['class' => 'checkBox_is_BusinessKey']); // {T60}
								?>	
							</div>
                            <div class="col-sm-2">
								<?php 
									echo $form->field($modelDbTableField, "[{$i}]is_GDPR_relevant")->checkbox(['class' => 'checkBox_is_GDPR_relevant']); // {T60}
								?>	
							</div>
						</div><!-- end:row -->
						
						<div class="row">
                            <div class="col-sm-6">
		                        <?php echo $form->field($modelDbTableField, "[{$i}]name")->textInput(['maxlength' => true]); ?>
                            </div>
                            <div class="col-sm-6">
								<?php echo $form->field($modelDbTableField, "[{$i}]datatype"); ?>
                            </div>
                        </div><!-- end:row -->

							<?php // echo $form->field($modelDbTableField, "[{$i}]description")->widget(floor12\summernote\Summernote::class); ?>
							<?php echo $form->field($modelDbTableField, "[{$i}]description")->widget(\yii\redactor\widgets\Redactor::className()); ?>


	                    </div>
	                </div>
	            <?php endforeach; ?>
				<?php $i=-100; ?>
	            </div>
	            <?php DynamicFormWidget::end(); ?>
	        </div>
	    </div>
	
	</div>

    <div class="form-group">
        <?= Html::submitButton($modelDbTable->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $modelDbTable->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>



<?php

$this->registerJsFile(
    '@web/js/yii2-dynamic-form-patched-4-metagrid.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

$this->registerJsFile(
    '@web/js/dbtablefieldmultipleedit.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);


?>