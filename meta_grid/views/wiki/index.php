<style>
.thead_white table thead {
    background-color: #FFFFFF;
}
</style>

<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper; 
use kartik\select2\Select2; 
use vendor\meta_grid\helper\RBACHelper;
use app\models\VWikiSearchinterface;
/* @var $this yii\web\View */
/* @var $searchModel app\models\WikiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Wikis');
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);

// Prevent loading bootstrap.css v3.4.1 (see T212)
\Yii::$app->assetManager->bundles['yii\\bootstrap\\BootstrapAsset'] = [
    'css' => [],
    'js' => []
];

?>
<div class="wiki-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<?php
// Das ist nicht der Yii2-Way, ... @ToDo
if (isset($_GET["searchShow"]))
{
	echo $this->render('_search', ['model' =>$searchModel]);
}
else
{
	echo "<a class='btn btn-default' href='index.php?r=".$_GET["r"]."&searchShow=1'>".Yii::t('app', 'Advanced Search')."</a></br></br>";
}
?>

    <p>
		<?= Yii::$app->user->identity->isAdmin || Yii::$app->User->can('create-wiki')  ? Html::a(
		Yii::t('app', 'Create {modelClass}', ['modelClass' => Yii::t('app', 'Wiki'),]), ['create'], ['class' => 'btn btn-success']) : "" ?>
					</p>

	<?php
	$session = Yii::$app->session;
	
	// Inform user about set perspective_filter
	if (array_key_exists("fk_object_type_id",  $searchModel->attributes) === true && (isset($searchModel->find()->select(['fk_object_type_id'])->one()->fk_object_type_id) === true))
	{
		$fk_object_type_id=$searchModel->find()->select(['fk_object_type_id'])->one()->fk_object_type_id;
		if ($session->hasFlash('perspective_filter_for_' . $fk_object_type_id))
		{	
			echo yii\bootstrap4\Alert::widget([
					'options' => [
									'class' => 'alert-info',
					],
					'body' => $session->getFlash('perspective_filter_for_' . $fk_object_type_id),
			]);
		}		
	}
	
	if ($session->hasFlash('deleteError'))
	{	
		echo yii\bootstrap4\Alert::widget([
				'options' => [
					'class' => 'alert alert-danger alert-dismissable',
				],
				'body' => $session->getFlash('deleteError'),
		]);
	}

	\yii\helpers\Url::remember($url = '', $name = Yii::$app->controller->id."/INDEX");
	?>
	    <?= GridView::widget([
		'tableOptions' => ['id' => 'grid-view-wiki', 'class' => 'table table-striped table-bordered'],
		'dataProvider' => $dataProvider,
		'pager' => [
			'firstPageLabel' => '<span class="glyphicon glyphicon-chevron-left"></span><span class="glyphicon glyphicon-chevron-left"></span>',
			'lastPageLabel' => '<span class="glyphicon glyphicon-chevron-right"></span><span class="glyphicon glyphicon-chevron-right"></span>',
			'prevPageLabel' => '<span class="glyphicon glyphicon-chevron-left"></span>',
			'nextPageLabel' => '<span class="glyphicon glyphicon-chevron-right"></span>',
			'maxButtonCount' => 15,
			'class' => 'yii\bootstrap4\LinkPager'
		],
		'layout' => "{pager}\n{summary}{items}\n{pager}",
       	'rowOptions' => function ($model, $key, $index, $grid) {
       		$controller = Yii::$app->controller->id;
       		return [
       				'ondblclick' => 'location.href="'
       				. Yii::$app->urlManager->createUrl([$controller . '/view','id'=>$key])
       				. '"',
       		];
       	},
		'options' => [
			'class' => 'thead_white',
		],    
        'filterModel' => $searchModel,
        'columns' => [
        	['class' => 'yii\grid\ActionColumn', 'contentOptions'=>[ 'style'=>'white-space: nowrap;']
            ,
				'template' => RBACHelper::filterActionColumn_meta_grid('{view} {update} {delete}'),
            ],
        	
        	['class' => 'yii\grid\SerialColumn'],

            'name:ntext',
            [
             'label' => Yii::t('app', 'Preview'),
             'value' => function($model) {
             		return html_preview($model->description);
             		},
			'format' => 'html',
			'filter' => Html::activeTextInput($searchModel, 'description', ['class' => 'form-control', 'placeholder' => 'Suche...']),
            ],
            // [
            //  'label' => Yii::t('app', 'Client'),
            //  'value' => function($model) {
            //  		return $model->fk_project_id == "" ? $model->fk_project_id : ($model->fkProject->fkClient === NULL ? Yii::t('app', "Can't lookup the client name (for project {fk_project_id})", ['fk_project_id' => $model->fk_project_id]) : $model->fkProject->fkClient->name);
            //  		},
            //  		'filter' => Select2::widget([
            //  				'model' => $searchModel,
            //  				'attribute' => 'fk_client_id',
            //  				'data' => ArrayHelper::map(VWikiSearchinterface::find()->select(['fk_client_id', 'client_name'])->distinct()->asArray()->all(), 'fk_client_id', 'client_name'),
            //  				'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_client_id'],
            //  				'pluginOptions' => [
            //  						'allowClear' => true
            //  				],
            //  		]),
			// 'contentOptions' => function ($model, $key, $index, $column) {
			// 	try {
			// 		return $model->fkProject->fkClient === NULL ? ['style' => 'color: red'] : [];
			// 	} catch (\Exception $e) {
			// 		return ['style' => 'color: red'];
			// 	}	
			//  },
            // ],
            // [
            //  'label' => Yii::t('app', 'Project'),
            //  'value' => function($model) {
            //  		return $model->fk_project_id == "" ? $model->fk_project_id : (isset($_GET["searchShow"]) ? $model->fkProject->name . ' [' . $model->fk_project_id . ']' : ($model->fkProject=== NULL ? Yii::t('app', "Can't lookup the {relFieldname} name (for id {this_id})", ['relFieldname' => 'fkProject', 'this_id' => $model->fk_project_id]) : $model->fkProject->name));
            //  		},
            // 'filter' => Select2::widget([
            // 		'model' => $searchModel,
            // 		'attribute' => 'fk_project_id',
            // 		'data' => ArrayHelper::map(VWikiSearchinterface::find()->select(['fk_project_id', 'project_name'])->distinct()->asArray()->all(), 'fk_project_id', 'project_name'),
            // 		'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_fkProject'],
            // 		'pluginOptions' => [
            // 				'allowClear' => true
            // 		],
			// ]),
			// 'contentOptions' => function ($model, $key, $index, $column) {
			//      return $model->fkProject === NULL ? ['style' => 'color: red'] : [];
			//  },
            // ],
            // [
            //  'label' => Yii::t('app', 'User'),
            //  'value' => function($model) {
            //  		return $model->fk_user_id == "" ? $model->fk_user_id : (isset($_GET["searchShow"]) ? $model->fkUser->name . ' [' . $model->fk_user_id . ']' : ($model->fkUser=== NULL ? Yii::t('app', "Can't lookup the {relFieldname} name (for id {this_id})", ['relFieldname' => 'fkUser', 'this_id' => $model->fk_user_id]) : $model->fkUser->name));
            //  		},
            // 'filter' => Select2::widget([
            // 		'model' => $searchModel,
            // 		'attribute' => 'fk_user_id',
            // 		'data' => ArrayHelper::map(app\models\User::find()->asArray()->all(), 'id', 'name'),
            // 		'options' => ['placeholder' => Yii::t('app', 'Select ...'), 'id' =>'select2_fkUser', 'multiple' => true],
            // 		'pluginOptions' => [
            // 				'allowClear' => true
            // 		],
			// ]),
			// 'contentOptions' => function ($model, $key, $index, $column) {
			//      return $model->fkUser === NULL ? ['style' => 'color: red'] : [];
			//  },
            // ],
        ],
    ]); ?>

	<?php 	$Utils = new \vendor\meta_grid\helper\Utils();
	if ($Utils->get_app_config("floatthead_for_gridviews") == 1)
	{
		\bluezed\floatThead\FloatThead::widget(
			[
				'tableId' => 'grid-view-wiki', 
				'options' => [
					'top'=>'50'
				]
			]
		);
	}
	?>

	
</div>



<?php
/**
 * Erzeugt eine HTML-Vorschau (kürzt Text-Inhalt auf $maxChars) und gibt gültiges HTML zurück.
 * @param string $html Input-HTML (Fragment oder vollständiges Dokument)
 * @param int $maxChars Maximale Anzahl Textzeichen in der Vorschau
 * @param string $ellipsis Text am Ende, z.B. '...'
 * @return string gültiges HTML-Fragment
 */
