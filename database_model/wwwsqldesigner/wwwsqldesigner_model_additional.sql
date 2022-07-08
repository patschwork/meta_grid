-- Diese Datei wurde automatisiert ueber das Python-Script create_wwwsqldesigner_model_additional.py erstellt
-- 2022-07-07 03:03:11

PRAGMA foreign_keys = ON;

CREATE TABLE IF NOT EXISTS _newUUID (uuid TEXT);

DROP TABLE IF EXISTS keyfigure_log;
CREATE TABLE keyfigure_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   fk_object_type_id INTEGER,
   fk_object_type_uuid TEXT,
   fk_project_id INTEGER,
   fk_project_uuid TEXT,
   name TEXT(250),
   description TEXT(4000),
   formula TEXT(4000),
   aggregation TEXT(500),
   character TEXT(500),
   type TEXT(500),
   unit TEXT(500),
   value_range TEXT(500),
   cumulation_possible BOOLEAN,
   fk_deleted_status_id INTEGER,
   fk_deleted_status_uuid TEXT,
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT,
   source_definition TEXT,
   source_definition_language TEXT(250),
   source_comment TEXT
);

DROP TRIGGER IF EXISTS TRIG_keyfigure_log_INSERT;
CREATE TRIGGER TRIG_keyfigure_log_INSERT AFTER INSERT
ON keyfigure
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO keyfigure_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,formula,aggregation,character,type,unit,value_range,cumulation_possible,fk_deleted_status_id, fk_deleted_status_uuid,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition,source_definition_language,source_comment) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.formula,new.aggregation,new.character,new.type,new.unit,new.value_range,new.cumulation_possible,new.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=new.fk_deleted_status_id),new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id),new.source_definition,new.source_definition_language,new.source_comment);
   UPDATE keyfigure SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM keyfigure_log WHERE log_id=(SELECT MAX(log_id)+1 FROM keyfigure_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_keyfigure_log_UPDATE;
CREATE TRIGGER TRIG_keyfigure_log_UPDATE AFTER UPDATE
ON keyfigure
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE keyfigure SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO keyfigure_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,formula,aggregation,character,type,unit,value_range,cumulation_possible,fk_deleted_status_id, fk_deleted_status_uuid,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition,source_definition_language,source_comment) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.formula,new.aggregation,new.character,new.type,new.unit,new.value_range,new.cumulation_possible,new.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=new.fk_deleted_status_id),new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id),new.source_definition,new.source_definition_language,new.source_comment);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_keyfigure_log_DELETE;
CREATE TRIGGER TRIG_keyfigure_log_DELETE AFTER DELETE
ON keyfigure
BEGIN
   INSERT INTO keyfigure_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,formula,aggregation,character,type,unit,value_range,cumulation_possible,fk_deleted_status_id, fk_deleted_status_uuid,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition,source_definition_language,source_comment) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.formula,old.aggregation,old.character,old.type,old.unit,old.value_range,old.cumulation_possible,old.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=old.fk_deleted_status_id),old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id),old.source_definition,old.source_definition_language,old.source_comment);

   INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id) VALUES (old.id, old.fk_object_type_id);
END;


DROP TABLE IF EXISTS object_type_log;
CREATE TABLE object_type_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   name TEXT(250)
);

DROP TRIGGER IF EXISTS TRIG_object_type_log_INSERT;
CREATE TRIGGER TRIG_object_type_log_INSERT AFTER INSERT
ON object_type
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO object_type_log (log_action, id,uuid,name) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.name);
   UPDATE object_type SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM object_type_log WHERE log_id=(SELECT MAX(log_id)+1 FROM object_type_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_object_type_log_UPDATE;
CREATE TRIGGER TRIG_object_type_log_UPDATE AFTER UPDATE
ON object_type
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE object_type SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO object_type_log (log_action, id,uuid,name) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.name);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_object_type_log_DELETE;
CREATE TRIGGER TRIG_object_type_log_DELETE AFTER DELETE
ON object_type
BEGIN
   INSERT INTO object_type_log (log_action, id,uuid,name) VALUES ('DELETE',old.id,old.uuid,old.name);
END;


DROP TABLE IF EXISTS client_log;
CREATE TABLE client_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   name TEXT(250),
   description TEXT(500),
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT
);

DROP TRIGGER IF EXISTS TRIG_client_log_INSERT;
CREATE TRIGGER TRIG_client_log_INSERT AFTER INSERT
ON client
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO client_log (log_action, id,uuid,name,description,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.name,new.description,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   UPDATE client SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM client_log WHERE log_id=(SELECT MAX(log_id)+1 FROM client_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_client_log_UPDATE;
CREATE TRIGGER TRIG_client_log_UPDATE AFTER UPDATE
ON client
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE client SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO client_log (log_action, id,uuid,name,description,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.name,new.description,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_client_log_DELETE;
CREATE TRIGGER TRIG_client_log_DELETE AFTER DELETE
ON client
BEGIN
   INSERT INTO client_log (log_action, id,uuid,name,description,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('DELETE',old.id,old.uuid,old.name,old.description,old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id));
END;


DROP TABLE IF EXISTS project_log;
CREATE TABLE project_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   fk_client_id INTEGER,
   fk_client_uuid TEXT,
   name TEXT(250),
   description TEXT(500),
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT
);

DROP TRIGGER IF EXISTS TRIG_project_log_INSERT;
CREATE TRIGGER TRIG_project_log_INSERT AFTER INSERT
ON project
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO project_log (log_action, id,uuid,fk_client_id, fk_client_uuid,name,description,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_client_id, (SELECT uuid FROM client WHERE id=new.fk_client_id),new.name,new.description,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   UPDATE project SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM project_log WHERE log_id=(SELECT MAX(log_id)+1 FROM project_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_project_log_UPDATE;
CREATE TRIGGER TRIG_project_log_UPDATE AFTER UPDATE
ON project
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE project SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO project_log (log_action, id,uuid,fk_client_id, fk_client_uuid,name,description,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_client_id, (SELECT uuid FROM client WHERE id=new.fk_client_id),new.name,new.description,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_project_log_DELETE;
CREATE TRIGGER TRIG_project_log_DELETE AFTER DELETE
ON project
BEGIN
   INSERT INTO project_log (log_action, id,uuid,fk_client_id, fk_client_uuid,name,description,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('DELETE',old.id,old.uuid,old.fk_client_id, (SELECT uuid FROM client WHERE id=old.fk_client_id),old.name,old.description,old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id));
END;


DROP TABLE IF EXISTS sourcesystem_log;
CREATE TABLE sourcesystem_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   fk_object_type_id INTEGER,
   fk_object_type_uuid TEXT,
   fk_project_id INTEGER,
   fk_project_uuid TEXT,
   name TEXT(250),
   description TEXT(500),
   fk_contact_group_id_as_supporter INTEGER,
   fk_contact_group_uuid_as_supporter TEXT,
   fk_deleted_status_id INTEGER,
   fk_deleted_status_uuid TEXT,
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT
);

DROP TRIGGER IF EXISTS TRIG_sourcesystem_log_INSERT;
CREATE TRIGGER TRIG_sourcesystem_log_INSERT AFTER INSERT
ON sourcesystem
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO sourcesystem_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_contact_group_id_as_supporter, fk_contact_group_uuid_as_supporter,fk_deleted_status_id, fk_deleted_status_uuid,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_contact_group_id_as_supporter, (SELECT uuid FROM contact_group WHERE id=new.fk_contact_group_id_as_supporter),new.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=new.fk_deleted_status_id),new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   UPDATE sourcesystem SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM sourcesystem_log WHERE log_id=(SELECT MAX(log_id)+1 FROM sourcesystem_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_sourcesystem_log_UPDATE;
CREATE TRIGGER TRIG_sourcesystem_log_UPDATE AFTER UPDATE
ON sourcesystem
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE sourcesystem SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO sourcesystem_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_contact_group_id_as_supporter, fk_contact_group_uuid_as_supporter,fk_deleted_status_id, fk_deleted_status_uuid,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_contact_group_id_as_supporter, (SELECT uuid FROM contact_group WHERE id=new.fk_contact_group_id_as_supporter),new.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=new.fk_deleted_status_id),new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_sourcesystem_log_DELETE;
CREATE TRIGGER TRIG_sourcesystem_log_DELETE AFTER DELETE
ON sourcesystem
BEGIN
   INSERT INTO sourcesystem_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_contact_group_id_as_supporter, fk_contact_group_uuid_as_supporter,fk_deleted_status_id, fk_deleted_status_uuid,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.fk_contact_group_id_as_supporter, (SELECT uuid FROM contact_group WHERE id=old.fk_contact_group_id_as_supporter),old.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=old.fk_deleted_status_id),old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id));

   INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id) VALUES (old.id, old.fk_object_type_id);
END;


DROP TABLE IF EXISTS data_delivery_object_log;
CREATE TABLE data_delivery_object_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   fk_object_type_id INTEGER,
   fk_object_type_uuid TEXT,
   fk_project_id INTEGER,
   fk_project_uuid TEXT,
   name TEXT(250),
   description TEXT(500),
   fk_tool_id INTEGER,
   fk_tool_uuid TEXT,
   fk_data_delivery_type_id INTEGER,
   fk_data_delivery_type_uuid TEXT,
   fk_contact_group_id_as_data_owner INTEGER,
   fk_contact_group_uuid_as_data_owner TEXT,
   fk_deleted_status_id INTEGER,
   fk_deleted_status_uuid TEXT,
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT,
   source_definition TEXT,
   source_comment TEXT
);

