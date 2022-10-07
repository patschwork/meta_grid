<?php


use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Tag */

$this->title = Yii::t('app', 'Create {modelClass}', ['modelClass' => Yii::t('app', 'Tag')]); 
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tags'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tag-create">

	<h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
		//'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
		'object_typeList' => $object_typeList,		// autogeneriert ueber gii/CRUD
'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
'userList' => $userList,		// autogeneriert ueber gii/CRUD
'projectListDisables' => $projectListDisables,		// autogeneriert ueber gii/CRUD
'mode' => $mode,		// autogeneriert ueber gii/CRUD
					]) ?>

</div>