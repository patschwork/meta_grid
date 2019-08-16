<?php


use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Sourcesystem */

$this->title = Yii::t('app', 'Create {modelClass}', ['modelClass' => Yii::t('app', 'Sourcesystem')]); 
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sourcesystems'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sourcesystem-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		//'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
		'object_typeList' => $object_typeList,		// autogeneriert ueber gii/CRUD
'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
'contact_group_as_supporterList' => $contact_group_as_supporterList,		// autogeneriert ueber gii/CRUD
					]) ?>

</div>