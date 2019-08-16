@echo off
SET override=1
SET interactive=0

SET controller=Client
SET model=%controller%
call yii.bat gii/crud --overwrite=%override% --interactive=%interactive% --controllerClass=app\controllers\%controller%Controller --enableI18N=1 --enablePjax=1 --modelClass=app\models\%model% --searchModelClass=app\models\%model%Search --template=myCrud --appconfig=config/console.php

SET controller=Project
SET model=%controller%
call yii.bat gii/crud --overwrite=%override% --interactive=%interactive% --controllerClass=app\controllers\%controller%Controller --enableI18N=1 --enablePjax=1 --modelClass=app\models\%model% --searchModelClass=app\models\%model%Search --template=myCrud --appconfig=config/console.php

SET controller=Keyfigure
SET model=%controller%
call yii.bat gii/crud --overwrite=%override% --interactive=%interactive% --controllerClass=app\controllers\%controller%Controller --enableI18N=1 --enablePjax=1 --modelClass=app\models\%model% --searchModelClass=app\models\%model%Search --template=myCrud --appconfig=config/console.php

SET controller=Attribute
SET model=%controller%
call yii.bat gii/crud --overwrite=%override% --interactive=%interactive% --controllerClass=app\controllers\%controller%Controller --enableI18N=1 --enablePjax=1 --modelClass=app\models\%model% --searchModelClass=app\models\%model%Search --template=myCrud --appconfig=config/console.php

SET controller=Sourcesystem
SET model=%controller%
call yii.bat gii/crud --overwrite=%override% --interactive=%interactive% --controllerClass=app\controllers\%controller%Controller --enableI18N=1 --enablePjax=1 --modelClass=app\models\%model% --searchModelClass=app\models\%model%Search --template=myCrud --appconfig=config/console.php

SET controller=Dbdatabase
SET model=DbDatabase
call yii.bat gii/crud --overwrite=%override% --interactive=%interactive% --controllerClass=app\controllers\%controller%Controller --enableI18N=1 --enablePjax=1 --modelClass=app\models\%model% --searchModelClass=app\models\%model%Search --template=myCrud --appconfig=config/console.php

SET controller=Dbtable
SET model=DbTable
call yii.bat gii/crud --overwrite=%override% --interactive=%interactive% --controllerClass=app\controllers\%controller%Controller --enableI18N=1 --enablePjax=1 --modelClass=app\models\%model% --searchModelClass=app\models\%model%Search --template=myCrud --appconfig=config/console.php

SET controller=Dbtablefield
SET model=DbTableField
call yii.bat gii/crud --overwrite=%override% --interactive=%interactive% --controllerClass=app\controllers\%controller%Controller --enableI18N=1 --enablePjax=1 --modelClass=app\models\%model% --searchModelClass=app\models\%model%Search --template=myCrud --appconfig=config/console.php

SET controller=Dbtablecontext
SET model=DbTableContext
call yii.bat gii/crud --overwrite=%override% --interactive=%interactive% --controllerClass=app\controllers\%controller%Controller --enableI18N=1 --enablePjax=1 --modelClass=app\models\%model% --searchModelClass=app\models\%model%Search --template=myCrud --appconfig=config/console.php

SET controller=Dbtabletype
SET model=DbTableType
call yii.bat gii/crud --overwrite=%override% --interactive=%interactive% --controllerClass=app\controllers\%controller%Controller --enableI18N=1 --enablePjax=1 --modelClass=app\models\%model% --searchModelClass=app\models\%model%Search --template=myCrud --appconfig=config/console.php

SET controller=Tool
SET model=%controller%
call yii.bat gii/crud --overwrite=%override% --interactive=%interactive% --controllerClass=app\controllers\%controller%Controller --enableI18N=1 --enablePjax=1 --modelClass=app\models\%model% --searchModelClass=app\models\%model%Search --template=myCrud --appconfig=config/console.php