DROP TRIGGER IF EXISTS TRIG_data_delivery_object_log_INSERT;
CREATE TRIGGER TRIG_data_delivery_object_log_INSERT AFTER INSERT
ON data_delivery_object
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO data_delivery_object_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_tool_id, fk_tool_uuid,fk_data_delivery_type_id, fk_data_delivery_type_uuid,fk_contact_group_id_as_data_owner, fk_contact_group_uuid_as_data_owner,fk_deleted_status_id, fk_deleted_status_uuid,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition,source_comment) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_tool_id, (SELECT uuid FROM tool WHERE id=new.fk_tool_id),new.fk_data_delivery_type_id, (SELECT uuid FROM data_delivery_type WHERE id=new.fk_data_delivery_type_id),new.fk_contact_group_id_as_data_owner, (SELECT uuid FROM contact_group WHERE id=new.fk_contact_group_id_as_data_owner),new.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=new.fk_deleted_status_id),new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id),new.source_definition,new.source_comment);
   UPDATE data_delivery_object SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM data_delivery_object_log WHERE log_id=(SELECT MAX(log_id)+1 FROM data_delivery_object_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_data_delivery_object_log_UPDATE;
CREATE TRIGGER TRIG_data_delivery_object_log_UPDATE AFTER UPDATE
ON data_delivery_object
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE data_delivery_object SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO data_delivery_object_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_tool_id, fk_tool_uuid,fk_data_delivery_type_id, fk_data_delivery_type_uuid,fk_contact_group_id_as_data_owner, fk_contact_group_uuid_as_data_owner,fk_deleted_status_id, fk_deleted_status_uuid,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition,source_comment) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_tool_id, (SELECT uuid FROM tool WHERE id=new.fk_tool_id),new.fk_data_delivery_type_id, (SELECT uuid FROM data_delivery_type WHERE id=new.fk_data_delivery_type_id),new.fk_contact_group_id_as_data_owner, (SELECT uuid FROM contact_group WHERE id=new.fk_contact_group_id_as_data_owner),new.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=new.fk_deleted_status_id),new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id),new.source_definition,new.source_comment);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_data_delivery_object_log_DELETE;
CREATE TRIGGER TRIG_data_delivery_object_log_DELETE AFTER DELETE
ON data_delivery_object
BEGIN
   INSERT INTO data_delivery_object_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_tool_id, fk_tool_uuid,fk_data_delivery_type_id, fk_data_delivery_type_uuid,fk_contact_group_id_as_data_owner, fk_contact_group_uuid_as_data_owner,fk_deleted_status_id, fk_deleted_status_uuid,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition,source_comment) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.fk_tool_id, (SELECT uuid FROM tool WHERE id=old.fk_tool_id),old.fk_data_delivery_type_id, (SELECT uuid FROM data_delivery_type WHERE id=old.fk_data_delivery_type_id),old.fk_contact_group_id_as_data_owner, (SELECT uuid FROM contact_group WHERE id=old.fk_contact_group_id_as_data_owner),old.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=old.fk_deleted_status_id),old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id),old.source_definition,old.source_comment);

   INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id) VALUES (old.id, old.fk_object_type_id);
END;


DROP TABLE IF EXISTS map_object_2_object_log;
CREATE TABLE map_object_2_object_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   fk_mapping_qualifier_id INTEGER,
   fk_mapping_qualifier_uuid TEXT,
   ref_fk_object_id_1 INTEGER,
   ref_fk_object_type_id_1 INTEGER,
   ref_fk_object_id_2 INTEGER,
   ref_fk_object_type_id_2 INTEGER,
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT
-- Wegen des UNIQUE muss das Komma immer am Ende entfernt werden! Siehe auch TRIGGER!
);

DROP TRIGGER IF EXISTS TRIG_map_object_2_object_log_INSERT;
CREATE TRIGGER TRIG_map_object_2_object_log_INSERT AFTER INSERT
ON map_object_2_object
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO map_object_2_object_log (log_action, id,uuid,fk_mapping_qualifier_id, fk_mapping_qualifier_uuid,ref_fk_object_id_1,ref_fk_object_type_id_1,ref_fk_object_id_2,ref_fk_object_type_id_2,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_mapping_qualifier_id, (SELECT uuid FROM mapping_qualifier WHERE id=new.fk_mapping_qualifier_id),new.ref_fk_object_id_1,new.ref_fk_object_type_id_1,new.ref_fk_object_id_2,new.ref_fk_object_type_id_2,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   UPDATE map_object_2_object SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM map_object_2_object_log WHERE log_id=(SELECT MAX(log_id)+1 FROM map_object_2_object_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_map_object_2_object_log_UPDATE;
CREATE TRIGGER TRIG_map_object_2_object_log_UPDATE AFTER UPDATE
ON map_object_2_object
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE map_object_2_object SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO map_object_2_object_log (log_action, id,uuid,fk_mapping_qualifier_id, fk_mapping_qualifier_uuid,ref_fk_object_id_1,ref_fk_object_type_id_1,ref_fk_object_id_2,ref_fk_object_type_id_2,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_mapping_qualifier_id, (SELECT uuid FROM mapping_qualifier WHERE id=new.fk_mapping_qualifier_id),new.ref_fk_object_id_1,new.ref_fk_object_type_id_1,new.ref_fk_object_id_2,new.ref_fk_object_type_id_2,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_map_object_2_object_log_DELETE;
CREATE TRIGGER TRIG_map_object_2_object_log_DELETE AFTER DELETE
ON map_object_2_object
BEGIN
   INSERT INTO map_object_2_object_log (log_action, id,uuid,fk_mapping_qualifier_id, fk_mapping_qualifier_uuid,ref_fk_object_id_1,ref_fk_object_type_id_1,ref_fk_object_id_2,ref_fk_object_type_id_2,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('DELETE',old.id,old.uuid,old.fk_mapping_qualifier_id, (SELECT uuid FROM mapping_qualifier WHERE id=old.fk_mapping_qualifier_id),old.ref_fk_object_id_1,old.ref_fk_object_type_id_1,old.ref_fk_object_id_2,old.ref_fk_object_type_id_2,old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id));
END;


DROP TABLE IF EXISTS tool_log;
CREATE TABLE tool_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   fk_tool_type_id INTEGER,
   fk_tool_type_uuid TEXT,
   tool_name TEXT(255),
   vendor TEXT(255),
   version TEXT(255),
   comment TEXT(500),
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT
);

DROP TRIGGER IF EXISTS TRIG_tool_log_INSERT;
CREATE TRIGGER TRIG_tool_log_INSERT AFTER INSERT
ON tool
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO tool_log (log_action, id,uuid,fk_tool_type_id, fk_tool_type_uuid,tool_name,vendor,version,comment,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_tool_type_id, (SELECT uuid FROM tool_type WHERE id=new.fk_tool_type_id),new.tool_name,new.vendor,new.version,new.comment,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   UPDATE tool SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM tool_log WHERE log_id=(SELECT MAX(log_id)+1 FROM tool_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_tool_log_UPDATE;
CREATE TRIGGER TRIG_tool_log_UPDATE AFTER UPDATE
ON tool
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE tool SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO tool_log (log_action, id,uuid,fk_tool_type_id, fk_tool_type_uuid,tool_name,vendor,version,comment,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_tool_type_id, (SELECT uuid FROM tool_type WHERE id=new.fk_tool_type_id),new.tool_name,new.vendor,new.version,new.comment,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_tool_log_DELETE;
CREATE TRIGGER TRIG_tool_log_DELETE AFTER DELETE
ON tool
BEGIN
   INSERT INTO tool_log (log_action, id,uuid,fk_tool_type_id, fk_tool_type_uuid,tool_name,vendor,version,comment,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('DELETE',old.id,old.uuid,old.fk_tool_type_id, (SELECT uuid FROM tool_type WHERE id=old.fk_tool_type_id),old.tool_name,old.vendor,old.version,old.comment,old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id));
END;


DROP TABLE IF EXISTS object_depends_on_log;
CREATE TABLE object_depends_on_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   ref_fk_object_id_parent INTEGER,
   ref_fk_object_type_id_parent INTEGER,
   ref_fk_object_id_child INTEGER,
   ref_fk_object_type_id_child INTEGER,
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT
);

DROP TRIGGER IF EXISTS TRIG_object_depends_on_log_INSERT;
CREATE TRIGGER TRIG_object_depends_on_log_INSERT AFTER INSERT
ON object_depends_on
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO object_depends_on_log (log_action, id,uuid,ref_fk_object_id_parent,ref_fk_object_type_id_parent,ref_fk_object_id_child,ref_fk_object_type_id_child,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.ref_fk_object_id_parent,new.ref_fk_object_type_id_parent,new.ref_fk_object_id_child,new.ref_fk_object_type_id_child,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   UPDATE object_depends_on SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM object_depends_on_log WHERE log_id=(SELECT MAX(log_id)+1 FROM object_depends_on_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_object_depends_on_log_UPDATE;
CREATE TRIGGER TRIG_object_depends_on_log_UPDATE AFTER UPDATE
ON object_depends_on
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE object_depends_on SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO object_depends_on_log (log_action, id,uuid,ref_fk_object_id_parent,ref_fk_object_type_id_parent,ref_fk_object_id_child,ref_fk_object_type_id_child,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.ref_fk_object_id_parent,new.ref_fk_object_type_id_parent,new.ref_fk_object_id_child,new.ref_fk_object_type_id_child,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_object_depends_on_log_DELETE;
CREATE TRIGGER TRIG_object_depends_on_log_DELETE AFTER DELETE
ON object_depends_on
BEGIN
   INSERT INTO object_depends_on_log (log_action, id,uuid,ref_fk_object_id_parent,ref_fk_object_type_id_parent,ref_fk_object_id_child,ref_fk_object_type_id_child,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('DELETE',old.id,old.uuid,old.ref_fk_object_id_parent,old.ref_fk_object_type_id_parent,old.ref_fk_object_id_child,old.ref_fk_object_type_id_child,old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id));
END;


DROP TABLE IF EXISTS attribute_log;
CREATE TABLE attribute_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   fk_object_type_id INTEGER,
   fk_object_type_uuid TEXT,
   fk_project_id INTEGER,
   fk_project_uuid TEXT,
   name TEXT(250),
   description TEXT(4000),
   formula TEXT(4000),
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT
);

DROP TRIGGER IF EXISTS TRIG_attribute_log_INSERT;
CREATE TRIGGER TRIG_attribute_log_INSERT AFTER INSERT
ON attribute
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO attribute_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,formula,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.formula,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   UPDATE attribute SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM attribute_log WHERE log_id=(SELECT MAX(log_id)+1 FROM attribute_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_attribute_log_UPDATE;
CREATE TRIGGER TRIG_attribute_log_UPDATE AFTER UPDATE
ON attribute
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE attribute SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO attribute_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,formula,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.formula,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_attribute_log_DELETE;
CREATE TRIGGER TRIG_attribute_log_DELETE AFTER DELETE
ON attribute
BEGIN
   INSERT INTO attribute_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,formula,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.formula,old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id));

   INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id) VALUES (old.id, old.fk_object_type_id);
END;


DROP TABLE IF EXISTS tool_type_log;
CREATE TABLE tool_type_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   name TEXT(250),
   description TEXT(500),
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT
);

DROP TRIGGER IF EXISTS TRIG_tool_type_log_INSERT;
CREATE TRIGGER TRIG_tool_type_log_INSERT AFTER INSERT
ON tool_type
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO tool_type_log (log_action, id,uuid,name,description,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.name,new.description,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   UPDATE tool_type SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM tool_type_log WHERE log_id=(SELECT MAX(log_id)+1 FROM tool_type_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_tool_type_log_UPDATE;
CREATE TRIGGER TRIG_tool_type_log_UPDATE AFTER UPDATE
ON tool_type
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE tool_type SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO tool_type_log (log_action, id,uuid,name,description,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.name,new.description,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_tool_type_log_DELETE;
CREATE TRIGGER TRIG_tool_type_log_DELETE AFTER DELETE
ON tool_type
BEGIN
   INSERT INTO tool_type_log (log_action, id,uuid,name,description,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('DELETE',old.id,old.uuid,old.name,old.description,old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id));
END;


DROP TABLE IF EXISTS db_table_log;
CREATE TABLE db_table_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   fk_object_type_id INTEGER,
   fk_object_type_uuid TEXT,
   fk_project_id INTEGER,
   fk_project_uuid TEXT,
   name TEXT(250),
   description TEXT(4000),
   location TEXT,
   fk_db_table_context_id INTEGER,
   fk_db_table_context_uuid TEXT,
   fk_db_table_type_id INTEGER,
   fk_db_table_type_uuid TEXT,
   fk_deleted_status_id INTEGER,
   fk_deleted_status_uuid TEXT,
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT,
   source_definition TEXT,
   source_comment TEXT
);

DROP TRIGGER IF EXISTS TRIG_db_table_log_INSERT;
CREATE TRIGGER TRIG_db_table_log_INSERT AFTER INSERT
ON db_table
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO db_table_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,location,fk_db_table_context_id, fk_db_table_context_uuid,fk_db_table_type_id, fk_db_table_type_uuid,fk_deleted_status_id, fk_deleted_status_uuid,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition,source_comment) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.location,new.fk_db_table_context_id, (SELECT uuid FROM db_table_context WHERE id=new.fk_db_table_context_id),new.fk_db_table_type_id, (SELECT uuid FROM db_table_type WHERE id=new.fk_db_table_type_id),new.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=new.fk_deleted_status_id),new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id),new.source_definition,new.source_comment);
   UPDATE db_table SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM db_table_log WHERE log_id=(SELECT MAX(log_id)+1 FROM db_table_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_db_table_log_UPDATE;
CREATE TRIGGER TRIG_db_table_log_UPDATE AFTER UPDATE
ON db_table
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE db_table SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO db_table_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,location,fk_db_table_context_id, fk_db_table_context_uuid,fk_db_table_type_id, fk_db_table_type_uuid,fk_deleted_status_id, fk_deleted_status_uuid,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition,source_comment) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.location,new.fk_db_table_context_id, (SELECT uuid FROM db_table_context WHERE id=new.fk_db_table_context_id),new.fk_db_table_type_id, (SELECT uuid FROM db_table_type WHERE id=new.fk_db_table_type_id),new.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=new.fk_deleted_status_id),new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id),new.source_definition,new.source_comment);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_db_table_log_DELETE;
CREATE TRIGGER TRIG_db_table_log_DELETE AFTER DELETE
ON db_table
BEGIN
   INSERT INTO db_table_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,location,fk_db_table_context_id, fk_db_table_context_uuid,fk_db_table_type_id, fk_db_table_type_uuid,fk_deleted_status_id, fk_deleted_status_uuid,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition,source_comment) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.location,old.fk_db_table_context_id, (SELECT uuid FROM db_table_context WHERE id=old.fk_db_table_context_id),old.fk_db_table_type_id, (SELECT uuid FROM db_table_type WHERE id=old.fk_db_table_type_id),old.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=old.fk_deleted_status_id),old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id),old.source_definition,old.source_comment);

   INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id) VALUES (old.id, old.fk_object_type_id);
