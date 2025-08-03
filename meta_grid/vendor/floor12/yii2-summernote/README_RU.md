# yii2-summernote

Виджет WYSIWYG-редактора [Summernote](https://summernote.org/ ) для фреймворка [Yii2](https://yiiframework.ru/), c
включенными ассетами и поправлеными багами в js-библетеках.

[![Latest Stable Version](http://poser.pugx.org/floor12/yii2-summernote/v)](https://packagist.org/packages/floor12/yii2-summernote)
[![Total Downloads](http://poser.pugx.org/floor12/yii2-summernote/downloads)](https://packagist.org/packages/floor12/yii2-summernote)
[![Latest Unstable Version](http://poser.pugx.org/floor12/yii2-summernote/v/unstable)](https://packagist.org/packages/floor12/yii2-summernote)
[![License](http://poser.pugx.org/floor12/yii2-summernote/license)](https://packagist.org/packages/floor12/yii2-summernote)
[![PHP Version Require](http://poser.pugx.org/floor12/yii2-summernote/require/php)](https://packagist.org/packages/floor12/yii2-summernote)


![Пример работы виджета](https://floor12.net/en/files/default/image?hash=81ef4ae8ce4cf1c288ad9dd78ff72ec2&width=1500)

## Установка

Устанавливаем виджет через composer:
Выполняем команду

```bash
$ composer require floor12/yii2-summernote
```

## Использование

Простейший пример без формы и модели:

```php
use floor12\summernote\Summernote;

echo Summernote::widget(['name' => 'some_field'])
```

Пример работы с `ActiveForm` и `ActiveRecord` моделью:

```php
$form = ActiveForm::begin();

echo $form->field($model, 'content_ru')
    ->widget(Summernote::class);
             
ActiveForm::end();
```

Пример интеграции с [модулем файлов](https://github.com/floor12/yii2-module-files), чтобы перехватывать загружаемые в
текст изображения, сохранять их отдельно и затем
использовать.

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

![Пример работы](https://floor12.net/en/files/default/image?hash=868c9752a86820692dabcb334f766df7&width=1500)