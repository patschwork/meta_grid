<?php


use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DbTableField */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Db Table Field',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Db Table Fields'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="db-table-field-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		//'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
		'object_typeList' => $object_typeList,		// autogeneriert ueber gii/CRUD
'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
'db_tableList' => $db_tableList,		// autogeneriert ueber gii/CRUD
        
    ]) ?>

</div>