END;


DROP TABLE IF EXISTS db_table_field_log;
CREATE TABLE db_table_field_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   fk_object_type_id INTEGER,
   fk_object_type_uuid TEXT,
   fk_project_id INTEGER,
   fk_project_uuid TEXT,
   name TEXT(250),
   description TEXT(500),
   fk_db_table_id INTEGER,
   fk_db_table_uuid TEXT,
   datatype TEXT(250),
   bulk_load_checksum TEXT(200),
   fk_deleted_status_id INTEGER,
   fk_deleted_status_uuid TEXT,
   is_PrimaryKey BOOLEAN,
   is_BusinessKey BOOLEAN,
   is_GDPR_relevant BOOLEAN,
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT,
   source_definition TEXT,
   source_comment TEXT
);

DROP TRIGGER IF EXISTS TRIG_db_table_field_log_INSERT;
CREATE TRIGGER TRIG_db_table_field_log_INSERT AFTER INSERT
ON db_table_field
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO db_table_field_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_db_table_id, fk_db_table_uuid,datatype,bulk_load_checksum,fk_deleted_status_id, fk_deleted_status_uuid,is_PrimaryKey,is_BusinessKey,is_GDPR_relevant,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition,source_comment) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_db_table_id, (SELECT uuid FROM db_table WHERE id=new.fk_db_table_id),new.datatype,new.bulk_load_checksum,new.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=new.fk_deleted_status_id),new.is_PrimaryKey,new.is_BusinessKey,new.is_GDPR_relevant,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id),new.source_definition,new.source_comment);
   UPDATE db_table_field SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM db_table_field_log WHERE log_id=(SELECT MAX(log_id)+1 FROM db_table_field_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_db_table_field_log_UPDATE;
CREATE TRIGGER TRIG_db_table_field_log_UPDATE AFTER UPDATE
ON db_table_field
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE db_table_field SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO db_table_field_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_db_table_id, fk_db_table_uuid,datatype,bulk_load_checksum,fk_deleted_status_id, fk_deleted_status_uuid,is_PrimaryKey,is_BusinessKey,is_GDPR_relevant,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition,source_comment) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_db_table_id, (SELECT uuid FROM db_table WHERE id=new.fk_db_table_id),new.datatype,new.bulk_load_checksum,new.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=new.fk_deleted_status_id),new.is_PrimaryKey,new.is_BusinessKey,new.is_GDPR_relevant,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id),new.source_definition,new.source_comment);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_db_table_field_log_DELETE;
CREATE TRIGGER TRIG_db_table_field_log_DELETE AFTER DELETE
ON db_table_field
BEGIN
   INSERT INTO db_table_field_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_db_table_id, fk_db_table_uuid,datatype,bulk_load_checksum,fk_deleted_status_id, fk_deleted_status_uuid,is_PrimaryKey,is_BusinessKey,is_GDPR_relevant,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition,source_comment) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.fk_db_table_id, (SELECT uuid FROM db_table WHERE id=old.fk_db_table_id),old.datatype,old.bulk_load_checksum,old.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=old.fk_deleted_status_id),old.is_PrimaryKey,old.is_BusinessKey,old.is_GDPR_relevant,old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id),old.source_definition,old.source_comment);

   INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id) VALUES (old.id, old.fk_object_type_id);
END;


DROP TABLE IF EXISTS db_table_context_log;
CREATE TABLE db_table_context_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   fk_object_type_id INTEGER,
   fk_object_type_uuid TEXT,
   fk_project_id INTEGER,
   fk_project_uuid TEXT,
   name TEXT(250),
   description TEXT(500),
   prefix TEXT(100),
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT
);

DROP TRIGGER IF EXISTS TRIG_db_table_context_log_INSERT;
CREATE TRIGGER TRIG_db_table_context_log_INSERT AFTER INSERT
ON db_table_context
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO db_table_context_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,prefix,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.prefix,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   UPDATE db_table_context SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM db_table_context_log WHERE log_id=(SELECT MAX(log_id)+1 FROM db_table_context_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_db_table_context_log_UPDATE;
CREATE TRIGGER TRIG_db_table_context_log_UPDATE AFTER UPDATE
ON db_table_context
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE db_table_context SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO db_table_context_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,prefix,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.prefix,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_db_table_context_log_DELETE;
CREATE TRIGGER TRIG_db_table_context_log_DELETE AFTER DELETE
ON db_table_context
BEGIN
   INSERT INTO db_table_context_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,prefix,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.prefix,old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id));

   INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id) VALUES (old.id, old.fk_object_type_id);
END;


DROP TABLE IF EXISTS db_database_log;
CREATE TABLE db_database_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   fk_object_type_id INTEGER,
   fk_object_type_uuid TEXT,
   fk_project_id INTEGER,
   fk_project_uuid TEXT,
   name TEXT,
   description TEXT,
   fk_tool_id INTEGER,
   fk_tool_uuid TEXT,
   fk_deleted_status_id INTEGER,
   fk_deleted_status_uuid TEXT,
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT,
   source_definition TEXT,
   source_definition_language TEXT(250),
   source_comment TEXT
);

DROP TRIGGER IF EXISTS TRIG_db_database_log_INSERT;
CREATE TRIGGER TRIG_db_database_log_INSERT AFTER INSERT
ON db_database
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO db_database_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_tool_id, fk_tool_uuid,fk_deleted_status_id, fk_deleted_status_uuid,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition,source_definition_language,source_comment) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_tool_id, (SELECT uuid FROM tool WHERE id=new.fk_tool_id),new.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=new.fk_deleted_status_id),new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id),new.source_definition,new.source_definition_language,new.source_comment);
   UPDATE db_database SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM db_database_log WHERE log_id=(SELECT MAX(log_id)+1 FROM db_database_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_db_database_log_UPDATE;
CREATE TRIGGER TRIG_db_database_log_UPDATE AFTER UPDATE
ON db_database
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE db_database SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO db_database_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_tool_id, fk_tool_uuid,fk_deleted_status_id, fk_deleted_status_uuid,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition,source_definition_language,source_comment) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_tool_id, (SELECT uuid FROM tool WHERE id=new.fk_tool_id),new.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=new.fk_deleted_status_id),new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id),new.source_definition,new.source_definition_language,new.source_comment);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_db_database_log_DELETE;
CREATE TRIGGER TRIG_db_database_log_DELETE AFTER DELETE
ON db_database
BEGIN
   INSERT INTO db_database_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_tool_id, fk_tool_uuid,fk_deleted_status_id, fk_deleted_status_uuid,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition,source_definition_language,source_comment) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.fk_tool_id, (SELECT uuid FROM tool WHERE id=old.fk_tool_id),old.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=old.fk_deleted_status_id),old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id),old.source_definition,old.source_definition_language,old.source_comment);

   INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id) VALUES (old.id, old.fk_object_type_id);
END;


DROP TABLE IF EXISTS scheduling_log;
CREATE TABLE scheduling_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   fk_object_type_id INTEGER,
   fk_object_type_uuid TEXT,
   fk_project_id INTEGER,
   fk_project_uuid TEXT,
   name TEXT,
   description TEXT,
   fk_tool_id INTEGER,
   fk_tool_uuid TEXT,
   scheduling_series TEXT,
   fk_deleted_status_id INTEGER,
   fk_deleted_status_uuid TEXT,
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT,
   source_definition TEXT,
   source_definition_language TEXT(250),
   source_comment TEXT
);

