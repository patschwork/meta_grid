CREATE TABLE keyfigure (
id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER NOT NULL  DEFAULT 1 REFERENCES object_type (id),
fk_project_id INTEGER DEFAULT NULL REFERENCES project (id),
name TEXT(250) DEFAULT NULL,
description TEXT(4000) DEFAULT NULL,
formula TEXT(4000) DEFAULT NULL,
aggregation TEXT(500) DEFAULT NULL,
character TEXT(500) DEFAULT NULL,
type TEXT(500) DEFAULT NULL,
unit TEXT(500) DEFAULT NULL,
value_range TEXT(500) DEFAULT NULL,
cumulation_possible BOOLEAN DEFAULT NULL,
fk_deleted_status_id INTEGER DEFAULT NULL REFERENCES deleted_status (id),
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id),
source_definition TEXT DEFAULT NULL,
source_definition_language TEXT(250) DEFAULT NULL,
source_comment TEXT DEFAULT NULL
);

CREATE TABLE object_type (
id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
name TEXT(250) DEFAULT NULL
);

CREATE TABLE client (
id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
name TEXT(250) DEFAULT NULL,
description TEXT(500) DEFAULT NULL,
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id)
);

CREATE TABLE project (
id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_client_id INTEGER NOT NULL  DEFAULT NULL REFERENCES client (id),
name TEXT(250) DEFAULT NULL,
description TEXT(500) DEFAULT NULL,
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id)
);

CREATE TABLE sourcesystem (
id INTEGER NOT NULL  PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER NOT NULL  DEFAULT 2 REFERENCES object_type (id),
fk_project_id INTEGER DEFAULT NULL REFERENCES project (id),
name TEXT(250) DEFAULT NULL,
description TEXT(500) DEFAULT NULL,
fk_contact_group_id_as_supporter INTEGER DEFAULT NULL REFERENCES contact_group (id),
fk_deleted_status_id INTEGER DEFAULT NULL REFERENCES deleted_status (id),
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id)
);

CREATE TABLE data_delivery_object (
id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER NOT NULL  DEFAULT 3 REFERENCES object_type (id),
fk_project_id INTEGER DEFAULT NULL REFERENCES project (id),
name TEXT(250) DEFAULT NULL,
description TEXT(500) DEFAULT NULL,
fk_tool_id INTEGER NOT NULL  DEFAULT NULL REFERENCES tool (id),
fk_data_delivery_type_id INTEGER DEFAULT NULL REFERENCES data_delivery_type (id),
fk_contact_group_id_as_data_owner INTEGER DEFAULT NULL REFERENCES contact_group (id),
fk_deleted_status_id INTEGER DEFAULT NULL REFERENCES deleted_status (id),
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id),
source_definition TEXT DEFAULT NULL,
source_comment TEXT DEFAULT NULL
);

CREATE TABLE map_object_2_object (
id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_mapping_qualifier_id INTEGER DEFAULT NULL REFERENCES mapping_qualifier (id),
ref_fk_object_id_1 INTEGER NOT NULL  DEFAULT NULL,
ref_fk_object_type_id_1 INTEGER NOT NULL  DEFAULT NULL REFERENCES object_type (id),
ref_fk_object_id_2 INTEGER DEFAULT NULL,
ref_fk_object_type_id_2 INTEGER NOT NULL  DEFAULT NULL REFERENCES object_type (id),
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id),
UNIQUE (ref_fk_object_id_1, ref_fk_object_type_id_1, ref_fk_object_id_2, ref_fk_object_type_id_2)
);

CREATE TABLE tool (
id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_tool_type_id INTEGER DEFAULT NULL REFERENCES tool_type (id),
tool_name TEXT(255) DEFAULT NULL,
vendor TEXT(255) DEFAULT NULL,
version TEXT(255) DEFAULT NULL,
comment TEXT(500) DEFAULT NULL,
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id)
);

CREATE TABLE object_depends_on (
id INTEGER NOT NULL  PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
ref_fk_object_id_parent INTEGER DEFAULT NULL,
ref_fk_object_type_id_parent INTEGER DEFAULT NULL,
ref_fk_object_id_child INTEGER DEFAULT NULL,
ref_fk_object_type_id_child INTEGER DEFAULT NULL,
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id)
);

