<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\VAllObjectsUnion;
use yii\helpers\Url;				// Patrick, 2016-01-17
use kartik\depdrop\DepDrop;			// Patrick, 2016-01-17


/* @var $this yii\web\View */
/* @var $model app\models\MapObject2Object */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="map-object2-object-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php 
    	// $form->field($model, 'ref_fk_object_type_id_1')->hiddenInput() nicht verwenden, da es einen unnötigen Label erzeugt.
    	// disabled elemente funktionieren nicht! Post-Submit klappt dann nicht mehr.
    	echo Html::activeHiddenInput($model, 'ref_fk_object_id_1');
    	echo Html::activeHiddenInput($model, 'ref_fk_object_type_id_1');
    ?>
    
    <?php 
	    // Eine DropDown Liste mit allen Objekttypen anzeigen
	    $modelObjectTypes = new app\models\ObjectType();
	    echo $form->field($modelObjectTypes, 'id')->dropDownList($objectTypesList, ['id'=>'allObjects']);
    
	    // Alle Objekte in DepDrop anzeigen (gefiltert auf die Auswahl aus der Dropdownliste oberhalb (allObjects)
	    $modelAllObjectsUnion = new VAllObjectsUnion();
       	echo $form->field($modelAllObjectsUnion, 'listkey')->widget(DepDrop::classname(), 
       	[
			'type'=>DepDrop::TYPE_SELECT2,
    		'options'=>['listkey'=>'listvalue_2'],
    		'pluginOptions'=>
       			[
	       			'depends'=>['allObjects'],
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