DROP TRIGGER IF EXISTS TRIG_scheduling_log_INSERT;
CREATE TRIGGER TRIG_scheduling_log_INSERT AFTER INSERT
ON scheduling
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO scheduling_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_tool_id, fk_tool_uuid,scheduling_series,fk_deleted_status_id, fk_deleted_status_uuid,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition,source_definition_language,source_comment) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_tool_id, (SELECT uuid FROM tool WHERE id=new.fk_tool_id),new.scheduling_series,new.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=new.fk_deleted_status_id),new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id),new.source_definition,new.source_definition_language,new.source_comment);
   UPDATE scheduling SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM scheduling_log WHERE log_id=(SELECT MAX(log_id)+1 FROM scheduling_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_scheduling_log_UPDATE;
CREATE TRIGGER TRIG_scheduling_log_UPDATE AFTER UPDATE
ON scheduling
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE scheduling SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO scheduling_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_tool_id, fk_tool_uuid,scheduling_series,fk_deleted_status_id, fk_deleted_status_uuid,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition,source_definition_language,source_comment) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_tool_id, (SELECT uuid FROM tool WHERE id=new.fk_tool_id),new.scheduling_series,new.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=new.fk_deleted_status_id),new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id),new.source_definition,new.source_definition_language,new.source_comment);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_scheduling_log_DELETE;
CREATE TRIGGER TRIG_scheduling_log_DELETE AFTER DELETE
ON scheduling
BEGIN
   INSERT INTO scheduling_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_tool_id, fk_tool_uuid,scheduling_series,fk_deleted_status_id, fk_deleted_status_uuid,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition,source_definition_language,source_comment) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.fk_tool_id, (SELECT uuid FROM tool WHERE id=old.fk_tool_id),old.scheduling_series,old.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=old.fk_deleted_status_id),old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id),old.source_definition,old.source_definition_language,old.source_comment);

   INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id) VALUES (old.id, old.fk_object_type_id);
END;


DROP TABLE IF EXISTS parameter_log;
CREATE TABLE parameter_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   fk_object_type_id INTEGER,
   fk_object_type_uuid TEXT,
   fk_project_id INTEGER,
   fk_project_uuid TEXT,
   name TEXT,
   description TEXT,
   is_optional INTEGER,
   default_value TEXT,
   datatype TEXT,
   range TEXT,
   fk_deleted_status_id INTEGER,
   fk_deleted_status_uuid TEXT,
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT,
   source_definition TEXT,
   source_definition_language TEXT(250),
   source_comment TEXT
);

DROP TRIGGER IF EXISTS TRIG_parameter_log_INSERT;
CREATE TRIGGER TRIG_parameter_log_INSERT AFTER INSERT
ON parameter
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO parameter_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,is_optional,default_value,datatype,range,fk_deleted_status_id, fk_deleted_status_uuid,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition,source_definition_language,source_comment) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.is_optional,new.default_value,new.datatype,new.range,new.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=new.fk_deleted_status_id),new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id),new.source_definition,new.source_definition_language,new.source_comment);
   UPDATE parameter SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM parameter_log WHERE log_id=(SELECT MAX(log_id)+1 FROM parameter_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_parameter_log_UPDATE;
CREATE TRIGGER TRIG_parameter_log_UPDATE AFTER UPDATE
ON parameter
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE parameter SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO parameter_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,is_optional,default_value,datatype,range,fk_deleted_status_id, fk_deleted_status_uuid,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition,source_definition_language,source_comment) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.is_optional,new.default_value,new.datatype,new.range,new.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=new.fk_deleted_status_id),new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id),new.source_definition,new.source_definition_language,new.source_comment);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_parameter_log_DELETE;
CREATE TRIGGER TRIG_parameter_log_DELETE AFTER DELETE
ON parameter
BEGIN
   INSERT INTO parameter_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,is_optional,default_value,datatype,range,fk_deleted_status_id, fk_deleted_status_uuid,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition,source_definition_language,source_comment) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.is_optional,old.default_value,old.datatype,old.range,old.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=old.fk_deleted_status_id),old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id),old.source_definition,old.source_definition_language,old.source_comment);

   INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id) VALUES (old.id, old.fk_object_type_id);
END;


DROP TABLE IF EXISTS db_table_type_log;
CREATE TABLE db_table_type_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   name TEXT,
   description TEXT,
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT,
   source_definition_language TEXT(250)
);

DROP TRIGGER IF EXISTS TRIG_db_table_type_log_INSERT;
CREATE TRIGGER TRIG_db_table_type_log_INSERT AFTER INSERT
ON db_table_type
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO db_table_type_log (log_action, id,uuid,name,description,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition_language) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.name,new.description,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id),new.source_definition_language);
   UPDATE db_table_type SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM db_table_type_log WHERE log_id=(SELECT MAX(log_id)+1 FROM db_table_type_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_db_table_type_log_UPDATE;
CREATE TRIGGER TRIG_db_table_type_log_UPDATE AFTER UPDATE
ON db_table_type
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE db_table_type SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO db_table_type_log (log_action, id,uuid,name,description,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition_language) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.name,new.description,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id),new.source_definition_language);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_db_table_type_log_DELETE;
CREATE TRIGGER TRIG_db_table_type_log_DELETE AFTER DELETE
ON db_table_type
BEGIN
   INSERT INTO db_table_type_log (log_action, id,uuid,name,description,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition_language) VALUES ('DELETE',old.id,old.uuid,old.name,old.description,old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id),old.source_definition_language);
END;


DROP TABLE IF EXISTS object_comment_log;
CREATE TABLE object_comment_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   fk_object_type_id INTEGER,
   fk_object_type_uuid TEXT,
   ref_fk_object_id INTEGER,
   ref_fk_object_type_id INTEGER,
   comment TEXT,
   created_at_datetime TEXT,
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT
);

DROP TRIGGER IF EXISTS TRIG_object_comment_log_INSERT;
CREATE TRIGGER TRIG_object_comment_log_INSERT AFTER INSERT
ON object_comment
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO object_comment_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,ref_fk_object_id,ref_fk_object_type_id,comment,created_at_datetime,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.ref_fk_object_id,new.ref_fk_object_type_id,new.comment,new.created_at_datetime,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   UPDATE object_comment SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM object_comment_log WHERE log_id=(SELECT MAX(log_id)+1 FROM object_comment_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_object_comment_log_UPDATE;
CREATE TRIGGER TRIG_object_comment_log_UPDATE AFTER UPDATE
ON object_comment
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE object_comment SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO object_comment_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,ref_fk_object_id,ref_fk_object_type_id,comment,created_at_datetime,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.ref_fk_object_id,new.ref_fk_object_type_id,new.comment,new.created_at_datetime,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_object_comment_log_DELETE;
CREATE TRIGGER TRIG_object_comment_log_DELETE AFTER DELETE
ON object_comment
BEGIN
   INSERT INTO object_comment_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,ref_fk_object_id,ref_fk_object_type_id,comment,created_at_datetime,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.ref_fk_object_id,old.ref_fk_object_type_id,old.comment,old.created_at_datetime,old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id));

   INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id) VALUES (old.id, old.fk_object_type_id);
END;


DROP TABLE IF EXISTS glossary_log;
CREATE TABLE glossary_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   fk_object_type_id INTEGER,
   fk_object_type_uuid TEXT,
   fk_project_id INTEGER,
   fk_project_uuid TEXT,
   name TEXT,
   description TEXT,
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT
);

DROP TRIGGER IF EXISTS TRIG_glossary_log_INSERT;
CREATE TRIGGER TRIG_glossary_log_INSERT AFTER INSERT
ON glossary
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO glossary_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   UPDATE glossary SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM glossary_log WHERE log_id=(SELECT MAX(log_id)+1 FROM glossary_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_glossary_log_UPDATE;
CREATE TRIGGER TRIG_glossary_log_UPDATE AFTER UPDATE
ON glossary
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE glossary SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO glossary_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_glossary_log_DELETE;
CREATE TRIGGER TRIG_glossary_log_DELETE AFTER DELETE
ON glossary
BEGIN
   INSERT INTO glossary_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id));

   INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id) VALUES (old.id, old.fk_object_type_id);
END;


DROP TABLE IF EXISTS data_delivery_type_log;
CREATE TABLE data_delivery_type_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   name TEXT(250),
   description TEXT(500),
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT,
   source_definition_language TEXT(250)
);

DROP TRIGGER IF EXISTS TRIG_data_delivery_type_log_INSERT;
CREATE TRIGGER TRIG_data_delivery_type_log_INSERT AFTER INSERT
ON data_delivery_type
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO data_delivery_type_log (log_action, id,uuid,name,description,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition_language) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.name,new.description,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id),new.source_definition_language);
   UPDATE data_delivery_type SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM data_delivery_type_log WHERE log_id=(SELECT MAX(log_id)+1 FROM data_delivery_type_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_data_delivery_type_log_UPDATE;
CREATE TRIGGER TRIG_data_delivery_type_log_UPDATE AFTER UPDATE
ON data_delivery_type
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE data_delivery_type SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO data_delivery_type_log (log_action, id,uuid,name,description,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition_language) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.name,new.description,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id),new.source_definition_language);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_data_delivery_type_log_DELETE;
CREATE TRIGGER TRIG_data_delivery_type_log_DELETE AFTER DELETE
ON data_delivery_type
BEGIN
   INSERT INTO data_delivery_type_log (log_action, id,uuid,name,description,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition_language) VALUES ('DELETE',old.id,old.uuid,old.name,old.description,old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id),old.source_definition_language);
END;


DROP TABLE IF EXISTS data_transfer_process_log;
CREATE TABLE data_transfer_process_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   fk_object_type_id INTEGER,
   fk_object_type_uuid TEXT,
   fk_project_id INTEGER,
   fk_project_uuid TEXT,
   name TEXT(250),
   description TEXT(4000),
   fk_data_transfer_type_id INTEGER,
   fk_data_transfer_type_uuid TEXT,
   location TEXT(500),
   source_internal_object_id TEXT(500),
   fk_deleted_status_id INTEGER,
   fk_deleted_status_uuid TEXT,
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT,
   source_definition TEXT,
   source_comment TEXT
);

DROP TRIGGER IF EXISTS TRIG_data_transfer_process_log_INSERT;
CREATE TRIGGER TRIG_data_transfer_process_log_INSERT AFTER INSERT
ON data_transfer_process
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO data_transfer_process_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_data_transfer_type_id, fk_data_transfer_type_uuid,location,source_internal_object_id,fk_deleted_status_id, fk_deleted_status_uuid,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition,source_comment) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_data_transfer_type_id, (SELECT uuid FROM data_transfer_type WHERE id=new.fk_data_transfer_type_id),new.location,new.source_internal_object_id,new.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=new.fk_deleted_status_id),new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id),new.source_definition,new.source_comment);
   UPDATE data_transfer_process SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM data_transfer_process_log WHERE log_id=(SELECT MAX(log_id)+1 FROM data_transfer_process_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_data_transfer_process_log_UPDATE;
CREATE TRIGGER TRIG_data_transfer_process_log_UPDATE AFTER UPDATE
ON data_transfer_process
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE data_transfer_process SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO data_transfer_process_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_data_transfer_type_id, fk_data_transfer_type_uuid,location,source_internal_object_id,fk_deleted_status_id, fk_deleted_status_uuid,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition,source_comment) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_data_transfer_type_id, (SELECT uuid FROM data_transfer_type WHERE id=new.fk_data_transfer_type_id),new.location,new.source_internal_object_id,new.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=new.fk_deleted_status_id),new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id),new.source_definition,new.source_comment);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_data_transfer_process_log_DELETE;
CREATE TRIGGER TRIG_data_transfer_process_log_DELETE AFTER DELETE
ON data_transfer_process
BEGIN
   INSERT INTO data_transfer_process_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_data_transfer_type_id, fk_data_transfer_type_uuid,location,source_internal_object_id,fk_deleted_status_id, fk_deleted_status_uuid,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition,source_comment) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.fk_data_transfer_type_id, (SELECT uuid FROM data_transfer_type WHERE id=old.fk_data_transfer_type_id),old.location,old.source_internal_object_id,old.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=old.fk_deleted_status_id),old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id),old.source_definition,old.source_comment);

   INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id) VALUES (old.id, old.fk_object_type_id);
