#!/bin/sh

export override=1
export interactive=0

export controller=Client
export model=$controller
php yii gii/crud --overwrite=$override --interactive=$interactive --controllerClass=app\\controllers\\${controller}Controller --enableI18N=1 --enablePjax=1 --modelClass=app\\models\\${model} --searchModelClass=app\\models\\${model}Search --template=myCrud --appconfig=config/console.php

export controller=Project
export model=$controller
php yii gii/crud --overwrite=$override --interactive=$interactive --controllerClass=app\\controllers\\${controller}Controller --enableI18N=1 --enablePjax=1 --modelClass=app\\models\\${model} --searchModelClass=app\\models\\${model}Search --template=myCrud --appconfig=config/console.php

export controller=Keyfigure
export model=$controller
php yii gii/crud --overwrite=$override --interactive=$interactive --controllerClass=app\\controllers\\${controller}Controller --enableI18N=1 --enablePjax=1 --modelClass=app\\models\\${model} --searchModelClass=app\\models\\${model}Search --template=myCrud --appconfig=config/console.php

export controller=Attribute
export model=$controller
php yii gii/crud --overwrite=$override --interactive=$interactive --controllerClass=app\\controllers\\${controller}Controller --enableI18N=1 --enablePjax=1 --modelClass=app\\models\\${model} --searchModelClass=app\\models\\${model}Search --template=myCrud --appconfig=config/console.php

export controller=Sourcesystem
export model=$controller
php yii gii/crud --overwrite=$override --interactive=$interactive --controllerClass=app\\controllers\\${controller}Controller --enableI18N=1 --enablePjax=1 --modelClass=app\\models\\${model} --searchModelClass=app\\models\\${model}Search --template=myCrud --appconfig=config/console.php

export controller=Dbdatabase
export model=DbDatabase
php yii gii/crud --overwrite=$override --interactive=$interactive --controllerClass=app\\controllers\\${controller}Controller --enableI18N=1 --enablePjax=1 --modelClass=app\\models\\${model} --searchModelClass=app\\models\\${model}Search --template=myCrud --appconfig=config/console.php

export controller=Dbtable
export model=DbTable
php yii gii/crud --overwrite=$override --interactive=$interactive --controllerClass=app\\controllers\\${controller}Controller --enableI18N=1 --enablePjax=1 --modelClass=app\\models\\${model} --searchModelClass=app\\models\\${model}Search --template=myCrud --appconfig=config/console.php

export controller=Dbtablefield
export model=DbTableField
php yii gii/crud --overwrite=$override --interactive=$interactive --controllerClass=app\\controllers\\${controller}Controller --enableI18N=1 --enablePjax=1 --modelClass=app\\models\\${model} --searchModelClass=app\\models\\${model}Search --template=myCrud --appconfig=config/console.php

export controller=Dbtablecontext
export model=DbTableContext
php yii gii/crud --overwrite=$override --interactive=$interactive --controllerClass=app\\controllers\\${controller}Controller --enableI18N=1 --enablePjax=1 --modelClass=app\\models\\${model} --searchModelClass=app\\models\\${model}Search --template=myCrud --appconfig=config/console.php

export controller=Dbtabletype
export model=DbTableType
php yii gii/crud --overwrite=$override --interactive=$interactive --controllerClass=app\\controllers\\${controller}Controller --enableI18N=1 --enablePjax=1 --modelClass=app\\models\\${model} --searchModelClass=app\\models\\${model}Search --template=myCrud --appconfig=config/console.php

export controller=Tool
export model=$controller
php yii gii/crud --overwrite=$override --interactive=$interactive --controllerClass=app\\controllers\\${controller}Controller --enableI18N=1 --enablePjax=1 --modelClass=app\\models\\${model} --searchModelClass=app\\models\\${model}Search --template=myCrud --appconfig=config/console.php

export controller=Tooltype
export model=ToolType
php yii gii/crud --overwrite=$override --interactive=$interactive --controllerClass=app\\controllers\\${controller}Controller --enableI18N=1 --enablePjax=1 --modelClass=app\\models\\${model} --searchModelClass=app\\models\\${model}Search --template=myCrud --appconfig=config/console.php

export controller=Datatransferprocess
export model=DataTransferProcess
php yii gii/crud --overwrite=$override --interactive=$interactive --controllerClass=app\\controllers\\${controller}Controller --enableI18N=1 --enablePjax=1 --modelClass=app\\models\\${model} --searchModelClass=app\\models\\${model}Search --template=myCrud --appconfig=config/console.php

