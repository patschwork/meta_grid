#!/bin/sh

export override=1
export interactive=0

export tableName=datamanagement_process
export modelClass=DatamanagementProcess
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=object_persistence_method
export modelClass=ObjectPersistenceMethod
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1


export tableName=attribute
export modelClass=Attribute
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=bracket
export modelClass=Bracket
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=bracket_searchPattern
export modelClass=BracketSearchPattern
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=client
export modelClass=Client
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=contact
export modelClass=Contact
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=contact_group
export modelClass=ContactGroup
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=data_delivery_object
export modelClass=DataDeliveryObject
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=data_delivery_type
export modelClass=DataDeliveryType
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=data_transfer_process
export modelClass=DataTransferProcess
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=data_transfer_type
export modelClass=DataTransferType
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=db_database
export modelClass=DbDatabase
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=db_table
export modelClass=DbTable
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=db_table_context
export modelClass=DbTableContext
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=db_table_field
export modelClass=DbTableField
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=db_table_type
export modelClass=DbTableType
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=deleted_status
export modelClass=DeletedStatus
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=export_file_db_table_field_params
export modelClass=ExportFileDbTableFieldParams
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=export_file_db_table_field_queue
export modelClass=ExportFileDbTableFieldQueue
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=export_file_db_table_field_result
export modelClass=ExportFileDbTableFieldResult
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=export_file_db_table_params
export modelClass=ExportFileDbTableParams
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=export_file_db_table_queue
export modelClass=ExportFileDbTableQueue
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=export_file_db_table_result
export modelClass=ExportFileDbTableResult
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=glossary
export modelClass=Glossary
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=import_stage_db_table
export modelClass=ImportStageDbTable
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=keyfigure
export modelClass=Keyfigure
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=map_object_2_object
export modelClass=MapObject2Object
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=mapping_qualifier
export modelClass=MappingQualifier
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=object_comment
export modelClass=Objectcomment
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=object_depends_on
export modelClass=ObjectDependsOn
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=object_persistence_method
export modelClass=ObjectPersistenceMethod
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=object_type
export modelClass=ObjectType
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=project
export modelClass=Project
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=scheduling
export modelClass=Scheduling
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=sourcesystem
export modelClass=Sourcesystem
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=tool
export modelClass=Tool
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=tool_type
export modelClass=ToolType
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=url
export modelClass=Url
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1

export tableName=v_All_Objects_Union
export modelClass=VAllObjectsUnion
php yii gii/model --overwrite=$override --interactive=$interactive --tableName=$tableName --modelClass=$modelClass --ns=app\\models\\base --enableI18N=1
