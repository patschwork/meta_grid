<?php


use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DbDatabase */

$this->title = Yii::t('app', 'Create {modelClass}', ['modelClass' => Yii::t('app', 'Db Database')]); 
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Db Databases'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="db-database-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		//'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
		'object_typeList' => $object_typeList,		// autogeneriert ueber gii/CRUD
'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
'toolList' => $toolList,		// autogeneriert ueber gii/CRUD
'deleted_statusList' => $deleted_statusList,		// autogeneriert ueber gii/CRUD
					]) ?>

</div>