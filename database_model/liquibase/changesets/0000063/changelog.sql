PRAGMA legacy_alter_table=ON;

DROP TRIGGER TRIG_keyfigure_log_UPDATE;

DROP TRIGGER TRIG_keyfigure_log_DELETE;

DROP TRIGGER TRIG_keyfigure_log_INSERT;


ALTER TABLE keyfigure RENAME TO keyfigure_62fc522f85ed4595aef75a47373e58bc;


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
fk_deleted_status_id INTEGER DEFAULT NULL REFERENCES deleted_status (id)
)
;


INSERT INTO keyfigure
(
    id,uuid,fk_object_type_id,fk_project_id,name,description,formula,aggregation,character,type,unit,value_range,cumulation_possible
)
SELECT id,uuid,fk_object_type_id,fk_project_id,name,description,formula,aggregation,character,type,unit,value_range,cumulation_possible FROM keyfigure_62fc522f85ed4595aef75a47373e58bc;



DROP TABLE keyfigure_62fc522f85ed4595aef75a47373e58bc;


PRAGMA foreign_keys=off;



DROP TRIGGER TRIG_sourcesystem_log_INSERT;

DROP TRIGGER TRIG_sourcesystem_log_UPDATE;

DROP TRIGGER TRIG_sourcesystem_log_DELETE;


ALTER TABLE sourcesystem RENAME TO sourcesystem_b6a8474b028243258ef54fd97439e052;


CREATE TABLE sourcesystem (
id INTEGER NOT NULL  PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER NOT NULL  DEFAULT 2 REFERENCES object_type (id),
fk_project_id INTEGER DEFAULT NULL REFERENCES project (id),
name TEXT(250) DEFAULT NULL,
description TEXT(500) DEFAULT NULL,
fk_contact_group_id_as_supporter INTEGER DEFAULT NULL REFERENCES contact_group (id),
fk_deleted_status_id INTEGER DEFAULT NULL REFERENCES deleted_status (id)
)
;


INSERT INTO sourcesystem
(
    id,uuid,fk_object_type_id,fk_project_id,name,description,fk_contact_group_id_as_supporter
)
SELECT id,uuid,fk_object_type_id,fk_project_id,name,description,fk_contact_group_id_as_supporter FROM sourcesystem_b6a8474b028243258ef54fd97439e052;



DROP TABLE sourcesystem_b6a8474b028243258ef54fd97439e052;


PRAGMA foreign_keys=off;

		


DROP TRIGGER TRIG_data_delivery_object_log_INSERT;

DROP TRIGGER TRIG_data_delivery_object_log_UPDATE;

DROP TRIGGER TRIG_data_delivery_object_log_DELETE;



ALTER TABLE data_delivery_object RENAME TO data_delivery_object_e91518386e4343ac9930702f43e030b4;


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
fk_deleted_status_id INTEGER DEFAULT NULL REFERENCES deleted_status (id)
)
;


INSERT INTO data_delivery_object
(
    id,uuid,fk_object_type_id,fk_project_id,name,description,fk_tool_id,fk_data_delivery_type_id,fk_contact_group_id_as_data_owner
)
SELECT id,uuid,fk_object_type_id,fk_project_id,name,description,fk_tool_id,fk_data_delivery_type_id,fk_contact_group_id_as_data_owner FROM data_delivery_object_e91518386e4343ac9930702f43e030b4;



DROP TABLE data_delivery_object_e91518386e4343ac9930702f43e030b4;




DROP TRIGGER TRIG_db_table_log_INSERT;

DROP TRIGGER TRIG_db_table_log_UPDATE;

DROP TRIGGER TRIG_db_table_log_DELETE;



ALTER TABLE db_table RENAME TO db_table_8e37af8ce56e49c595c84a63671d1b2d;


