<?php 
namespace vendor\meta_grid\attribute_select;

use yii;
use yii\base\Widget;
use yii\widgets\InputWidget;
use yii\helpers\Html;
use kartik\select2\Select2;

use app\models\Attribute;

class AttributeSelectWidget extends InputWidget
{
    public $message;
    
    private function getAttributeList()
    {
    	$model = new Attribute();
    	$items = $model::find()->all();
    	$itemList = array();
    	$itemList[null] = Html::encode(Yii::t('app', '- Please select -'));
    	foreach($items as $item)
    	{
    		$itemList[$item->id] = $item->name;
    	}
    	return $itemList;
    }
    
//     private $_assetBundle;
    
    public function init()
    {
        parent::init();
    }

    public function run()
    {
    	parent::run();
    	AttributeSelectAsset::register($this->getView());
    	
    	if ($this->hasModel()) {

    		echo Select2::widget([
    				'model' => $this->model,
    				'attribute' => $this->attribute,
    				'data' => $this->getAttributeList(),
    				'options' => ['placeholder' => Yii::t('app', 'Select ...')],
    				'pluginOptions' => [
    						'allowClear' => true
    				],
    		]);
    	} else {
    		echo "No Model given (AttributeSelectWidget)!";
    	}
    }

}
?>