# yii2-summernote

The [Summernote](https://summernote.org/) WYSIWYG-editor [Yii-widget](https://yiiframework.ru/), with all included assets.

[![Latest Stable Version](http://poser.pugx.org/floor12/yii2-summernote/v)](https://packagist.org/packages/floor12/yii2-summernote)
[![Total Downloads](http://poser.pugx.org/floor12/yii2-summernote/downloads)](https://packagist.org/packages/floor12/yii2-summernote)
[![Latest Unstable Version](http://poser.pugx.org/floor12/yii2-summernote/v/unstable)](https://packagist.org/packages/floor12/yii2-summernote)
[![License](http://poser.pugx.org/floor12/yii2-summernote/license)](https://packagist.org/packages/floor12/yii2-summernote)
[![PHP Version Require](http://poser.pugx.org/floor12/yii2-summernote/require/php)](https://packagist.org/packages/floor12/yii2-summernote)


![Widget example](https://floor12.net/en/files/default/image?hash=81ef4ae8ce4cf1c288ad9dd78ff72ec2&width=1500)
## Installation


Install the widget via composer:
Execute the command

```bash
$ composer require floor12/yii2-summernote
```

## Usage

The simplest example:

```php
use floor12\summernote\Summernote;

echo Summernote::widget(['name' => 'some_field'])
```


The`ActiveForm` and `ActiveRecord` model example:

```php
$form = ActiveForm::begin();

echo $form->field($model, 'content_ru')
    ->widget(Summernote::class);
             
ActiveForm::end();
```


An example of integrating with [my files module](https://github.com/floor12/yii2-module-files) to intercept editors uploads, save them separately and then use in the editor.

```php
$form = ActiveForm::begin();

echo $form->field($model, 'content_ru')
    ->widget(Summernote::class, [
        'fileField' => 'imagesDesktop',
        'fileModelClass' => $model::class
    ]);

echo $form->field($model, 'imagesDesktop')
    ->widget(FileInputWidget::class);

ActiveForm::end();
```

![Working example](https://floor12.net/en/files/default/image?hash=868c9752a86820692dabcb334f766df7&width=1500)