<?php


use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ObjectDependsOn */

$this->title = Yii::t('app', 'Create {modelClass}', ['modelClass' => Yii::t('app', 'Object Depends On')]); 
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Object Depends Ons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="object-depends-on-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		//'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
							]) ?>

</div>