END;


DROP TABLE IF EXISTS data_transfer_type_log;
CREATE TABLE data_transfer_type_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   name TEXT(250),
   description TEXT(4000),
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT,
   source_definition_language TEXT(250)
);

DROP TRIGGER IF EXISTS TRIG_data_transfer_type_log_INSERT;
CREATE TRIGGER TRIG_data_transfer_type_log_INSERT AFTER INSERT
ON data_transfer_type
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO data_transfer_type_log (log_action, id,uuid,name,description,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition_language) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.name,new.description,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id),new.source_definition_language);
   UPDATE data_transfer_type SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM data_transfer_type_log WHERE log_id=(SELECT MAX(log_id)+1 FROM data_transfer_type_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_data_transfer_type_log_UPDATE;
CREATE TRIGGER TRIG_data_transfer_type_log_UPDATE AFTER UPDATE
ON data_transfer_type
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE data_transfer_type SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO data_transfer_type_log (log_action, id,uuid,name,description,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition_language) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.name,new.description,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id),new.source_definition_language);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_data_transfer_type_log_DELETE;
CREATE TRIGGER TRIG_data_transfer_type_log_DELETE AFTER DELETE
ON data_transfer_type
BEGIN
   INSERT INTO data_transfer_type_log (log_action, id,uuid,name,description,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid,source_definition_language) VALUES ('DELETE',old.id,old.uuid,old.name,old.description,old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id),old.source_definition_language);
END;


DROP TABLE IF EXISTS app_config_log;
CREATE TABLE app_config_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   key TEXT,
   valueINT TEXT,
   valueSTRING TEXT,
   description TEXT
);

DROP TRIGGER IF EXISTS TRIG_app_config_log_INSERT;
CREATE TRIGGER TRIG_app_config_log_INSERT AFTER INSERT
ON app_config
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO app_config_log (log_action, id,uuid,key,valueINT,valueSTRING,description) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.key,new.valueINT,new.valueSTRING,new.description);
   UPDATE app_config SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM app_config_log WHERE log_id=(SELECT MAX(log_id)+1 FROM app_config_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_app_config_log_UPDATE;
CREATE TRIGGER TRIG_app_config_log_UPDATE AFTER UPDATE
ON app_config
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE app_config SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO app_config_log (log_action, id,uuid,key,valueINT,valueSTRING,description) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.key,new.valueINT,new.valueSTRING,new.description);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_app_config_log_DELETE;
CREATE TRIGGER TRIG_app_config_log_DELETE AFTER DELETE
ON app_config
BEGIN
   INSERT INTO app_config_log (log_action, id,uuid,key,valueINT,valueSTRING,description) VALUES ('DELETE',old.id,old.uuid,old.key,old.valueINT,old.valueSTRING,old.description);
END;


DROP TABLE IF EXISTS contact_group_log;
CREATE TABLE contact_group_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   fk_object_type_id INTEGER,
   fk_object_type_uuid TEXT,
   fk_client_id INTEGER,
   fk_client_uuid TEXT,
   name TEXT,
   description TEXT,
   short_name TEXT,
   fk_deleted_status_id INTEGER,
   fk_deleted_status_uuid TEXT,
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT
);

DROP TRIGGER IF EXISTS TRIG_contact_group_log_INSERT;
CREATE TRIGGER TRIG_contact_group_log_INSERT AFTER INSERT
ON contact_group
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO contact_group_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_client_id, fk_client_uuid,name,description,short_name,fk_deleted_status_id, fk_deleted_status_uuid,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_client_id, (SELECT uuid FROM client WHERE id=new.fk_client_id),new.name,new.description,new.short_name,new.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=new.fk_deleted_status_id),new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   UPDATE contact_group SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM contact_group_log WHERE log_id=(SELECT MAX(log_id)+1 FROM contact_group_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_contact_group_log_UPDATE;
CREATE TRIGGER TRIG_contact_group_log_UPDATE AFTER UPDATE
ON contact_group
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE contact_group SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO contact_group_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_client_id, fk_client_uuid,name,description,short_name,fk_deleted_status_id, fk_deleted_status_uuid,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_client_id, (SELECT uuid FROM client WHERE id=new.fk_client_id),new.name,new.description,new.short_name,new.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=new.fk_deleted_status_id),new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_contact_group_log_DELETE;
CREATE TRIGGER TRIG_contact_group_log_DELETE AFTER DELETE
ON contact_group
BEGIN
   INSERT INTO contact_group_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_client_id, fk_client_uuid,name,description,short_name,fk_deleted_status_id, fk_deleted_status_uuid,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_client_id, (SELECT uuid FROM client WHERE id=old.fk_client_id),old.name,old.description,old.short_name,old.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=old.fk_deleted_status_id),old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id));

   INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id) VALUES (old.id, old.fk_object_type_id);
END;


DROP TABLE IF EXISTS contact_log;
CREATE TABLE contact_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   fk_object_type_id INTEGER,
   fk_object_type_uuid TEXT,
   fk_contact_group_id INTEGER,
   fk_contact_group_uuid TEXT,
   fk_client_id INTEGER,
   fk_client_uuid TEXT,
   givenname TEXT,
   surname TEXT,
   email TEXT,
   phone TEXT,
   mobile TEXT,
   ldap_cn TEXT,
   description TEXT,
   fk_deleted_status_id INTEGER,
   fk_deleted_status_uuid TEXT,
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT
);

DROP TRIGGER IF EXISTS TRIG_contact_log_INSERT;
CREATE TRIGGER TRIG_contact_log_INSERT AFTER INSERT
ON contact
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO contact_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_contact_group_id, fk_contact_group_uuid,fk_client_id, fk_client_uuid,givenname,surname,email,phone,mobile,ldap_cn,description,fk_deleted_status_id, fk_deleted_status_uuid,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_contact_group_id, (SELECT uuid FROM contact_group WHERE id=new.fk_contact_group_id),new.fk_client_id, (SELECT uuid FROM client WHERE id=new.fk_client_id),new.givenname,new.surname,new.email,new.phone,new.mobile,new.ldap_cn,new.description,new.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=new.fk_deleted_status_id),new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   UPDATE contact SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM contact_log WHERE log_id=(SELECT MAX(log_id)+1 FROM contact_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_contact_log_UPDATE;
CREATE TRIGGER TRIG_contact_log_UPDATE AFTER UPDATE
ON contact
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE contact SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO contact_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_contact_group_id, fk_contact_group_uuid,fk_client_id, fk_client_uuid,givenname,surname,email,phone,mobile,ldap_cn,description,fk_deleted_status_id, fk_deleted_status_uuid,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_contact_group_id, (SELECT uuid FROM contact_group WHERE id=new.fk_contact_group_id),new.fk_client_id, (SELECT uuid FROM client WHERE id=new.fk_client_id),new.givenname,new.surname,new.email,new.phone,new.mobile,new.ldap_cn,new.description,new.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=new.fk_deleted_status_id),new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_contact_log_DELETE;
CREATE TRIGGER TRIG_contact_log_DELETE AFTER DELETE
ON contact
BEGIN
   INSERT INTO contact_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_contact_group_id, fk_contact_group_uuid,fk_client_id, fk_client_uuid,givenname,surname,email,phone,mobile,ldap_cn,description,fk_deleted_status_id, fk_deleted_status_uuid,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_contact_group_id, (SELECT uuid FROM contact_group WHERE id=old.fk_contact_group_id),old.fk_client_id, (SELECT uuid FROM client WHERE id=old.fk_client_id),old.givenname,old.surname,old.email,old.phone,old.mobile,old.ldap_cn,old.description,old.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=old.fk_deleted_status_id),old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id));

   INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id) VALUES (old.id, old.fk_object_type_id);
END;


DROP TABLE IF EXISTS bracket_log;
CREATE TABLE bracket_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   fk_object_type_id INTEGER,
   fk_object_type_uuid TEXT,
   fk_project_id INTEGER,
   fk_project_uuid TEXT,
   name TEXT(250),
   description TEXT(4000),
   fk_attribute_id INTEGER,
   fk_attribute_uuid TEXT,
   fk_object_type_id_as_searchFilter INTEGER,
   fk_object_type_uuid_as_searchFilter TEXT,
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT
);

DROP TRIGGER IF EXISTS TRIG_bracket_log_INSERT;
CREATE TRIGGER TRIG_bracket_log_INSERT AFTER INSERT
ON bracket
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO bracket_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_attribute_id, fk_attribute_uuid,fk_object_type_id_as_searchFilter, fk_object_type_uuid_as_searchFilter,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_attribute_id, (SELECT uuid FROM attribute WHERE id=new.fk_attribute_id),new.fk_object_type_id_as_searchFilter, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id_as_searchFilter),new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   UPDATE bracket SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM bracket_log WHERE log_id=(SELECT MAX(log_id)+1 FROM bracket_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_bracket_log_UPDATE;
CREATE TRIGGER TRIG_bracket_log_UPDATE AFTER UPDATE
ON bracket
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE bracket SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO bracket_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_attribute_id, fk_attribute_uuid,fk_object_type_id_as_searchFilter, fk_object_type_uuid_as_searchFilter,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_attribute_id, (SELECT uuid FROM attribute WHERE id=new.fk_attribute_id),new.fk_object_type_id_as_searchFilter, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id_as_searchFilter),new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_bracket_log_DELETE;
CREATE TRIGGER TRIG_bracket_log_DELETE AFTER DELETE
ON bracket
BEGIN
   INSERT INTO bracket_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_attribute_id, fk_attribute_uuid,fk_object_type_id_as_searchFilter, fk_object_type_uuid_as_searchFilter,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.fk_attribute_id, (SELECT uuid FROM attribute WHERE id=old.fk_attribute_id),old.fk_object_type_id_as_searchFilter, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id_as_searchFilter),old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id));

   INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id) VALUES (old.id, old.fk_object_type_id);
END;


DROP TABLE IF EXISTS bracket_searchPattern_log;
CREATE TABLE bracket_searchPattern_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   fk_object_type_id INTEGER,
   fk_object_type_uuid TEXT,
   fk_bracket_id INTEGER,
   fk_bracket_uuid TEXT,
   searchPattern TEXT(500),
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT
);

