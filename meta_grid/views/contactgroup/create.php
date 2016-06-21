<?php


use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ContactGroup */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Contact Group',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Contact Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contact-group-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		//'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
		'object_typeList' => $object_typeList,		// autogeneriert ueber gii/CRUD
'clientList' => $clientList,		// autogeneriert ueber gii/CRUD
        
    ]) ?>

</div>