CREATE TABLE attribute (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER NOT NULL  DEFAULT 9 REFERENCES object_type (id),
fk_project_id INTEGER NOT NULL  DEFAULT NULL REFERENCES project (id),
name TEXT(250) DEFAULT NULL,
description TEXT(4000) DEFAULT NULL,
formula TEXT(4000) DEFAULT NULL,
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id)
);

CREATE TABLE tool_type (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
name TEXT(250) DEFAULT NULL,
description TEXT(500) DEFAULT NULL,
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id)
);

CREATE TABLE db_table (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER NOT NULL  DEFAULT 4 REFERENCES object_type (id),
fk_project_id INTEGER NOT NULL  DEFAULT NULL REFERENCES project (id),
name TEXT(250) DEFAULT NULL,
description TEXT(4000) DEFAULT NULL,
location TEXT DEFAULT NULL,
fk_db_table_context_id INTEGER DEFAULT NULL REFERENCES db_table_context (id),
fk_db_table_type_id INTEGER DEFAULT NULL REFERENCES db_table_type (id),
fk_deleted_status_id INTEGER DEFAULT NULL REFERENCES deleted_status (id),
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id),
source_definition TEXT DEFAULT NULL,
source_comment TEXT DEFAULT NULL
);

CREATE TABLE db_table_field (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER NOT NULL  DEFAULT 5 REFERENCES object_type (id),
fk_project_id INTEGER NOT NULL  DEFAULT NULL REFERENCES project (id),
name TEXT(250) DEFAULT NULL,
description TEXT(500) DEFAULT NULL,
fk_db_table_id INTEGER DEFAULT NULL REFERENCES db_table (id),
datatype TEXT(250) DEFAULT NULL,
bulk_load_checksum TEXT(200) DEFAULT NULL,
fk_deleted_status_id INTEGER DEFAULT NULL REFERENCES deleted_status (id),
is_PrimaryKey BOOLEAN DEFAULT NULL,
is_BusinessKey BOOLEAN DEFAULT NULL,
is_GDPR_relevant BOOLEAN DEFAULT NULL,
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id),
source_definition TEXT DEFAULT NULL,
source_comment TEXT DEFAULT NULL
);

CREATE TABLE db_table_context (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER NOT NULL  DEFAULT 6 REFERENCES object_type (id),
fk_project_id INTEGER NOT NULL  DEFAULT NULL REFERENCES project (id),
name TEXT(250) DEFAULT NULL,
description TEXT(500) DEFAULT NULL,
prefix TEXT(100) DEFAULT NULL,
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id)
);

CREATE TABLE db_database (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER NOT NULL  DEFAULT 11 REFERENCES object_type (id),
fk_project_id INTEGER NOT NULL  DEFAULT NULL REFERENCES project (id),
name TEXT DEFAULT NULL,
description TEXT DEFAULT NULL,
fk_tool_id INTEGER NOT NULL  DEFAULT NULL REFERENCES tool (id),
fk_deleted_status_id INTEGER DEFAULT NULL REFERENCES deleted_status (id),
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id),
source_definition TEXT DEFAULT NULL,
source_definition_language TEXT(250) DEFAULT NULL,
source_comment TEXT DEFAULT NULL
);

CREATE TABLE scheduling (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER NOT NULL  DEFAULT 8 REFERENCES object_type (id),
fk_project_id INTEGER NOT NULL  DEFAULT NULL REFERENCES project (id),
name TEXT DEFAULT NULL,
description TEXT DEFAULT NULL,
fk_tool_id INTEGER NOT NULL  DEFAULT NULL REFERENCES tool (id),
scheduling_series TEXT DEFAULT NULL,
fk_deleted_status_id INTEGER DEFAULT NULL REFERENCES deleted_status (id),
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id),
source_definition TEXT DEFAULT NULL,
source_definition_language TEXT(250) DEFAULT NULL,
source_comment TEXT DEFAULT NULL
);

CREATE TABLE parameter (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER NOT NULL  DEFAULT 7 REFERENCES object_type (id),
fk_project_id INTEGER NOT NULL  DEFAULT NULL REFERENCES project (id),
name TEXT DEFAULT NULL,
description TEXT DEFAULT NULL,
is_optional INTEGER DEFAULT NULL,
default_value TEXT DEFAULT NULL,
datatype TEXT DEFAULT NULL,
range TEXT DEFAULT NULL,
fk_deleted_status_id INTEGER DEFAULT NULL REFERENCES deleted_status (id),
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id),
source_definition TEXT DEFAULT NULL,
source_definition_language TEXT(250) DEFAULT NULL,
source_comment TEXT DEFAULT NULL
);

