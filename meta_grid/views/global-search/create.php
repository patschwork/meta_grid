<?php


use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\VAllObjectsUnion */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Vall Objects Union',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vall Objects Unions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vall-objects-union-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		//'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
		'object_typeList' => $object_typeList,		// autogeneriert ueber gii/CRUD
'clientList' => $clientList,		// autogeneriert ueber gii/CRUD
'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
					]) ?>

</div>