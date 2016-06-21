<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\Url;				// Patrick, 2016-01-17
use kartik\depdrop\DepDrop;			// Patrick, 2016-01-17

/* @var $this yii\web\View */
/* @var $model app\models\Contact */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contact-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    
		// autogeneriert ueber gii/CRUD
		echo $form->field($model, 'fk_client_id')->dropDownList($clientList, ['id'=>'name__fk_client_id']);
	
		// autogeneriert ueber gii/CRUD
// 		echo $form->field($model, 'fk_contact_group_id')->dropDownList($contact_groupList, ['id'=>'name']);
		
		echo $form->field($model, 'fk_contact_group_id')->widget(DepDrop::classname(), 
		[
			'data'=>\yii\helpers\ArrayHelper::map(app\models\Client::find()->asArray()->all(), 'id', 'name'),
			'options'=>['id'=>'name__fk_contact_group_id'],
			'pluginOptions'=>
			[
				'depends'=>['name__fk_client_id'],
				'initialize' => true,
				'placeholder'=>'Select...',
				'url'=>Url::to(['/contact/contactgroupdepdrop'])
			]
		]);	

	?>


    <?= $form->field($model, 'givenname')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'surname')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'email')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'phone')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'mobile')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'ldap_cn')->textarea(['rows' => 6]) ?>

	<?php
		$form->field($model, 'description')->widget(\yii\redactor\widgets\Redactor::className());	?>
     <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
