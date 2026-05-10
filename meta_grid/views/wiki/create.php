<?php


use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Wiki */

$this->title = Yii::t('app', 'Create {modelClass}', ['modelClass' => Yii::t('app', 'Wiki')]); 
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Wikis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wiki-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
		//'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
		'object_typeList'               => $object_typeList,		// autogeneriert ueber gii/CRUD
		'projectList'                   => $projectList,		// autogeneriert ueber gii/CRUD
		'userList'                      => $userList,		// autogeneriert ueber gii/CRUD
		'modalparent'                   => $modalparent,
		'refreshfield'                  => $refreshfield,
		'projectListDisables'           => $projectListDisables,
		'mode'                          => $mode,		
	]) ?>

</div>