export controller=Datatransfertype
export model=DataTransferType
php yii gii/crud --overwrite=$override --interactive=$interactive --controllerClass=app\\controllers\\${controller}Controller --enableI18N=1 --enablePjax=1 --modelClass=app\\models\\${model} --searchModelClass=app\\models\\${model}Search --template=myCrud --appconfig=config/console.php

export controller=Datadeliveryobject
export model=DataDeliveryObject
php yii gii/crud --overwrite=$override --interactive=$interactive --controllerClass=app\\controllers\\${controller}Controller --enableI18N=1 --enablePjax=1 --modelClass=app\\models\\${model} --searchModelClass=app\\models\\${model}Search --template=myCrud --appconfig=config/console.php

export controller=Datadeliverytype
export model=DataDeliveryType
php yii gii/crud --overwrite=$override --interactive=$interactive --controllerClass=app\\controllers\\${controller}Controller --enableI18N=1 --enablePjax=1 --modelClass=app\\models\\${model} --searchModelClass=app\\models\\${model}Search --template=myCrud --appconfig=config/console.php

export controller=Scheduling
export model=$controller
php yii gii/crud --overwrite=$override --interactive=$interactive --controllerClass=app\\controllers\\${controller}Controller --enableI18N=1 --enablePjax=1 --modelClass=app\\models\\${model} --searchModelClass=app\\models\\${model}Search --template=myCrud --appconfig=config/console.php

export controller=Glossary
export model=$controller
php yii gii/crud --overwrite=$override --interactive=$interactive --controllerClass=app\\controllers\\${controller}Controller --enableI18N=1 --enablePjax=1 --modelClass=app\\models\\${model} --searchModelClass=app\\models\\${model}Search --template=myCrud --appconfig=config/console.php

export controller=Contact
export model=$controller
php yii gii/crud --overwrite=$override --interactive=$interactive --controllerClass=app\\controllers\\${controller}Controller --enableI18N=1 --enablePjax=1 --modelClass=app\\models\\${model} --searchModelClass=app\\models\\${model}Search --template=myCrud --appconfig=config/console.php

export controller=Contactgroup
export model=ContactGroup
php yii gii/crud --overwrite=$override --interactive=$interactive --controllerClass=app\\controllers\\${controller}Controller --enableI18N=1 --enablePjax=1 --modelClass=app\\models\\${model} --searchModelClass=app\\models\\${model}Search --template=myCrud --appconfig=config/console.php

export controller=Bracket
export model=$controller
php yii gii/crud --overwrite=$override --interactive=$interactive --controllerClass=app\\controllers\\${controller}Controller --enableI18N=1 --enablePjax=1 --modelClass=app\\models\\${model} --searchModelClass=app\\models\\${model}Search --template=myCrud --appconfig=config/console.php

export controller=Mapper
export model=MapObject2Object
php yii gii/crud --overwrite=$override --interactive=$interactive --controllerClass=app\\controllers\\${controller}Controller --enableI18N=1 --enablePjax=1 --modelClass=app\\models\\${model} --searchModelClass=app\\models\\${model}Search --template=myCrud --appconfig=config/console.php

export controller=Objectdependson
export model=ObjectDependsOn
php yii gii/crud --overwrite=$override --interactive=$interactive --controllerClass=app\\controllers\\${controller}Controller --enableI18N=1 --enablePjax=1 --modelClass=app\\models\\${model} --searchModelClass=app\\models\\${model}Search --template=myCrud --appconfig=config/console.php

export controller=Url
export model=Url
php yii gii/crud --overwrite=$override --interactive=$interactive --controllerClass=app\\controllers\\${controller}Controller --enableI18N=1 --enablePjax=1 --modelClass=app\\models\\${model} --searchModelClass=app\\models\\${model}Search --template=myCrud --appconfig=config/console.php

# export controller=Mappingqualifier
# export model=MappingQualifier
# php yii gii/crud --overwrite=$override --interactive=$interactive --controllerClass=app\\controllers\\${controller}Controller --enableI18N=1 --enablePjax=1 --modelClass=app\\models\\${model} --searchModelClass=app\\models\\${model}Search --template=myCrud --appconfig=config/console.php
