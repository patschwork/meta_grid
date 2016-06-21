<?php


use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DbTableContext */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Db Table Context',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Db Table Contexts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="db-table-context-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		//'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
		'object_typeList' => $object_typeList,		// autogeneriert ueber gii/CRUD
'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
        
    ]) ?>

</div>