CREATE TABLE db_table_type (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
name TEXT DEFAULT NULL,
description TEXT DEFAULT NULL,
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id),
source_definition_language TEXT(250) DEFAULT NULL
);

CREATE TABLE object_comment (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER NOT NULL  DEFAULT 12 REFERENCES object_type (id),
ref_fk_object_id INTEGER DEFAULT NULL,
ref_fk_object_type_id INTEGER DEFAULT NULL REFERENCES object_type (id),
comment TEXT DEFAULT NULL,
created_at_datetime TEXT DEFAULT NULL,
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id)
);

CREATE TABLE glossary (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER NOT NULL  DEFAULT 10 REFERENCES object_type (id),
fk_project_id INTEGER NOT NULL  DEFAULT NULL REFERENCES project (id),
name TEXT DEFAULT NULL,
description TEXT DEFAULT NULL,
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id)
);

CREATE TABLE data_delivery_type (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
name TEXT(250) DEFAULT NULL,
description TEXT(500) DEFAULT NULL,
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id),
source_definition_language TEXT(250) DEFAULT NULL
);

CREATE TABLE data_transfer_process (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER NOT NULL  DEFAULT 13 REFERENCES object_type (id),
fk_project_id INTEGER NOT NULL  DEFAULT NULL REFERENCES project (id),
name TEXT(250) DEFAULT NULL,
description TEXT(4000) DEFAULT NULL,
fk_data_transfer_type_id INTEGER DEFAULT NULL REFERENCES data_transfer_type (id),
location TEXT(500) DEFAULT NULL,
source_internal_object_id TEXT(500) DEFAULT NULL,
fk_deleted_status_id INTEGER DEFAULT NULL REFERENCES deleted_status (id),
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id),
source_definition TEXT DEFAULT NULL,
source_comment TEXT DEFAULT NULL
);

CREATE TABLE data_transfer_type (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
name TEXT(250) DEFAULT NULL,
description TEXT(4000) DEFAULT NULL,
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id),
source_definition_language TEXT(250) DEFAULT NULL
);

CREATE TABLE app_config (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
key TEXT DEFAULT NULL,
valueINT TEXT DEFAULT NULL,
valueSTRING TEXT DEFAULT NULL,
description TEXT DEFAULT NULL
);

CREATE TABLE contact_group (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER DEFAULT 14 REFERENCES object_type (id),
fk_client_id INTEGER NOT NULL  DEFAULT NULL REFERENCES client (id),
name TEXT DEFAULT NULL,
description TEXT DEFAULT NULL,
short_name TEXT DEFAULT NULL,
fk_deleted_status_id INTEGER DEFAULT NULL REFERENCES deleted_status (id),
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id)
);

CREATE TABLE contact (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER DEFAULT 15 REFERENCES object_type (id),
fk_contact_group_id INTEGER DEFAULT NULL REFERENCES contact_group (id),
fk_client_id INTEGER NOT NULL  DEFAULT NULL REFERENCES client (id),
givenname TEXT DEFAULT NULL,
surname TEXT DEFAULT NULL,
email TEXT DEFAULT NULL,
phone TEXT DEFAULT NULL,
mobile TEXT DEFAULT NULL,
ldap_cn TEXT DEFAULT NULL,
description TEXT DEFAULT NULL,
fk_deleted_status_id INTEGER DEFAULT NULL REFERENCES deleted_status (id),
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id)
);

CREATE TABLE bracket (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER DEFAULT 16 REFERENCES object_type (id),
fk_project_id INTEGER DEFAULT NULL REFERENCES project (id),
name TEXT(250) DEFAULT NULL,
description TEXT(4000) DEFAULT NULL,
fk_attribute_id INTEGER DEFAULT NULL,
fk_object_type_id_as_searchFilter INTEGER DEFAULT NULL,
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id)
);

CREATE TABLE bracket_searchPattern (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER DEFAULT 17 REFERENCES object_type (id),
fk_bracket_id INTEGER DEFAULT NULL REFERENCES bracket (id),
searchPattern TEXT(500) DEFAULT NULL,
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id)
);