CREATE TABLE db_table (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER NOT NULL  DEFAULT 4 REFERENCES object_type (id),
fk_project_id INTEGER NOT NULL  DEFAULT NULL REFERENCES project (id),
name TEXT(250) DEFAULT NULL,
description TEXT(500) DEFAULT NULL,
location TEXT DEFAULT NULL,
fk_db_table_context_id INTEGER DEFAULT NULL REFERENCES db_table_context (id),
fk_db_table_type_id INTEGER DEFAULT NULL REFERENCES db_table_type (id),
fk_deleted_status_id INTEGER DEFAULT NULL REFERENCES deleted_status (id)
)
;


INSERT INTO db_table
(
    id,uuid,fk_object_type_id,fk_project_id,name,description,location,fk_db_table_context_id,fk_db_table_type_id
)
SELECT id,uuid,fk_object_type_id,fk_project_id,name,description,location,fk_db_table_context_id,fk_db_table_type_id FROM db_table_8e37af8ce56e49c595c84a63671d1b2d;



DROP TABLE db_table_8e37af8ce56e49c595c84a63671d1b2d;




DROP TRIGGER TRIG_db_table_field_log_INSERT;

DROP TRIGGER TRIG_db_table_field_log_UPDATE;

DROP TRIGGER TRIG_db_table_field_log_DELETE;


ALTER TABLE db_table_field RENAME TO db_table_field_60b8e405546a4a3792e200a65736487b;


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
fk_deleted_status_id INTEGER DEFAULT NULL REFERENCES deleted_status (id)
)
;


INSERT INTO db_table_field
(
    id,uuid,fk_object_type_id,fk_project_id,name,description,fk_db_table_id,datatype,bulk_load_checksum
)
SELECT id,uuid,fk_object_type_id,fk_project_id,name,description,fk_db_table_id,datatype,bulk_load_checksum FROM db_table_field_60b8e405546a4a3792e200a65736487b;



DROP TABLE db_table_field_60b8e405546a4a3792e200a65736487b;




DROP TRIGGER TRIG_parameter_log_INSERT;

DROP TRIGGER TRIG_parameter_log_UPDATE;

DROP TRIGGER TRIG_parameter_log_DELETE;


ALTER TABLE parameter RENAME TO parameter_68a61935a42e4430b1be629a2cbd6b7e;


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
fk_deleted_status_id INTEGER DEFAULT NULL REFERENCES deleted_status (id)
)
;


INSERT INTO parameter
(
    id,uuid,fk_object_type_id,fk_project_id,name,description,is_optional,default_value,datatype,range
)
SELECT id,uuid,fk_object_type_id,fk_project_id,name,description,is_optional,default_value,datatype,range FROM parameter_68a61935a42e4430b1be629a2cbd6b7e;



DROP TABLE parameter_68a61935a42e4430b1be629a2cbd6b7e;




DROP TRIGGER TRIG_scheduling_log_INSERT;

DROP TRIGGER TRIG_scheduling_log_UPDATE;

DROP TRIGGER TRIG_scheduling_log_DELETE;



ALTER TABLE scheduling RENAME TO scheduling_7a68b2af44084524a583c7bf7033d895;


CREATE TABLE scheduling (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER NOT NULL  DEFAULT 8 REFERENCES object_type (id),
fk_project_id INTEGER NOT NULL  DEFAULT NULL REFERENCES project (id),
name TEXT DEFAULT NULL,
description TEXT DEFAULT NULL,
fk_tool_id INTEGER NOT NULL  DEFAULT NULL REFERENCES tool (id),
scheduling_series TEXT DEFAULT NULL,
fk_deleted_status_id INTEGER DEFAULT NULL REFERENCES deleted_status (id)
)
;


INSERT INTO scheduling
(
    id,uuid,fk_object_type_id,fk_project_id,name,description,fk_tool_id,scheduling_series
)
SELECT id,uuid,fk_object_type_id,fk_project_id,name,description,fk_tool_id,scheduling_series FROM scheduling_7a68b2af44084524a583c7bf7033d895;



DROP TABLE scheduling_7a68b2af44084524a583c7bf7033d895;




DROP TRIGGER TRIG_db_database_log_INSERT;

DROP TRIGGER TRIG_db_database_log_UPDATE;