DROP TRIGGER IF EXISTS TRIG_bracket_searchPattern_log_INSERT;
CREATE TRIGGER TRIG_bracket_searchPattern_log_INSERT AFTER INSERT
ON bracket_searchPattern
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO bracket_searchPattern_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_bracket_id, fk_bracket_uuid,searchPattern,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_bracket_id, (SELECT uuid FROM bracket WHERE id=new.fk_bracket_id),new.searchPattern,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   UPDATE bracket_searchPattern SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM bracket_searchPattern_log WHERE log_id=(SELECT MAX(log_id)+1 FROM bracket_searchPattern_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_bracket_searchPattern_log_UPDATE;
CREATE TRIGGER TRIG_bracket_searchPattern_log_UPDATE AFTER UPDATE
ON bracket_searchPattern
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE bracket_searchPattern SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO bracket_searchPattern_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_bracket_id, fk_bracket_uuid,searchPattern,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_bracket_id, (SELECT uuid FROM bracket WHERE id=new.fk_bracket_id),new.searchPattern,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_bracket_searchPattern_log_DELETE;
CREATE TRIGGER TRIG_bracket_searchPattern_log_DELETE AFTER DELETE
ON bracket_searchPattern
BEGIN
   INSERT INTO bracket_searchPattern_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_bracket_id, fk_bracket_uuid,searchPattern,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_bracket_id, (SELECT uuid FROM bracket WHERE id=old.fk_bracket_id),old.searchPattern,old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id));

   INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id) VALUES (old.id, old.fk_object_type_id);
END;


-- DROP TABLE IF EXISTS import_stage_db_table_log;
-- CREATE TABLE import_stage_db_table_log (
--    log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
--    log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--    log_action TEXT,
--    id INTEGER,
--    client_name TEXT(250),
--    project_name TEXT(250),
--    db_table_name TEXT(250),
--    db_table_description TEXT(500),
--    db_table_field_name TEXT(250),
--    db_table_field_datatype TEXT(250),
--    db_table_field_description TEXT(500),
--    db_table_type_name TEXT(250),
--    db_table_context_name TEXT(250),
--    db_table_context_prefix TEXT(100),
--    isPrimaryKeyField BOOLEAN,
--    isForeignKeyField BOOLEAN,
--    foreignKey_table_name TEXT(250),
--    foreignKey_table_field_name TEXT(250),
--    _import_state INTEGER,
--    _import_date TIMESTAMP,
--    is_BusinessKey BOOLEAN,
--    is_GDPR_relevant BOOLEAN,
--    location TEXT,
--    database_or_catalog TEXT(1000),
--    schema TEXT(4000),
--    fk_project_id TEXT,
--    fk_project_uuid TEXT,
--    fk_db_database_id TEXT,
--    fk_db_database_uuid TEXT,
--    column_default_value TEXT(1000),
--    column_cant_be_null BOOLEAN,
--    additional_field_1 TEXT(4000),
--    additional_field_2 TEXT(4000),
--    additional_field_3 TEXT(4000),
--    additional_field_4 TEXT(4000),
--    additional_field_5 TEXT(4000),
--    additional_field_6 TEXT(4000),
--    additional_field_7 TEXT(4000),
--    additional_field_8 TEXT(4000),
--    additional_field_9 TEXT(4000)
-- );
-- 
-- DROP TRIGGER IF EXISTS TRIG_import_stage_db_table_log_INSERT;
-- CREATE TRIGGER TRIG_import_stage_db_table_log_INSERT AFTER INSERT
-- ON import_stage_db_table
-- BEGIN
--    INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
--    INSERT INTO import_stage_db_table_log (log_action, id,client_name,project_name,db_table_name,db_table_description,db_table_field_name,db_table_field_datatype,db_table_field_description,db_table_type_name,db_table_context_name,db_table_context_prefix,isPrimaryKeyField,isForeignKeyField,foreignKey_table_name,foreignKey_table_field_name,_import_state,_import_date,is_BusinessKey,is_GDPR_relevant,location,database_or_catalog,schema,fk_project_id, fk_project_uuid,fk_db_database_id, fk_db_database_uuid,column_default_value,column_cant_be_null,additional_field_1,additional_field_2,additional_field_3,additional_field_4,additional_field_5,additional_field_6,additional_field_7,additional_field_8,additional_field_9) VALUES ('INSERT',new.id,new.client_name,new.project_name,new.db_table_name,new.db_table_description,new.db_table_field_name,new.db_table_field_datatype,new.db_table_field_description,new.db_table_type_name,new.db_table_context_name,new.db_table_context_prefix,new.isPrimaryKeyField,new.isForeignKeyField,new.foreignKey_table_name,new.foreignKey_table_field_name,new._import_state,new._import_date,new.is_BusinessKey,new.is_GDPR_relevant,new.location,new.database_or_catalog,new.schema,new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.fk_db_database_id, (SELECT uuid FROM db_database WHERE id=new.fk_db_database_id),new.column_default_value,new.column_cant_be_null,new.additional_field_1,new.additional_field_2,new.additional_field_3,new.additional_field_4,new.additional_field_5,new.additional_field_6,new.additional_field_7,new.additional_field_8,new.additional_field_9);
--    UPDATE import_stage_db_table SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
--    DELETE FROM _newUUID;
--    DELETE FROM import_stage_db_table_log WHERE log_id=(SELECT MAX(log_id)+1 FROM import_stage_db_table_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
-- END;

-- DROP TRIGGER IF EXISTS TRIG_import_stage_db_table_log_UPDATE;
-- CREATE TRIGGER TRIG_import_stage_db_table_log_UPDATE AFTER UPDATE
-- ON import_stage_db_table
-- BEGIN
--    INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
--    UPDATE import_stage_db_table SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
--    INSERT INTO import_stage_db_table_log (log_action, id,client_name,project_name,db_table_name,db_table_description,db_table_field_name,db_table_field_datatype,db_table_field_description,db_table_type_name,db_table_context_name,db_table_context_prefix,isPrimaryKeyField,isForeignKeyField,foreignKey_table_name,foreignKey_table_field_name,_import_state,_import_date,is_BusinessKey,is_GDPR_relevant,location,database_or_catalog,schema,fk_project_id, fk_project_uuid,fk_db_database_id, fk_db_database_uuid,column_default_value,column_cant_be_null,additional_field_1,additional_field_2,additional_field_3,additional_field_4,additional_field_5,additional_field_6,additional_field_7,additional_field_8,additional_field_9) VALUES ('UPDATE',new.id,new.client_name,new.project_name,new.db_table_name,new.db_table_description,new.db_table_field_name,new.db_table_field_datatype,new.db_table_field_description,new.db_table_type_name,new.db_table_context_name,new.db_table_context_prefix,new.isPrimaryKeyField,new.isForeignKeyField,new.foreignKey_table_name,new.foreignKey_table_field_name,new._import_state,new._import_date,new.is_BusinessKey,new.is_GDPR_relevant,new.location,new.database_or_catalog,new.schema,new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.fk_db_database_id, (SELECT uuid FROM db_database WHERE id=new.fk_db_database_id),new.column_default_value,new.column_cant_be_null,new.additional_field_1,new.additional_field_2,new.additional_field_3,new.additional_field_4,new.additional_field_5,new.additional_field_6,new.additional_field_7,new.additional_field_8,new.additional_field_9);
--    DELETE FROM _newUUID;
-- END;

-- DROP TRIGGER IF EXISTS TRIG_import_stage_db_table_log_DELETE;
-- CREATE TRIGGER TRIG_import_stage_db_table_log_DELETE AFTER DELETE
-- ON import_stage_db_table
-- BEGIN
--    INSERT INTO import_stage_db_table_log (log_action, id,client_name,project_name,db_table_name,db_table_description,db_table_field_name,db_table_field_datatype,db_table_field_description,db_table_type_name,db_table_context_name,db_table_context_prefix,isPrimaryKeyField,isForeignKeyField,foreignKey_table_name,foreignKey_table_field_name,_import_state,_import_date,is_BusinessKey,is_GDPR_relevant,location,database_or_catalog,schema,fk_project_id, fk_project_uuid,fk_db_database_id, fk_db_database_uuid,column_default_value,column_cant_be_null,additional_field_1,additional_field_2,additional_field_3,additional_field_4,additional_field_5,additional_field_6,additional_field_7,additional_field_8,additional_field_9) VALUES ('DELETE',old.id,old.client_name,old.project_name,old.db_table_name,old.db_table_description,old.db_table_field_name,old.db_table_field_datatype,old.db_table_field_description,old.db_table_type_name,old.db_table_context_name,old.db_table_context_prefix,old.isPrimaryKeyField,old.isForeignKeyField,old.foreignKey_table_name,old.foreignKey_table_field_name,old._import_state,old._import_date,old.is_BusinessKey,old.is_GDPR_relevant,old.location,old.database_or_catalog,old.schema,old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.fk_db_database_id, (SELECT uuid FROM db_database WHERE id=old.fk_db_database_id),old.column_default_value,old.column_cant_be_null,old.additional_field_1,old.additional_field_2,old.additional_field_3,old.additional_field_4,old.additional_field_5,old.additional_field_6,old.additional_field_7,old.additional_field_8,old.additional_field_9);
-- 
--    INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id) VALUES (old.id, old.fk_object_type_id);
-- END;


DROP TABLE IF EXISTS perspective_filter_log;
CREATE TABLE perspective_filter_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   fk_language_id TEXT(32),
   -- fk_language_uuid TEXT(32),
   fk_object_type_id INTEGER,
   fk_object_type_uuid TEXT,
   filter_attribute_name TEXT(150),
   filter_value TEXT(150),
   ref_fk_object_type_id INTEGER,
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT
);

