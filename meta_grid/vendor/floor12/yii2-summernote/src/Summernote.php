<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 2019-02-18
 * Time: 11:28
 */

namespace floor12\summernote;

use floor12\files\logic\ClassnameEncoder;
use yii\helpers\Html;
use yii\widgets\InputWidget;

class Summernote extends InputWidget
{
    /** @var ?string */
    public $fileField = null;
    /** @var ?string */
    public $fileModelClass = null;
    /** @var array */
    public $options = [];
    /** @var array */
    public $clientOptions = [];
    /** @var array */
    private $defaultOptions = ['class' => 'form-control'];

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        $this->options = array_merge($this->defaultOptions, $this->options);
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerAssets();
        echo $this->hasModel()
            ? Html::activeTextarea($this->model, $this->attribute, $this->options)
            : Html::textarea($this->name, $this->value, $this->options);
        if ($this->fileField && $this->fileModelClass) {
            $classname = new ClassnameEncoder($this->fileModelClass);
            $this->getView()->registerJs("summernoteParams.fileField = '{$this->fileField}'");
            $this->getView()->registerJs("summernoteParams.fileClass = '{$classname}'");
        } else {
            $this->getView()->registerJs("summernoteParams.callbacks = {}");
        }
        $this->getView()->registerJs('jQuery( "#' . $this->options['id'] . '" ).summernote(summernoteParams);');


    }

    private function registerAssets()
    {
        SummernoteAsset::register($this->getView());
    }
}