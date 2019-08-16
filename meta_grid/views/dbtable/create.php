<?php


use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DbTable */

$this->title = Yii::t('app', 'Create {modelClass}', ['modelClass' => Yii::t('app', 'Db Table')]); 
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Db Tables'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="db-table-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		//'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
		'object_typeList' => $object_typeList,		// autogeneriert ueber gii/CRUD
'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
'db_table_contextList' => $db_table_contextList,		// autogeneriert ueber gii/CRUD
'db_table_typeList' => $db_table_typeList,		// autogeneriert ueber gii/CRUD
					]) ?>

</div>