DROP TRIGGER IF EXISTS TRIG_perspective_filter_log_INSERT;
CREATE TRIGGER TRIG_perspective_filter_log_INSERT AFTER INSERT
ON perspective_filter
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO perspective_filter_log (log_action, id,uuid,fk_language_id, fk_object_type_id, fk_object_type_uuid,filter_attribute_name,filter_value,ref_fk_object_type_id,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_language_id, new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.filter_attribute_name,new.filter_value,new.ref_fk_object_type_id,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   UPDATE perspective_filter SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM perspective_filter_log WHERE log_id=(SELECT MAX(log_id)+1 FROM perspective_filter_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_perspective_filter_log_UPDATE;
CREATE TRIGGER TRIG_perspective_filter_log_UPDATE AFTER UPDATE
ON perspective_filter
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE perspective_filter SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO perspective_filter_log (log_action, id,uuid,fk_language_id, fk_object_type_id, fk_object_type_uuid,filter_attribute_name,filter_value,ref_fk_object_type_id,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_language_id, new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.filter_attribute_name,new.filter_value,new.ref_fk_object_type_id,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_perspective_filter_log_DELETE;
CREATE TRIGGER TRIG_perspective_filter_log_DELETE AFTER DELETE
ON perspective_filter
BEGIN
   INSERT INTO perspective_filter_log (log_action, id,uuid,fk_language_id, fk_object_type_id, fk_object_type_uuid,filter_attribute_name,filter_value,ref_fk_object_type_id,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('DELETE',old.id,old.uuid,old.fk_language_id, old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.filter_attribute_name,old.filter_value,old.ref_fk_object_type_id,old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id));

   INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id) VALUES (old.id, old.fk_object_type_id);
END;


DROP TABLE IF EXISTS mapping_qualifier_log;
CREATE TABLE mapping_qualifier_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   fk_object_type_id INTEGER,
   fk_object_type_uuid TEXT,
   name TEXT(250),
   short_name TEXT(250),
   description TEXT(4000),
   needs_object_depends_on BOOLEAN,
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT
);

DROP TRIGGER IF EXISTS TRIG_mapping_qualifier_log_INSERT;
CREATE TRIGGER TRIG_mapping_qualifier_log_INSERT AFTER INSERT
ON mapping_qualifier
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO mapping_qualifier_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,name,short_name,description,needs_object_depends_on,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.name,new.short_name,new.description,new.needs_object_depends_on,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   UPDATE mapping_qualifier SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM mapping_qualifier_log WHERE log_id=(SELECT MAX(log_id)+1 FROM mapping_qualifier_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_mapping_qualifier_log_UPDATE;
CREATE TRIGGER TRIG_mapping_qualifier_log_UPDATE AFTER UPDATE
ON mapping_qualifier
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE mapping_qualifier SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO mapping_qualifier_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,name,short_name,description,needs_object_depends_on,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.name,new.short_name,new.description,new.needs_object_depends_on,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_mapping_qualifier_log_DELETE;
CREATE TRIGGER TRIG_mapping_qualifier_log_DELETE AFTER DELETE
ON mapping_qualifier
BEGIN
   INSERT INTO mapping_qualifier_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,name,short_name,description,needs_object_depends_on,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.name,old.short_name,old.description,old.needs_object_depends_on,old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id));

   INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id) VALUES (old.id, old.fk_object_type_id);
END;


DROP TABLE IF EXISTS url_log;
CREATE TABLE url_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   fk_object_type_id INTEGER,
   fk_object_type_uuid TEXT,
   fk_project_id INTEGER,
   fk_project_uuid TEXT,
   name TEXT(250),
   description TEXT(4000),
   url TEXT(4000),
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT
);

DROP TRIGGER IF EXISTS TRIG_url_log_INSERT;
CREATE TRIGGER TRIG_url_log_INSERT AFTER INSERT
ON url
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO url_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,url,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.url,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   UPDATE url SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM url_log WHERE log_id=(SELECT MAX(log_id)+1 FROM url_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_url_log_UPDATE;
CREATE TRIGGER TRIG_url_log_UPDATE AFTER UPDATE
ON url
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE url SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO url_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,url,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.url,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_url_log_DELETE;
CREATE TRIGGER TRIG_url_log_DELETE AFTER DELETE
ON url
BEGIN
   INSERT INTO url_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,url,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.url,old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id));

   INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id) VALUES (old.id, old.fk_object_type_id);
END;


DROP TABLE IF EXISTS cleanup_queue_log;
CREATE TABLE cleanup_queue_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   ref_fk_object_id INTEGER,
   ref_fk_object_type_id INTEGER,
   created_at_datetime TIMESTAMP
);

-- DROP TRIGGER IF EXISTS TRIG_cleanup_queue_log_INSERT;
-- CREATE TRIGGER TRIG_cleanup_queue_log_INSERT AFTER INSERT
-- ON cleanup_queue
-- BEGIN
--    INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
--    INSERT INTO cleanup_queue_log (log_action, id,ref_fk_object_id,ref_fk_object_type_id,created_at_datetime) VALUES ('INSERT',new.id,new.ref_fk_object_id,new.ref_fk_object_type_id,new.created_at_datetime);
--    UPDATE cleanup_queue SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
--    DELETE FROM _newUUID;
--    DELETE FROM cleanup_queue_log WHERE log_id=(SELECT MAX(log_id)+1 FROM cleanup_queue_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
-- END;

-- DROP TRIGGER IF EXISTS TRIG_cleanup_queue_log_UPDATE;
-- CREATE TRIGGER TRIG_cleanup_queue_log_UPDATE AFTER UPDATE
-- ON cleanup_queue
-- BEGIN
--    INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
--    UPDATE cleanup_queue SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
--    INSERT INTO cleanup_queue_log (log_action, id,ref_fk_object_id,ref_fk_object_type_id,created_at_datetime) VALUES ('UPDATE',new.id,new.ref_fk_object_id,new.ref_fk_object_type_id,new.created_at_datetime);
--    DELETE FROM _newUUID;
-- END;

DROP TRIGGER IF EXISTS TRIG_cleanup_queue_log_DELETE;
CREATE TRIGGER TRIG_cleanup_queue_log_DELETE AFTER DELETE
ON cleanup_queue
BEGIN
   INSERT INTO cleanup_queue_log (log_action, id,ref_fk_object_id,ref_fk_object_type_id,created_at_datetime) VALUES ('DELETE',old.id,old.ref_fk_object_id,old.ref_fk_object_type_id,old.created_at_datetime);
END;


DROP TABLE IF EXISTS deleted_status_log;
CREATE TABLE deleted_status_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   fk_object_type_id INTEGER,
   fk_object_type_uuid TEXT,
   name TEXT(250),
   description TEXT(4000),
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT
);

DROP TRIGGER IF EXISTS TRIG_deleted_status_log_INSERT;
CREATE TRIGGER TRIG_deleted_status_log_INSERT AFTER INSERT
ON deleted_status
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO deleted_status_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,name,description,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.name,new.description,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   UPDATE deleted_status SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM deleted_status_log WHERE log_id=(SELECT MAX(log_id)+1 FROM deleted_status_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_deleted_status_log_UPDATE;
CREATE TRIGGER TRIG_deleted_status_log_UPDATE AFTER UPDATE
ON deleted_status
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE deleted_status SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO deleted_status_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,name,description,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.name,new.description,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_deleted_status_log_DELETE;
CREATE TRIGGER TRIG_deleted_status_log_DELETE AFTER DELETE
ON deleted_status
BEGIN
   INSERT INTO deleted_status_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,name,description,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.name,old.description,old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id));

   INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id) VALUES (old.id, old.fk_object_type_id);
END;


DROP TABLE IF EXISTS export_file_db_table_queue_log;
CREATE TABLE export_file_db_table_queue_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   session TEXT(200),
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT
);

DROP TRIGGER IF EXISTS TRIG_export_file_db_table_queue_log_INSERT;
CREATE TRIGGER TRIG_export_file_db_table_queue_log_INSERT AFTER INSERT
ON export_file_db_table_queue
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO export_file_db_table_queue_log (log_action, id,session,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('INSERT',new.id,new.session,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   UPDATE export_file_db_table_queue SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM export_file_db_table_queue_log WHERE log_id=(SELECT MAX(log_id)+1 FROM export_file_db_table_queue_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_export_file_db_table_queue_log_UPDATE;
CREATE TRIGGER TRIG_export_file_db_table_queue_log_UPDATE AFTER UPDATE
ON export_file_db_table_queue
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE export_file_db_table_queue SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO export_file_db_table_queue_log (log_action, id,session,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('UPDATE',new.id,new.session,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_export_file_db_table_queue_log_DELETE;
CREATE TRIGGER TRIG_export_file_db_table_queue_log_DELETE AFTER DELETE
ON export_file_db_table_queue
BEGIN
   INSERT INTO export_file_db_table_queue_log (log_action, id,session,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('DELETE',old.id,old.session,old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id));

   INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id) VALUES (old.id, old.fk_object_type_id);
END;


DROP TABLE IF EXISTS export_file_db_table_params_log;
CREATE TABLE export_file_db_table_params_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   session TEXT(200),
   allowed_fk_project_id INTEGER,
   allowed_fk_client_id INTEGER,
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT
);

DROP TRIGGER IF EXISTS TRIG_export_file_db_table_params_log_INSERT;
CREATE TRIGGER TRIG_export_file_db_table_params_log_INSERT AFTER INSERT
ON export_file_db_table_params
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO export_file_db_table_params_log (log_action, id,session,allowed_fk_project_id,allowed_fk_client_id,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('INSERT',new.id,new.session,new.allowed_fk_project_id,new.allowed_fk_client_id,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   UPDATE export_file_db_table_params SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM export_file_db_table_params_log WHERE log_id=(SELECT MAX(log_id)+1 FROM export_file_db_table_params_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_export_file_db_table_params_log_UPDATE;
CREATE TRIGGER TRIG_export_file_db_table_params_log_UPDATE AFTER UPDATE
ON export_file_db_table_params
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE export_file_db_table_params SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO export_file_db_table_params_log (log_action, id,session,allowed_fk_project_id,allowed_fk_client_id,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('UPDATE',new.id,new.session,new.allowed_fk_project_id,new.allowed_fk_client_id,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_export_file_db_table_params_log_DELETE;
CREATE TRIGGER TRIG_export_file_db_table_params_log_DELETE AFTER DELETE
ON export_file_db_table_params
BEGIN
   INSERT INTO export_file_db_table_params_log (log_action, id,session,allowed_fk_project_id,allowed_fk_client_id,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('DELETE',old.id,old.session,old.allowed_fk_project_id,old.allowed_fk_client_id,old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id));

   INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id) VALUES (old.id, old.fk_object_type_id);
END;


DROP TABLE IF EXISTS export_file_db_table_result_log;
CREATE TABLE export_file_db_table_result_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   fk_object_type_id INTEGER,
   fk_object_type_uuid TEXT,
   fk_project_id INTEGER,
   fk_project_uuid TEXT,
   fk_client_id INTEGER,
   fk_client_uuid TEXT,
   project_name TEXT(250),
   client_name TEXT(250),
   name TEXT(250),
   description TEXT(4000),
   location TEXT,
   fk_db_table_context_id INTEGER,
   fk_db_table_context_uuid TEXT,
   db_table_context_name TEXT(250),
   fk_db_table_type_id INTEGER,
   fk_db_table_type_uuid TEXT,
   db_table_type_name TEXT(250),
   fk_deleted_status_id INTEGER,
   fk_deleted_status_uuid TEXT,
   deleted_status_name TEXT(250),
   databaseInfoFromLocation TEXT,
   comments TEXT,
   mappings TEXT,
   session TEXT(200),
   _auto_id INTEGER,
   _created_datetime TIMESTAMP,
   fk_object_persistence_method_id INTEGER,
   fk_object_persistence_method_uuid TEXT,
   fk_datamanagement_process_id INTEGER,
   fk_datamanagement_process_uuid TEXT
);

DROP TRIGGER IF EXISTS TRIG_export_file_db_table_result_log_INSERT;
CREATE TRIGGER TRIG_export_file_db_table_result_log_INSERT AFTER INSERT
ON export_file_db_table_result
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO export_file_db_table_result_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,fk_client_id, fk_client_uuid,project_name,client_name,name,description,location,fk_db_table_context_id, fk_db_table_context_uuid,db_table_context_name,fk_db_table_type_id, fk_db_table_type_uuid,db_table_type_name,fk_deleted_status_id, fk_deleted_status_uuid,deleted_status_name,databaseInfoFromLocation,comments,mappings,session,_auto_id,_created_datetime,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.fk_client_id, (SELECT uuid FROM client WHERE id=new.fk_client_id),new.project_name,new.client_name,new.name,new.description,new.location,new.fk_db_table_context_id, (SELECT uuid FROM db_table_context WHERE id=new.fk_db_table_context_id),new.db_table_context_name,new.fk_db_table_type_id, (SELECT uuid FROM db_table_type WHERE id=new.fk_db_table_type_id),new.db_table_type_name,new.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=new.fk_deleted_status_id),new.deleted_status_name,new.databaseInfoFromLocation,new.comments,new.mappings,new.session,new._auto_id,new._created_datetime,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   UPDATE export_file_db_table_result SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM export_file_db_table_result_log WHERE log_id=(SELECT MAX(log_id)+1 FROM export_file_db_table_result_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_export_file_db_table_result_log_UPDATE;
CREATE TRIGGER TRIG_export_file_db_table_result_log_UPDATE AFTER UPDATE
ON export_file_db_table_result
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE export_file_db_table_result SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO export_file_db_table_result_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,fk_client_id, fk_client_uuid,project_name,client_name,name,description,location,fk_db_table_context_id, fk_db_table_context_uuid,db_table_context_name,fk_db_table_type_id, fk_db_table_type_uuid,db_table_type_name,fk_deleted_status_id, fk_deleted_status_uuid,deleted_status_name,databaseInfoFromLocation,comments,mappings,session,_auto_id,_created_datetime,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.fk_client_id, (SELECT uuid FROM client WHERE id=new.fk_client_id),new.project_name,new.client_name,new.name,new.description,new.location,new.fk_db_table_context_id, (SELECT uuid FROM db_table_context WHERE id=new.fk_db_table_context_id),new.db_table_context_name,new.fk_db_table_type_id, (SELECT uuid FROM db_table_type WHERE id=new.fk_db_table_type_id),new.db_table_type_name,new.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=new.fk_deleted_status_id),new.deleted_status_name,new.databaseInfoFromLocation,new.comments,new.mappings,new.session,new._auto_id,new._created_datetime,new.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=new.fk_object_persistence_method_id),new.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=new.fk_datamanagement_process_id));
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_export_file_db_table_result_log_DELETE;
CREATE TRIGGER TRIG_export_file_db_table_result_log_DELETE AFTER DELETE
ON export_file_db_table_result
BEGIN
   INSERT INTO export_file_db_table_result_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,fk_client_id, fk_client_uuid,project_name,client_name,name,description,location,fk_db_table_context_id, fk_db_table_context_uuid,db_table_context_name,fk_db_table_type_id, fk_db_table_type_uuid,db_table_type_name,fk_deleted_status_id, fk_deleted_status_uuid,deleted_status_name,databaseInfoFromLocation,comments,mappings,session,_auto_id,_created_datetime,fk_object_persistence_method_id, fk_object_persistence_method_uuid,fk_datamanagement_process_id, fk_datamanagement_process_uuid) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.fk_client_id, (SELECT uuid FROM client WHERE id=old.fk_client_id),old.project_name,old.client_name,old.name,old.description,old.location,old.fk_db_table_context_id, (SELECT uuid FROM db_table_context WHERE id=old.fk_db_table_context_id),old.db_table_context_name,old.fk_db_table_type_id, (SELECT uuid FROM db_table_type WHERE id=old.fk_db_table_type_id),old.db_table_type_name,old.fk_deleted_status_id, (SELECT uuid FROM deleted_status WHERE id=old.fk_deleted_status_id),old.deleted_status_name,old.databaseInfoFromLocation,old.comments,old.mappings,old.session,old._auto_id,old._created_datetime,old.fk_object_persistence_method_id, (SELECT uuid FROM object_persistence_method WHERE id=old.fk_object_persistence_method_id),old.fk_datamanagement_process_id, (SELECT uuid FROM datamanagement_process WHERE id=old.fk_datamanagement_process_id));

   INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id) VALUES (old.id, old.fk_object_type_id);