CREATE TABLE import_stage_db_table (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
client_name TEXT(250) DEFAULT NULL,
project_name TEXT(250) DEFAULT NULL,
db_table_name TEXT(250) DEFAULT NULL,
db_table_description TEXT(500) DEFAULT NULL,
db_table_field_name TEXT(250) DEFAULT NULL,
db_table_field_datatype TEXT(250) DEFAULT NULL,
db_table_field_description TEXT(500) DEFAULT NULL,
db_table_type_name TEXT(250) DEFAULT NULL,
db_table_context_name TEXT(250) DEFAULT NULL,
db_table_context_prefix TEXT(100) DEFAULT NULL,
isPrimaryKeyField BOOLEAN DEFAULT NULL,
isForeignKeyField BOOLEAN DEFAULT NULL,
foreignKey_table_name TEXT(250) DEFAULT NULL,
foreignKey_table_field_name TEXT(250) DEFAULT NULL,
_import_state INTEGER DEFAULT NULL,
_import_date TIMESTAMP DEFAULT NULL,
is_BusinessKey BOOLEAN DEFAULT NULL,
is_GDPR_relevant BOOLEAN DEFAULT NULL,
location TEXT DEFAULT NULL,
database_or_catalog TEXT(1000) DEFAULT NULL,
schema TEXT(4000) DEFAULT NULL,
fk_project_id TEXT DEFAULT NULL,
fk_db_database_id TEXT DEFAULT NULL,
column_default_value TEXT(1000) DEFAULT NULL,
column_cant_be_null BOOLEAN DEFAULT NULL,
additional_field_1 TEXT(4000) DEFAULT NULL,
additional_field_2 TEXT(4000) DEFAULT NULL,
additional_field_3 TEXT(4000) DEFAULT NULL,
additional_field_4 TEXT(4000) DEFAULT NULL,
additional_field_5 TEXT(4000) DEFAULT NULL,
additional_field_6 TEXT(4000) DEFAULT NULL,
additional_field_7 TEXT(4000) DEFAULT NULL,
additional_field_8 TEXT(4000) DEFAULT NULL,
additional_field_9 TEXT(4000) DEFAULT NULL
);

CREATE TABLE perspective_filter (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_language_id TEXT(32) DEFAULT NULL,
fk_object_type_id INTEGER DEFAULT 18 REFERENCES object_type (id),
filter_attribute_name TEXT(150) DEFAULT NULL,
filter_value TEXT(150) DEFAULT NULL,
ref_fk_object_type_id INTEGER DEFAULT NULL REFERENCES object_type (id),
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id)
);

CREATE TABLE mapping_qualifier (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER DEFAULT 19 REFERENCES object_type (id),
name TEXT(250) DEFAULT NULL,
short_name TEXT(250) DEFAULT NULL,
description TEXT(4000) DEFAULT NULL,
needs_object_depends_on BOOLEAN NOT NULL  DEFAULT 0,
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id)
);

CREATE TABLE url (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER DEFAULT 24 REFERENCES object_type (id),
fk_project_id INTEGER DEFAULT NULL REFERENCES project (id),
name TEXT(250) DEFAULT NULL,
description TEXT(4000) DEFAULT NULL,
url TEXT(4000) DEFAULT NULL,
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id)
);

CREATE TABLE cleanup_queue (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
ref_fk_object_id INTEGER DEFAULT NULL,
ref_fk_object_type_id INTEGER DEFAULT NULL REFERENCES object_type (id),
created_at_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE deleted_status (
id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER DEFAULT 25 REFERENCES object_type (id),
name TEXT(250) DEFAULT NULL,
description TEXT(4000) DEFAULT NULL,
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id)
);

CREATE TABLE export_file_db_table_queue (
id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY,
session TEXT(200) DEFAULT NULL,
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id)
);

CREATE TABLE export_file_db_table_params (
id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
session TEXT(200) DEFAULT NULL,
allowed_fk_project_id INTEGER DEFAULT NULL,
allowed_fk_client_id INTEGER DEFAULT NULL,
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id)
);

