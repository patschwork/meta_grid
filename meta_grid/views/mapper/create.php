<?php


use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MapObject2Object */

$this->title = Yii::t('app', 'Create {modelClass}', ['modelClass' => Yii::t('app', 'Map Object2 Object')]); 
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Map Object2 Objects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="map-object2-object-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		//'projectList' => $projectList,		// autogeneriert ueber gii/CRUD
							]) ?>

</div>