function html_preview(string $html, int $maxChars = 200, string $ellipsis = '...'): string {
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    // Wenn Fragment, wickele in einen container
    $wrapped = '<!DOCTYPE html><html><body><div id="preview-root">'. $html .'</div></body></html>';
    $dom->loadHTML(mb_convert_encoding($wrapped, 'HTML-ENTITIES', 'UTF-8'));
    libxml_clear_errors();

    $root = $dom->getElementById('preview-root');
    if (!$root) return '';

    $chars = 0;
    $done = false;

    // Rekursiver Klon mit Kürzen
    $cloneDoc = new DOMDocument();
    $container = $cloneDoc->createElement('div');
    $cloneDoc->appendChild($container);

    $appendNode = function($node, $targetParent) use (&$appendNode, &$chars, $maxChars, $ellipsis, &$done, $cloneDoc) {
        if ($done) return;

        if ($node->nodeType === XML_TEXT_NODE) {
            $text = $node->wholeText;
            $remaining = $maxChars - $chars;
            if ($remaining <= 0) {
                $done = true;
                return;
            }
            if (mb_strlen($text) <= $remaining) {
                $chars += mb_strlen($text);
                $targetParent->appendChild($cloneDoc->createTextNode($text));
            } else {
                $part = mb_substr($text, 0, $remaining);
                // optional: auf Wortgrenzen schneiden
                $part = preg_replace('/\s+\S*$/u', '', $part);
                $targetParent->appendChild($cloneDoc->createTextNode($part . $ellipsis));
                $chars += mb_strlen($part);
                $done = true;
            }
        } elseif ($node->nodeType === XML_ELEMENT_NODE) {
            // Erzeuge Element-Kopie (ohne Events, nur Attribute)
            $newEl = $cloneDoc->createElement($node->nodeName);
            // Kopiere Attribute
            if ($node->hasAttributes()) {
                foreach ($node->attributes as $attr) {
                    // Sicher: attribut-werte als string
                    $newEl->setAttribute($attr->nodeName, $attr->nodeValue);
                }
            }
            $targetParent->appendChild($newEl);

            // Selbst schließende/void tags: keine Kinder behandeln
            $voidTags = ['area','base','br','col','embed','hr','img','input','link','meta','param','source','track','wbr'];
            if (in_array(strtolower($node->nodeName), $voidTags, true)) return;

            // Iteriere Kinder
            foreach ($node->childNodes as $child) {
                if ($done) break;
                $appendNode($child, $newEl);
            }
            // Falls keine Kinder hinzugefügt wurden und Tag leer ist, bleibt es korrekt geöffnet/geschlossen.
        } else {
            // Ignoriere Kommentare, CDATA etc.
            return;
        }
    };

    // Traverse Kinder von root in original DOM und baue in cloneDoc
    foreach ($root->childNodes as $child) {
        if ($done) break;
        $appendNode($child, $container);
    }

    // Ergebnis: innerHTML des container zurückgeben
    $out = '';
    foreach ($container->childNodes as $n) {
        $out .= $cloneDoc->saveHTML($n);
    }
    return $out;
}
	?>