SET controller=Tooltype
SET model=ToolType
call yii.bat gii/crud --overwrite=%override% --interactive=%interactive% --controllerClass=app\controllers\%controller%Controller --enableI18N=1 --enablePjax=1 --modelClass=app\models\%model% --searchModelClass=app\models\%model%Search --template=myCrud --appconfig=config/console.php

SET controller=Datatransferprocess
SET model=DataTransferProcess
call yii.bat gii/crud --overwrite=%override% --interactive=%interactive% --controllerClass=app\controllers\%controller%Controller --enableI18N=1 --enablePjax=1 --modelClass=app\models\%model% --searchModelClass=app\models\%model%Search --template=myCrud --appconfig=config/console.php

SET controller=Datatransfertype
SET model=DataTransferType
call yii.bat gii/crud --overwrite=%override% --interactive=%interactive% --controllerClass=app\controllers\%controller%Controller --enableI18N=1 --enablePjax=1 --modelClass=app\models\%model% --searchModelClass=app\models\%model%Search --template=myCrud --appconfig=config/console.php

SET controller=Datadeliveryobject
SET model=DataDeliveryObject
call yii.bat gii/crud --overwrite=%override% --interactive=%interactive% --controllerClass=app\controllers\%controller%Controller --enableI18N=1 --enablePjax=1 --modelClass=app\models\%model% --searchModelClass=app\models\%model%Search --template=myCrud --appconfig=config/console.php

SET controller=Datadeliverytype
SET model=DataDeliveryType
call yii.bat gii/crud --overwrite=%override% --interactive=%interactive% --controllerClass=app\controllers\%controller%Controller --enableI18N=1 --enablePjax=1 --modelClass=app\models\%model% --searchModelClass=app\models\%model%Search --template=myCrud --appconfig=config/console.php

SET controller=Scheduling
SET model=%controller%
call yii.bat gii/crud --overwrite=%override% --interactive=%interactive% --controllerClass=app\controllers\%controller%Controller --enableI18N=1 --enablePjax=1 --modelClass=app\models\%model% --searchModelClass=app\models\%model%Search --template=myCrud --appconfig=config/console.php

SET controller=Glossary
SET model=%controller%
call yii.bat gii/crud --overwrite=%override% --interactive=%interactive% --controllerClass=app\controllers\%controller%Controller --enableI18N=1 --enablePjax=1 --modelClass=app\models\%model% --searchModelClass=app\models\%model%Search --template=myCrud --appconfig=config/console.php

SET controller=Contact
SET model=%controller%
call yii.bat gii/crud --overwrite=%override% --interactive=%interactive% --controllerClass=app\controllers\%controller%Controller --enableI18N=1 --enablePjax=1 --modelClass=app\models\%model% --searchModelClass=app\models\%model%Search --template=myCrud --appconfig=config/console.php

SET controller=ContactGroup
SET model=%controller%
call yii.bat gii/crud --overwrite=%override% --interactive=%interactive% --controllerClass=app\controllers\%controller%Controller --enableI18N=1 --enablePjax=1 --modelClass=app\models\%model% --searchModelClass=app\models\%model%Search --template=myCrud --appconfig=config/console.php

SET controller=Bracket
SET model=%controller%
call yii.bat gii/crud --overwrite=%override% --interactive=%interactive% --controllerClass=app\controllers\%controller%Controller --enableI18N=1 --enablePjax=1 --modelClass=app\models\%model% --searchModelClass=app\models\%model%Search --template=myCrud --appconfig=config/console.php

SET controller=Mapper
SET model=MapObject2Object
call yii.bat gii/crud --overwrite=%override% --interactive=%interactive% --controllerClass=app\controllers\%controller%Controller --enableI18N=1 --enablePjax=1 --modelClass=app\models\%model% --searchModelClass=app\models\%model%Search --template=myCrud --appconfig=config/console.php

SET controller=Objectdependson
SET model=ObjectDependsOn
call yii.bat gii/crud --overwrite=%override% --interactive=%interactive% --controllerClass=app\controllers\%controller%Controller --enableI18N=1 --enablePjax=1 --modelClass=app\models\%model% --searchModelClass=app\models\%model%Search --template=myCrud --appconfig=config/console.php
