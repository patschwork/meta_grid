<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper; 

use app\models\VAllObjectsUnion;

/* @var $this yii\web\View */
/* @var $model app\models\GlobalSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vall-objects-union-search">

    <?php $form = ActiveForm::begin([
        // 'action' => ['index'],
        'method' => 'get',
    ]); ?>


    <?php 
	echo $form->field($model, 'name')->label("Search:");
    $dependency = new yii\caching\DbDependency();
    $dependency->sql='SELECT max(log_datetime) FROM "v_LastChangesLog_List"';
	echo $form->field($model, 'object_type_name')->checkboxList(ArrayHelper::map(VAllObjectsUnion::find()->select(['object_type_name'])->distinct()->cache(NULL, $dependency)->asArray()->all(), 'object_type_name', 'object_type_name'))->label("Filter object types");
	
	
    // echo $form->field($model, 'object_type_name');

    // echo $form->field($model, 'listvalue_1');

    // echo $form->field($model, 'id');

    // echo $form->field($model, 'fk_object_type_id');

    // // echo $form->field($model, 'listvalue_2');

    // // echo $form->field($model, 'listkey');

    // echo $form->field($model, 'fk_client_id');

    // echo $form->field($model, 'fk_project_id');
	?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