END;


DROP TABLE IF EXISTS object_persistence_method_log;
CREATE TABLE object_persistence_method_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   fk_object_type_id INTEGER,
   fk_object_type_uuid TEXT,
   name TEXT(250),
   description TEXT(4000)
);

DROP TRIGGER IF EXISTS TRIG_object_persistence_method_log_INSERT;
CREATE TRIGGER TRIG_object_persistence_method_log_INSERT AFTER INSERT
ON object_persistence_method
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO object_persistence_method_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,name,description) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.name,new.description);
   UPDATE object_persistence_method SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM object_persistence_method_log WHERE log_id=(SELECT MAX(log_id)+1 FROM object_persistence_method_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_object_persistence_method_log_UPDATE;
CREATE TRIGGER TRIG_object_persistence_method_log_UPDATE AFTER UPDATE
ON object_persistence_method
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE object_persistence_method SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO object_persistence_method_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,name,description) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.name,new.description);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_object_persistence_method_log_DELETE;
CREATE TRIGGER TRIG_object_persistence_method_log_DELETE AFTER DELETE
ON object_persistence_method
BEGIN
   INSERT INTO object_persistence_method_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,name,description) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.name,old.description);

   INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id) VALUES (old.id, old.fk_object_type_id);
END;


DROP TABLE IF EXISTS datamanagement_process_log;
CREATE TABLE datamanagement_process_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   fk_object_type_id INTEGER,
   fk_object_type_uuid TEXT,
   name TEXT(250),
   description TEXT(4000),
   tool TEXT(500),
   tool_version TEXT(250),
   routine TEXT(500)
);

DROP TRIGGER IF EXISTS TRIG_datamanagement_process_log_INSERT;
CREATE TRIGGER TRIG_datamanagement_process_log_INSERT AFTER INSERT
ON datamanagement_process
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO datamanagement_process_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,name,description,tool,tool_version,routine) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.name,new.description,new.tool,new.tool_version,new.routine);
   UPDATE datamanagement_process SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM datamanagement_process_log WHERE log_id=(SELECT MAX(log_id)+1 FROM datamanagement_process_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_datamanagement_process_log_UPDATE;
CREATE TRIGGER TRIG_datamanagement_process_log_UPDATE AFTER UPDATE
ON datamanagement_process
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE datamanagement_process SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO datamanagement_process_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,name,description,tool,tool_version,routine) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.name,new.description,new.tool,new.tool_version,new.routine);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_datamanagement_process_log_DELETE;
CREATE TRIGGER TRIG_datamanagement_process_log_DELETE AFTER DELETE
ON datamanagement_process
BEGIN
   INSERT INTO datamanagement_process_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,name,description,tool,tool_version,routine) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.name,old.description,old.tool,old.tool_version,old.routine);

   INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id) VALUES (old.id, old.fk_object_type_id);
END;


DROP TABLE IF EXISTS tag_log;
CREATE TABLE tag_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   fk_object_type_id INTEGER,
   fk_object_type_uuid TEXT,
   name TEXT(250),
   fk_project_id INTEGER,
   fk_project_uuid TEXT,
   fk_user_id INTEGER
-- Wegen des UNIQUE muss das Komma immer am Ende entfernt werden! Siehe auch TRIGGER!
);

DROP TRIGGER IF EXISTS TRIG_tag_log_INSERT;
CREATE TRIGGER TRIG_tag_log_INSERT AFTER INSERT
ON tag
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO tag_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,name,fk_project_id, fk_project_uuid,fk_user_id) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.name,new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.fk_user_id);
   UPDATE tag SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM tag_log WHERE log_id=(SELECT MAX(log_id)+1 FROM tag_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_tag_log_UPDATE;
CREATE TRIGGER TRIG_tag_log_UPDATE AFTER UPDATE
ON tag
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE tag SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO tag_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,name,fk_project_id, fk_project_uuid,fk_user_id) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.name,new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.fk_user_id);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_tag_log_DELETE;
CREATE TRIGGER TRIG_tag_log_DELETE AFTER DELETE
ON tag
BEGIN
   INSERT INTO tag_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,name,fk_project_id, fk_project_uuid,fk_user_id) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.name,old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.fk_user_id);

   INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id) VALUES (old.id, old.fk_object_type_id);
END;


DROP TABLE IF EXISTS map_object_2_tag_log;
CREATE TABLE map_object_2_tag_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   ref_fk_object_id INTEGER,
   ref_fk_object_type_id INTEGER,
   fk_tag_id INTEGER,
   fk_tag_uuid TEXT
-- Wegen des UNIQUE muss das Komma immer am Ende entfernt werden! Siehe auch TRIGGER!
);

DROP TRIGGER IF EXISTS TRIG_map_object_2_tag_log_INSERT;
CREATE TRIGGER TRIG_map_object_2_tag_log_INSERT AFTER INSERT
ON map_object_2_tag
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO map_object_2_tag_log (log_action, id,uuid,ref_fk_object_id,ref_fk_object_type_id,fk_tag_id, fk_tag_uuid) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.ref_fk_object_id,new.ref_fk_object_type_id,new.fk_tag_id, (SELECT uuid FROM tag WHERE id=new.fk_tag_id));
   UPDATE map_object_2_tag SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM map_object_2_tag_log WHERE log_id=(SELECT MAX(log_id)+1 FROM map_object_2_tag_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

DROP TRIGGER IF EXISTS TRIG_map_object_2_tag_log_UPDATE;
CREATE TRIGGER TRIG_map_object_2_tag_log_UPDATE AFTER UPDATE
ON map_object_2_tag
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE map_object_2_tag SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO map_object_2_tag_log (log_action, id,uuid,ref_fk_object_id,ref_fk_object_type_id,fk_tag_id, fk_tag_uuid) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.ref_fk_object_id,new.ref_fk_object_type_id,new.fk_tag_id, (SELECT uuid FROM tag WHERE id=new.fk_tag_id));
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_map_object_2_tag_log_DELETE;
CREATE TRIGGER TRIG_map_object_2_tag_log_DELETE AFTER DELETE
ON map_object_2_tag
BEGIN
   INSERT INTO map_object_2_tag_log (log_action, id,uuid,ref_fk_object_id,ref_fk_object_type_id,fk_tag_id, fk_tag_uuid) VALUES ('DELETE',old.id,old.uuid,old.ref_fk_object_id,old.ref_fk_object_type_id,old.fk_tag_id, (SELECT uuid FROM tag WHERE id=old.fk_tag_id));

   INSERT INTO cleanup_queue (ref_fk_object_id, ref_fk_object_type_id) VALUES (old.id, old.fk_object_type_id);
END;