DROP TRIGGER TRIG_db_database_log_DELETE;


ALTER TABLE db_database RENAME TO db_database_60cc1865c51b477cb8c245318bec31aa;


CREATE TABLE db_database (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER NOT NULL  DEFAULT 11 REFERENCES object_type (id),
fk_project_id INTEGER NOT NULL  DEFAULT NULL REFERENCES project (id),
name TEXT DEFAULT NULL,
description TEXT DEFAULT NULL,
fk_tool_id INTEGER NOT NULL  DEFAULT NULL REFERENCES tool (id),
fk_deleted_status_id INTEGER DEFAULT NULL REFERENCES deleted_status (id)
)
;


INSERT INTO db_database
(
    id,uuid,fk_object_type_id,fk_project_id,name,description,fk_tool_id
)
SELECT id,uuid,fk_object_type_id,fk_project_id,name,description,fk_tool_id FROM db_database_60cc1865c51b477cb8c245318bec31aa;



DROP TABLE db_database_60cc1865c51b477cb8c245318bec31aa;





DROP TRIGGER TRIG_data_transfer_process_log_INSERT;

DROP TRIGGER TRIG_data_transfer_process_log_UPDATE;

DROP TRIGGER TRIG_data_transfer_process_log_DELETE;




ALTER TABLE data_transfer_process RENAME TO data_transfer_process_456cd4b795a64999aa9f9862bed9a0aa;


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
fk_deleted_status_id INTEGER DEFAULT NULL REFERENCES deleted_status (id)
)
;


INSERT INTO data_transfer_process
(
    id,uuid,fk_object_type_id,fk_project_id,name,description,fk_data_transfer_type_id,location,source_internal_object_id
)
SELECT id,uuid,fk_object_type_id,fk_project_id,name,description,fk_data_transfer_type_id,location,source_internal_object_id FROM data_transfer_process_456cd4b795a64999aa9f9862bed9a0aa;



DROP TABLE data_transfer_process_456cd4b795a64999aa9f9862bed9a0aa;





DROP TRIGGER TRIG_contact_group_log_INSERT;

DROP TRIGGER TRIG_contact_group_log_UPDATE;

DROP TRIGGER TRIG_contact_group_log_DELETE;



ALTER TABLE contact_group RENAME TO contact_group_8ddff1aa219a44dd8f13c4a056d05771;


CREATE TABLE contact_group (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER DEFAULT 14 REFERENCES object_type (id),
fk_client_id INTEGER NOT NULL  DEFAULT NULL REFERENCES client (id),
name TEXT DEFAULT NULL,
description TEXT DEFAULT NULL,
short_name TEXT DEFAULT NULL,
fk_deleted_status_id INTEGER DEFAULT NULL REFERENCES deleted_status (id)
)
;


INSERT INTO contact_group
(
    id,uuid,fk_object_type_id,fk_client_id,name,description,short_name
)
SELECT id,uuid,fk_object_type_id,fk_client_id,name,description,short_name FROM contact_group_8ddff1aa219a44dd8f13c4a056d05771;



DROP TABLE contact_group_8ddff1aa219a44dd8f13c4a056d05771;





DROP TRIGGER TRIG_contact_log_INSERT;

DROP TRIGGER TRIG_contact_log_UPDATE;

DROP TRIGGER TRIG_contact_log_DELETE;



ALTER TABLE contact RENAME TO contact_472c4f8b8bf743798888fbb4a5eb92af;


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
fk_deleted_status_id INTEGER DEFAULT NULL REFERENCES deleted_status (id)
)
;


INSERT INTO contact
(
    id,uuid,fk_object_type_id,fk_contact_group_id,fk_client_id,givenname,surname,email,phone,mobile,ldap_cn,description
)
SELECT id,uuid,fk_object_type_id,fk_contact_group_id,fk_client_id,givenname,surname,email,phone,mobile,ldap_cn,description FROM contact_472c4f8b8bf743798888fbb4a5eb92af;



DROP TABLE contact_472c4f8b8bf743798888fbb4a5eb92af;

