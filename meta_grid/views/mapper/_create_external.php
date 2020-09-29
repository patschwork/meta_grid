<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\VAllObjectsUnion;
use yii\helpers\Url;				// Patrick, 2016-01-17
use kartik\depdrop\DepDrop;			// Patrick, 2016-01-17
use yii\bootstrap\Dropdown;

/* @var $this yii\web\View */
/* @var $model app\models\MapObject2Object */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="map-object2-object-form">

	<?php 
		echo "<h2>" . Yii::t('app', 'Mapping object to') . ": $TitleSrcInformation</h2>";
	?>

    <?php $form = ActiveForm::begin(); ?>

	<?php 
		echo $form->errorSummary($model);
	?>

    <?php 
    	// don't use $form->field($model, 'ref_fk_object_type_id_1')->hiddenInput() because this leads in creating a visible label.
		// disabled elements does not work! Post-Submit will then not work anymore
    	echo Html::activeHiddenInput($model, 'ref_fk_object_id_1');
    	echo Html::activeHiddenInput($model, 'ref_fk_object_type_id_1');
		?>
    
	<?php 
	    // Eine DropDown Liste mit allen Objekttypen anzeigen
	    $modelObjectTypes = new app\models\ObjectType();
	    echo $form->field($modelObjectTypes, 'id')->dropDownList($objectTypesList, ['id'=>'allObjects']);
		
		echo Html::label(Yii::t('app', 'Filter on parent project or client'));
		echo Html::dropDownList("filter_on_client_or_project", "yes", [$SrcFilterValueClientOrProjekt => Yii::t('app', "Yes"), -1 => Yii::t('app', "No")], ['id'=>'filter_on_client_or_project', 'class' => "form-control"]);
		
	    // Show all objects in DepDrop (filtered on the choice from dropdownlist above (allObjects)
	    $modelAllObjectsUnion = new VAllObjectsUnion();
       	echo $form->field($modelAllObjectsUnion, 'listkey')->widget(DepDrop::classname(), 
       	[
			'type'=>DepDrop::TYPE_SELECT2,
    		'options'=>['listkey'=>'listvalue_2'],
    		'pluginOptions'=>
       			[
	       			'depends'=>['allObjects', 'filter_on_client_or_project'],
	    			'placeholder'=>'Select...',
	       			'url'=>Url::to(['/mapper/vallobjectsuniondepdrop'])
        		]
        ]);
    
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