CREATE TABLE export_file_db_table_result (
id INTEGER NOT NULL  DEFAULT NULL AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER DEFAULT NULL,
fk_project_id INTEGER DEFAULT NULL,
fk_client_id INTEGER DEFAULT NULL,
project_name TEXT(250) DEFAULT NULL,
client_name TEXT(250) DEFAULT NULL,
name TEXT(250) DEFAULT NULL,
description TEXT(4000) DEFAULT NULL,
location TEXT DEFAULT NULL,
fk_db_table_context_id INTEGER DEFAULT NULL,
db_table_context_name TEXT(250) DEFAULT NULL,
fk_db_table_type_id INTEGER DEFAULT NULL,
db_table_type_name TEXT(250) DEFAULT NULL,
fk_deleted_status_id INTEGER DEFAULT NULL,
deleted_status_name TEXT(250) DEFAULT NULL,
databaseInfoFromLocation TEXT DEFAULT NULL,
comments TEXT DEFAULT NULL,
mappings TEXT DEFAULT NULL,
session TEXT(200) DEFAULT NULL,
_auto_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
_created_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id)
);

CREATE TABLE object_persistence_method (
id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER DEFAULT 26 REFERENCES object_type (id),
name TEXT(250) DEFAULT NULL,
description TEXT(4000) DEFAULT NULL
);

CREATE TABLE datamanagement_process (
id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER DEFAULT 27 REFERENCES object_type (id),
name TEXT(250) DEFAULT NULL,
description TEXT(4000) DEFAULT NULL,
tool TEXT(500) DEFAULT NULL,
tool_version TEXT(250) DEFAULT NULL,
routine TEXT(500) DEFAULT NULL
);

CREATE TABLE tag (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER DEFAULT 20 REFERENCES object_type (id),
name TEXT(250) NOT NULL ,
fk_project_id INTEGER DEFAULT NULL REFERENCES project (id),
fk_user_id INTEGER DEFAULT NULL,
UNIQUE (name, fk_project_id, fk_user_id)
);

CREATE TABLE map_object_2_tag (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
ref_fk_object_id INTEGER NOT NULL ,
ref_fk_object_type_id INTEGER NOT NULL  REFERENCES object_type (id),
fk_tag_id INTEGER NOT NULL  REFERENCES tag (id),
UNIQUE (ref_fk_object_id, ref_fk_object_type_id, fk_tag_id)
);

CREATE TABLE sink (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER NOT NULL  DEFAULT 28 REFERENCES object_type (id),
fk_project_id INTEGER NOT NULL  DEFAULT NULL REFERENCES project (id),
name TEXT(250) NOT NULL ,
description TEXT(4000) DEFAULT NULL,
fk_db_database_id INTEGER DEFAULT NULL REFERENCES db_database (id),
sink_is_also_sourcesystem BOOLEAN DEFAULT 0,
fk_sourcesystem_id INTEGER DEFAULT NULL REFERENCES sourcesystem (id),
fk_deleted_status_id INTEGER DEFAULT NULL REFERENCES deleted_status (id),
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id)
);

CREATE TABLE landscape (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER NOT NULL  DEFAULT 29 REFERENCES object_type (id),
scope TEXT(100) NOT NULL  DEFAULT 'NULL',
fk_client_id INTEGER DEFAULT NULL REFERENCES client (id),
fk_project_id INTEGER DEFAULT NULL REFERENCES project (id),
name TEXT(250) NOT NULL ,
description TEXT(4000) DEFAULT NULL,
fk_deleted_status_id INTEGER DEFAULT NULL REFERENCES deleted_status (id),
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id)
);

CREATE TABLE landscape_composing (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER NOT NULL  DEFAULT 30 REFERENCES object_type (id),
name TEXT(250) DEFAULT NULL,
description TEXT(4000) DEFAULT NULL,
ref_fk_object_id_1 INTEGER NOT NULL  DEFAULT NULL,
ref_fk_object_type_id_1 INTEGER NOT NULL  DEFAULT NULL REFERENCES object_type (id),
ref_fk_object_id_2 INTEGER NOT NULL  DEFAULT NULL,
ref_fk_object_type_id_2 INTEGER NOT NULL  DEFAULT NULL REFERENCES object_type (id),
fk_landscape_id INTEGER NOT NULL  DEFAULT NULL REFERENCES landscape (id),
fk_tool_id INTEGER DEFAULT NULL REFERENCES tool (id),
fk_data_transfer_process_id INTEGER DEFAULT NULL REFERENCES data_transfer_process (id),
fk_deleted_status_id INTEGER DEFAULT NULL REFERENCES deleted_status (id),
fk_object_persistence_method_id INTEGER DEFAULT NULL REFERENCES object_persistence_method (id),
fk_datamanagement_process_id INTEGER DEFAULT NULL REFERENCES datamanagement_process (id)
);

