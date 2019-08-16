<?php 
namespace vendor\meta_grid\contactgroup_select;

use yii;
use yii\base\Widget;
use yii\widgets\InputWidget;
use yii\helpers\Html;
use kartik\select2\Select2;

use app\models\ContactGroup;

class ContactGroupSelectWidget extends InputWidget
{
    public $message;
    
    private function getContactGroupAsSupporterList()
    {
    	$contact_group_as_supporterModel = new ContactGroup();
    	$contact_group_as_supporters = $contact_group_as_supporterModel::find()->all();
    	$contact_group_as_supporterList = array();
    	$contact_group_as_supporterList[null] = Html::encode(Yii::t('app', '- Please select -'));
    	foreach($contact_group_as_supporters as $contact_group_as_supporter)
    	{
    		$contact_group_as_supporterList[$contact_group_as_supporter->id] = $contact_group_as_supporter->name;
    	}
    	return $contact_group_as_supporterList;
    }
    
//     private $_assetBundle;
    
    public function init()
    {
        parent::init();
    }

    public function run()
    {
    	parent::run();
    	ContactGroupSelectAsset::register($this->getView());
    	
    	if ($this->hasModel()) {
//     		echo Html::activeDropDownList($this->model, $this->attribute, $this->getContactGroupAsSupporterList(), ['id'=>'name', 'class'=>"form-control"]);

    		echo Select2::widget([
    				'model' => $this->model,
    				'attribute' => $this->attribute,
    				'data' => $this->getContactGroupAsSupporterList(),
    				'options' => ['placeholder' => Yii::t('app', 'Select ...')],
    				'pluginOptions' => [
    						'allowClear' => true
    				],
    		]);
    	} else {
    		echo "No Model given (ContactGroupSelectWidget)!";
    	}
    }
    




}
?>