Bootstrap4 glyphicons for Yii2
==============================
If you want use Glyphicons in your Yii2 project with Bootstrap 4 (https://github.com/yiisoft/yii2-bootstrap4)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).


Add the repo to your composer.json.

```json
"repositories":[
    ...
    {
        "type": "git",
        "url": "https://xtetis@bitbucket.org/xtetis/yii2-bootstrap4-glyphicons.git"
    }
    ...
]
```

Either run

```
php composer.phar require --prefer-dist xtetis/yii2-bootstrap4-glyphicons "*"
```

or add

```
"xtetis/yii2-bootstrap4-glyphicons": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your Layout code:

```php
<?php
use xtetis\bootstrap4glyphicons\assets\GlyphiconAsset;
GlyphiconAsset::register($this);
?>```


