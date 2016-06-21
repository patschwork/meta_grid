<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SourcesystemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Sourcesystems');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sourcesystem-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<?php
// Das ist nicht der Yii2-Way, ... @ToDo
if (isset($_GET["searchShow"]))
{
	echo $this->render('_search', ['model' =>$searchModel]);
}
else
{
	echo "<a class='btn btn-default' href='index.php?r=".$_GET["r"]."&searchShow=1'>Advanced Search</a></br></br>";
}
?>

    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Sourcesystem',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
             'label' => 'Client',
             'value' => function($model) {
             		return $model->fk_project_id == "" ? $model->fk_project_id : $model->fkProject->fkClient->name;
             		},
            ],
            [
             'label' => 'Project',
             'value' => function($model) {
             		return $model->fk_project_id == "" ? $model->fk_project_id : (isset($_GET["searchShow"]) ? $model->fkProject->name . ' [' . $model->fk_project_id . ']' : $model->fkProject->name);
             		},
            ],
            'name:ntext',
            'description:html',
            [
             'label' => 'Contact Group As Supporter',
             'value' => function($model) {
             	// Achtung, an diesem Feld musste ich manuell korrigieren und es ist noch nicht im CRUD automatisch abgefangen! @ToDo	
            	return $model->fk_contact_group_id_as_supporter == "" ? $model->fk_contact_group_id_as_supporter : (isset($_GET["searchShow"]) ? $model->fkContactGroupIdAsSupporter->name . ' [' . $model->fk_contact_group_id_as_supporter . ']' : $model->fkContactGroupIdAsSupporter->name);
             		},
            ],
//             [
//             'label' => 'Contact Group As Supporter',
//             'value' => function($model) {
//             	return $model->fk_contact_group_id_as_supporter == "" ? $model->fk_contact_group_id_as_supporter : (isset($_GET["searchShow"]) ? $model->fkContactGroupAsSupporter->name . ' [' . $model->fk_contact_group_id_as_supporter . ']' : $model->fkContactGroupAsSupporter->name);
//             },
//             ],
            
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
