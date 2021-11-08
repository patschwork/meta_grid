#!/bin/sh

export override=1
export interactive=0

export tableName=datamanagement_process
export modelClass=DatamanagementProcess
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=object_persistence_method
export modelClass=ObjectPersistenceMethod
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1


export tableName=attribute
export modelClass=Attribute
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=bracket
export modelClass=Bracket
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=bracket_searchPattern
export modelClass=BracketSearchPattern
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=client
export modelClass=Client
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=contact
export modelClass=Contact
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=contact_group
export modelClass=ContactGroup
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=data_delivery_object
export modelClass=DataDeliveryObject
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=data_delivery_type
export modelClass=DataDeliveryType
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=data_transfer_process
export modelClass=DataTransferProcess
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=data_transfer_type
export modelClass=DataTransferType
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=db_database
export modelClass=DbDatabase
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=db_table
export modelClass=DbTable
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=db_table_context
export modelClass=DbTableContext
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=db_table_field
export modelClass=DbTableField
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=db_table_type
export modelClass=DbTableType
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=deleted_status
export modelClass=DeletedStatus
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=export_file_db_table_field_params
export modelClass=ExportFileDbTableFieldParams
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=export_file_db_table_field_queue
export modelClass=ExportFileDbTableFieldQueue
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=export_file_db_table_field_result
export modelClass=ExportFileDbTableFieldResult
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=export_file_db_table_params
export modelClass=ExportFileDbTableParams
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=export_file_db_table_queue
export modelClass=ExportFileDbTableQueue
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=export_file_db_table_result
export modelClass=ExportFileDbTableResult
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=glossary
export modelClass=Glossary
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=import_stage_db_table
export modelClass=ImportStageDbTable
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=keyfigure
export modelClass=Keyfigure
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=map_object_2_object
export modelClass=MapObject2Object
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=mapping_qualifier
export modelClass=MappingQualifier
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=object_comment
export modelClass=Objectcomment
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=object_depends_on
export modelClass=ObjectDependsOn
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=object_persistence_method
export modelClass=ObjectPersistenceMethod
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=object_type
export modelClass=ObjectType
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=project
export modelClass=Project
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=scheduling
export modelClass=Scheduling
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=sourcesystem
export modelClass=Sourcesystem
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=tool
export modelClass=Tool
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=tool_type
export modelClass=ToolType
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=url
export modelClass=Url
php7.1 yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

