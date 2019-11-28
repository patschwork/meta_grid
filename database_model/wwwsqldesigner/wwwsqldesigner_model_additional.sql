-- Diese Datei wurde automatisiert ueber das Python-Script create_wwwsqldesigner_model_additional.py erstellt
-- 2019-10-27 14:45:10

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
   cumulation_possible BOOLEAN
);

DROP TRIGGER IF EXISTS TRIG_keyfigure_log_INSERT;
CREATE TRIGGER TRIG_keyfigure_log_INSERT AFTER INSERT
ON keyfigure
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO keyfigure_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,formula,aggregation,character,type,unit,value_range,cumulation_possible) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.formula,new.aggregation,new.character,new.type,new.unit,new.value_range,new.cumulation_possible);
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
   INSERT INTO keyfigure_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,formula,aggregation,character,type,unit,value_range,cumulation_possible) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.formula,new.aggregation,new.character,new.type,new.unit,new.value_range,new.cumulation_possible);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_keyfigure_log_DELETE;
CREATE TRIGGER TRIG_keyfigure_log_DELETE AFTER DELETE
ON keyfigure
BEGIN
   INSERT INTO keyfigure_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,formula,aggregation,character,type,unit,value_range,cumulation_possible) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.formula,old.aggregation,old.character,old.type,old.unit,old.value_range,old.cumulation_possible);
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
   description TEXT(500)
);

DROP TRIGGER IF EXISTS TRIG_client_log_INSERT;
CREATE TRIGGER TRIG_client_log_INSERT AFTER INSERT
ON client
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO client_log (log_action, id,uuid,name,description) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.name,new.description);
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
   INSERT INTO client_log (log_action, id,uuid,name,description) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.name,new.description);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_client_log_DELETE;
CREATE TRIGGER TRIG_client_log_DELETE AFTER DELETE
ON client
BEGIN
   INSERT INTO client_log (log_action, id,uuid,name,description) VALUES ('DELETE',old.id,old.uuid,old.name,old.description);
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
   description TEXT(500)
);

DROP TRIGGER IF EXISTS TRIG_project_log_INSERT;
CREATE TRIGGER TRIG_project_log_INSERT AFTER INSERT
ON project
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO project_log (log_action, id,uuid,fk_client_id, fk_client_uuid,name,description) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_client_id, (SELECT uuid FROM client WHERE id=new.fk_client_id),new.name,new.description);
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
   INSERT INTO project_log (log_action, id,uuid,fk_client_id, fk_client_uuid,name,description) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_client_id, (SELECT uuid FROM client WHERE id=new.fk_client_id),new.name,new.description);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_project_log_DELETE;
CREATE TRIGGER TRIG_project_log_DELETE AFTER DELETE
ON project
BEGIN
   INSERT INTO project_log (log_action, id,uuid,fk_client_id, fk_client_uuid,name,description) VALUES ('DELETE',old.id,old.uuid,old.fk_client_id, (SELECT uuid FROM client WHERE id=old.fk_client_id),old.name,old.description);
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
   fk_contact_group_uuid_as_supporter TEXT
);

DROP TRIGGER IF EXISTS TRIG_sourcesystem_log_INSERT;
CREATE TRIGGER TRIG_sourcesystem_log_INSERT AFTER INSERT
ON sourcesystem
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO sourcesystem_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_contact_group_id_as_supporter, fk_contact_group_uuid_as_supporter) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_contact_group_id_as_supporter, (SELECT uuid FROM contact_group WHERE id=new.fk_contact_group_id_as_supporter));
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
   INSERT INTO sourcesystem_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_contact_group_id_as_supporter, fk_contact_group_uuid_as_supporter) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_contact_group_id_as_supporter, (SELECT uuid FROM contact_group WHERE id=new.fk_contact_group_id_as_supporter));
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_sourcesystem_log_DELETE;
CREATE TRIGGER TRIG_sourcesystem_log_DELETE AFTER DELETE
ON sourcesystem
BEGIN
   INSERT INTO sourcesystem_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_contact_group_id_as_supporter, fk_contact_group_uuid_as_supporter) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.fk_contact_group_id_as_supporter, (SELECT uuid FROM contact_group WHERE id=old.fk_contact_group_id_as_supporter));
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
   fk_contact_group_uuid_as_data_owner TEXT
);

DROP TRIGGER IF EXISTS TRIG_data_delivery_object_log_INSERT;
CREATE TRIGGER TRIG_data_delivery_object_log_INSERT AFTER INSERT
ON data_delivery_object
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO data_delivery_object_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_tool_id, fk_tool_uuid,fk_data_delivery_type_id, fk_data_delivery_type_uuid,fk_contact_group_id_as_data_owner, fk_contact_group_uuid_as_data_owner) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_tool_id, (SELECT uuid FROM tool WHERE id=new.fk_tool_id),new.fk_data_delivery_type_id, (SELECT uuid FROM data_delivery_type WHERE id=new.fk_data_delivery_type_id),new.fk_contact_group_id_as_data_owner, (SELECT uuid FROM contact_group WHERE id=new.fk_contact_group_id_as_data_owner));
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
   INSERT INTO data_delivery_object_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_tool_id, fk_tool_uuid,fk_data_delivery_type_id, fk_data_delivery_type_uuid,fk_contact_group_id_as_data_owner, fk_contact_group_uuid_as_data_owner) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_tool_id, (SELECT uuid FROM tool WHERE id=new.fk_tool_id),new.fk_data_delivery_type_id, (SELECT uuid FROM data_delivery_type WHERE id=new.fk_data_delivery_type_id),new.fk_contact_group_id_as_data_owner, (SELECT uuid FROM contact_group WHERE id=new.fk_contact_group_id_as_data_owner));
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_data_delivery_object_log_DELETE;
CREATE TRIGGER TRIG_data_delivery_object_log_DELETE AFTER DELETE
ON data_delivery_object
BEGIN
   INSERT INTO data_delivery_object_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_tool_id, fk_tool_uuid,fk_data_delivery_type_id, fk_data_delivery_type_uuid,fk_contact_group_id_as_data_owner, fk_contact_group_uuid_as_data_owner) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.fk_tool_id, (SELECT uuid FROM tool WHERE id=old.fk_tool_id),old.fk_data_delivery_type_id, (SELECT uuid FROM data_delivery_type WHERE id=old.fk_data_delivery_type_id),old.fk_contact_group_id_as_data_owner, (SELECT uuid FROM contact_group WHERE id=old.fk_contact_group_id_as_data_owner));
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
   ref_fk_object_type_id_2 INTEGER
-- Wegen des UNIQUE muss das Komma immer am Ende entfernt werden! Siehe auch TRIGGER!
);

DROP TRIGGER IF EXISTS TRIG_map_object_2_object_log_INSERT;
CREATE TRIGGER TRIG_map_object_2_object_log_INSERT AFTER INSERT
ON map_object_2_object
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO map_object_2_object_log (log_action, id,uuid,fk_mapping_qualifier_id, fk_mapping_qualifier_uuid,ref_fk_object_id_1,ref_fk_object_type_id_1,ref_fk_object_id_2,ref_fk_object_type_id_2) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_mapping_qualifier_id, (SELECT uuid FROM mapping_qualifier WHERE id=new.fk_mapping_qualifier_id),new.ref_fk_object_id_1,new.ref_fk_object_type_id_1,new.ref_fk_object_id_2,new.ref_fk_object_type_id_2);
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
   INSERT INTO map_object_2_object_log (log_action, id,uuid,fk_mapping_qualifier_id, fk_mapping_qualifier_uuid,ref_fk_object_id_1,ref_fk_object_type_id_1,ref_fk_object_id_2,ref_fk_object_type_id_2) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_mapping_qualifier_id, (SELECT uuid FROM mapping_qualifier WHERE id=new.fk_mapping_qualifier_id),new.ref_fk_object_id_1,new.ref_fk_object_type_id_1,new.ref_fk_object_id_2,new.ref_fk_object_type_id_2);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_map_object_2_object_log_DELETE;
CREATE TRIGGER TRIG_map_object_2_object_log_DELETE AFTER DELETE
ON map_object_2_object
BEGIN
   INSERT INTO map_object_2_object_log (log_action, id,uuid,fk_mapping_qualifier_id, fk_mapping_qualifier_uuid,ref_fk_object_id_1,ref_fk_object_type_id_1,ref_fk_object_id_2,ref_fk_object_type_id_2) VALUES ('DELETE',old.id,old.uuid,old.fk_mapping_qualifier_id, (SELECT uuid FROM mapping_qualifier WHERE id=old.fk_mapping_qualifier_id),old.ref_fk_object_id_1,old.ref_fk_object_type_id_1,old.ref_fk_object_id_2,old.ref_fk_object_type_id_2);
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
   comment TEXT(500)
);

DROP TRIGGER IF EXISTS TRIG_tool_log_INSERT;
CREATE TRIGGER TRIG_tool_log_INSERT AFTER INSERT
ON tool
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO tool_log (log_action, id,uuid,fk_tool_type_id, fk_tool_type_uuid,tool_name,vendor,version,comment) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_tool_type_id, (SELECT uuid FROM tool_type WHERE id=new.fk_tool_type_id),new.tool_name,new.vendor,new.version,new.comment);
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
   INSERT INTO tool_log (log_action, id,uuid,fk_tool_type_id, fk_tool_type_uuid,tool_name,vendor,version,comment) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_tool_type_id, (SELECT uuid FROM tool_type WHERE id=new.fk_tool_type_id),new.tool_name,new.vendor,new.version,new.comment);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_tool_log_DELETE;
CREATE TRIGGER TRIG_tool_log_DELETE AFTER DELETE
ON tool
BEGIN
   INSERT INTO tool_log (log_action, id,uuid,fk_tool_type_id, fk_tool_type_uuid,tool_name,vendor,version,comment) VALUES ('DELETE',old.id,old.uuid,old.fk_tool_type_id, (SELECT uuid FROM tool_type WHERE id=old.fk_tool_type_id),old.tool_name,old.vendor,old.version,old.comment);
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
   ref_fk_object_type_id_child INTEGER
);

DROP TRIGGER IF EXISTS TRIG_object_depends_on_log_INSERT;
CREATE TRIGGER TRIG_object_depends_on_log_INSERT AFTER INSERT
ON object_depends_on
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO object_depends_on_log (log_action, id,uuid,ref_fk_object_id_parent,ref_fk_object_type_id_parent,ref_fk_object_id_child,ref_fk_object_type_id_child) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.ref_fk_object_id_parent,new.ref_fk_object_type_id_parent,new.ref_fk_object_id_child,new.ref_fk_object_type_id_child);
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
   INSERT INTO object_depends_on_log (log_action, id,uuid,ref_fk_object_id_parent,ref_fk_object_type_id_parent,ref_fk_object_id_child,ref_fk_object_type_id_child) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.ref_fk_object_id_parent,new.ref_fk_object_type_id_parent,new.ref_fk_object_id_child,new.ref_fk_object_type_id_child);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_object_depends_on_log_DELETE;
CREATE TRIGGER TRIG_object_depends_on_log_DELETE AFTER DELETE
ON object_depends_on
BEGIN
   INSERT INTO object_depends_on_log (log_action, id,uuid,ref_fk_object_id_parent,ref_fk_object_type_id_parent,ref_fk_object_id_child,ref_fk_object_type_id_child) VALUES ('DELETE',old.id,old.uuid,old.ref_fk_object_id_parent,old.ref_fk_object_type_id_parent,old.ref_fk_object_id_child,old.ref_fk_object_type_id_child);
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
   formula TEXT(4000)
);

DROP TRIGGER IF EXISTS TRIG_attribute_log_INSERT;
CREATE TRIGGER TRIG_attribute_log_INSERT AFTER INSERT
ON attribute
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO attribute_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,formula) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.formula);
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
   INSERT INTO attribute_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,formula) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.formula);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_attribute_log_DELETE;
CREATE TRIGGER TRIG_attribute_log_DELETE AFTER DELETE
ON attribute
BEGIN
   INSERT INTO attribute_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,formula) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.formula);
END;


DROP TABLE IF EXISTS tool_type_log;
CREATE TABLE tool_type_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   name TEXT(250),
   description TEXT(500)
);

DROP TRIGGER IF EXISTS TRIG_tool_type_log_INSERT;
CREATE TRIGGER TRIG_tool_type_log_INSERT AFTER INSERT
ON tool_type
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO tool_type_log (log_action, id,uuid,name,description) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.name,new.description);
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
   INSERT INTO tool_type_log (log_action, id,uuid,name,description) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.name,new.description);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_tool_type_log_DELETE;
CREATE TRIGGER TRIG_tool_type_log_DELETE AFTER DELETE
ON tool_type
BEGIN
   INSERT INTO tool_type_log (log_action, id,uuid,name,description) VALUES ('DELETE',old.id,old.uuid,old.name,old.description);
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
   description TEXT(500),
   location TEXT,
   fk_db_table_context_id INTEGER,
   fk_db_table_context_uuid TEXT,
   fk_db_table_type_id INTEGER,
   fk_db_table_type_uuid TEXT
);

DROP TRIGGER IF EXISTS TRIG_db_table_log_INSERT;
CREATE TRIGGER TRIG_db_table_log_INSERT AFTER INSERT
ON db_table
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO db_table_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,location,fk_db_table_context_id, fk_db_table_context_uuid,fk_db_table_type_id, fk_db_table_type_uuid) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.location,new.fk_db_table_context_id, (SELECT uuid FROM db_table_context WHERE id=new.fk_db_table_context_id),new.fk_db_table_type_id, (SELECT uuid FROM db_table_type WHERE id=new.fk_db_table_type_id));
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
   INSERT INTO db_table_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,location,fk_db_table_context_id, fk_db_table_context_uuid,fk_db_table_type_id, fk_db_table_type_uuid) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.location,new.fk_db_table_context_id, (SELECT uuid FROM db_table_context WHERE id=new.fk_db_table_context_id),new.fk_db_table_type_id, (SELECT uuid FROM db_table_type WHERE id=new.fk_db_table_type_id));
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_db_table_log_DELETE;
CREATE TRIGGER TRIG_db_table_log_DELETE AFTER DELETE
ON db_table
BEGIN
   INSERT INTO db_table_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,location,fk_db_table_context_id, fk_db_table_context_uuid,fk_db_table_type_id, fk_db_table_type_uuid) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.location,old.fk_db_table_context_id, (SELECT uuid FROM db_table_context WHERE id=old.fk_db_table_context_id),old.fk_db_table_type_id, (SELECT uuid FROM db_table_type WHERE id=old.fk_db_table_type_id));
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
   bulk_load_checksum TEXT(200)
);

DROP TRIGGER IF EXISTS TRIG_db_table_field_log_INSERT;
CREATE TRIGGER TRIG_db_table_field_log_INSERT AFTER INSERT
ON db_table_field
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO db_table_field_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_db_table_id, fk_db_table_uuid,datatype,bulk_load_checksum) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_db_table_id, (SELECT uuid FROM db_table WHERE id=new.fk_db_table_id),new.datatype,new.bulk_load_checksum);
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
   INSERT INTO db_table_field_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_db_table_id, fk_db_table_uuid,datatype,bulk_load_checksum) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_db_table_id, (SELECT uuid FROM db_table WHERE id=new.fk_db_table_id),new.datatype,new.bulk_load_checksum);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_db_table_field_log_DELETE;
CREATE TRIGGER TRIG_db_table_field_log_DELETE AFTER DELETE
ON db_table_field
BEGIN
   INSERT INTO db_table_field_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_db_table_id, fk_db_table_uuid,datatype,bulk_load_checksum) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.fk_db_table_id, (SELECT uuid FROM db_table WHERE id=old.fk_db_table_id),old.datatype,old.bulk_load_checksum);
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
   prefix TEXT(100)
);

DROP TRIGGER IF EXISTS TRIG_db_table_context_log_INSERT;
CREATE TRIGGER TRIG_db_table_context_log_INSERT AFTER INSERT
ON db_table_context
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO db_table_context_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,prefix) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.prefix);
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
   INSERT INTO db_table_context_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,prefix) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.prefix);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_db_table_context_log_DELETE;
CREATE TRIGGER TRIG_db_table_context_log_DELETE AFTER DELETE
ON db_table_context
BEGIN
   INSERT INTO db_table_context_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,prefix) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.prefix);
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
   fk_tool_uuid TEXT
);

DROP TRIGGER IF EXISTS TRIG_db_database_log_INSERT;
CREATE TRIGGER TRIG_db_database_log_INSERT AFTER INSERT
ON db_database
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO db_database_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_tool_id, fk_tool_uuid) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_tool_id, (SELECT uuid FROM tool WHERE id=new.fk_tool_id));
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
   INSERT INTO db_database_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_tool_id, fk_tool_uuid) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_tool_id, (SELECT uuid FROM tool WHERE id=new.fk_tool_id));
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_db_database_log_DELETE;
CREATE TRIGGER TRIG_db_database_log_DELETE AFTER DELETE
ON db_database
BEGIN
   INSERT INTO db_database_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_tool_id, fk_tool_uuid) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.fk_tool_id, (SELECT uuid FROM tool WHERE id=old.fk_tool_id));
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
   scheduling_series TEXT
);

DROP TRIGGER IF EXISTS TRIG_scheduling_log_INSERT;
CREATE TRIGGER TRIG_scheduling_log_INSERT AFTER INSERT
ON scheduling
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO scheduling_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_tool_id, fk_tool_uuid,scheduling_series) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_tool_id, (SELECT uuid FROM tool WHERE id=new.fk_tool_id),new.scheduling_series);
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
   INSERT INTO scheduling_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_tool_id, fk_tool_uuid,scheduling_series) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_tool_id, (SELECT uuid FROM tool WHERE id=new.fk_tool_id),new.scheduling_series);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_scheduling_log_DELETE;
CREATE TRIGGER TRIG_scheduling_log_DELETE AFTER DELETE
ON scheduling
BEGIN
   INSERT INTO scheduling_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_tool_id, fk_tool_uuid,scheduling_series) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.fk_tool_id, (SELECT uuid FROM tool WHERE id=old.fk_tool_id),old.scheduling_series);
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
   range TEXT
);

DROP TRIGGER IF EXISTS TRIG_parameter_log_INSERT;
CREATE TRIGGER TRIG_parameter_log_INSERT AFTER INSERT
ON parameter
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO parameter_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,is_optional,default_value,datatype,range) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.is_optional,new.default_value,new.datatype,new.range);
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
   INSERT INTO parameter_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,is_optional,default_value,datatype,range) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.is_optional,new.default_value,new.datatype,new.range);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_parameter_log_DELETE;
CREATE TRIGGER TRIG_parameter_log_DELETE AFTER DELETE
ON parameter
BEGIN
   INSERT INTO parameter_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,is_optional,default_value,datatype,range) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.is_optional,old.default_value,old.datatype,old.range);
END;


DROP TABLE IF EXISTS db_table_type_log;
CREATE TABLE db_table_type_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   name TEXT,
   description TEXT
);

DROP TRIGGER IF EXISTS TRIG_db_table_type_log_INSERT;
CREATE TRIGGER TRIG_db_table_type_log_INSERT AFTER INSERT
ON db_table_type
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO db_table_type_log (log_action, id,uuid,name,description) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.name,new.description);
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
   INSERT INTO db_table_type_log (log_action, id,uuid,name,description) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.name,new.description);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_db_table_type_log_DELETE;
CREATE TRIGGER TRIG_db_table_type_log_DELETE AFTER DELETE
ON db_table_type
BEGIN
   INSERT INTO db_table_type_log (log_action, id,uuid,name,description) VALUES ('DELETE',old.id,old.uuid,old.name,old.description);
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
   created_at_datetime TEXT
);

DROP TRIGGER IF EXISTS TRIG_object_comment_log_INSERT;
CREATE TRIGGER TRIG_object_comment_log_INSERT AFTER INSERT
ON object_comment
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO object_comment_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,ref_fk_object_id,ref_fk_object_type_id,comment,created_at_datetime) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.ref_fk_object_id,new.ref_fk_object_type_id,new.comment,new.created_at_datetime);
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
   INSERT INTO object_comment_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,ref_fk_object_id,ref_fk_object_type_id,comment,created_at_datetime) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.ref_fk_object_id,new.ref_fk_object_type_id,new.comment,new.created_at_datetime);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_object_comment_log_DELETE;
CREATE TRIGGER TRIG_object_comment_log_DELETE AFTER DELETE
ON object_comment
BEGIN
   INSERT INTO object_comment_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,ref_fk_object_id,ref_fk_object_type_id,comment,created_at_datetime) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.ref_fk_object_id,old.ref_fk_object_type_id,old.comment,old.created_at_datetime);
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
   description TEXT
);

DROP TRIGGER IF EXISTS TRIG_glossary_log_INSERT;
CREATE TRIGGER TRIG_glossary_log_INSERT AFTER INSERT
ON glossary
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO glossary_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description);
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
   INSERT INTO glossary_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_glossary_log_DELETE;
CREATE TRIGGER TRIG_glossary_log_DELETE AFTER DELETE
ON glossary
BEGIN
   INSERT INTO glossary_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description);
END;


DROP TABLE IF EXISTS data_delivery_type_log;
CREATE TABLE data_delivery_type_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   name TEXT(250),
   description TEXT(500)
);

DROP TRIGGER IF EXISTS TRIG_data_delivery_type_log_INSERT;
CREATE TRIGGER TRIG_data_delivery_type_log_INSERT AFTER INSERT
ON data_delivery_type
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO data_delivery_type_log (log_action, id,uuid,name,description) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.name,new.description);
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
   INSERT INTO data_delivery_type_log (log_action, id,uuid,name,description) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.name,new.description);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_data_delivery_type_log_DELETE;
CREATE TRIGGER TRIG_data_delivery_type_log_DELETE AFTER DELETE
ON data_delivery_type
BEGIN
   INSERT INTO data_delivery_type_log (log_action, id,uuid,name,description) VALUES ('DELETE',old.id,old.uuid,old.name,old.description);
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
   source_internal_object_id TEXT(500)
);

DROP TRIGGER IF EXISTS TRIG_data_transfer_process_log_INSERT;
CREATE TRIGGER TRIG_data_transfer_process_log_INSERT AFTER INSERT
ON data_transfer_process
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO data_transfer_process_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_data_transfer_type_id, fk_data_transfer_type_uuid,location,source_internal_object_id) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_data_transfer_type_id, (SELECT uuid FROM data_transfer_type WHERE id=new.fk_data_transfer_type_id),new.location,new.source_internal_object_id);
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
   INSERT INTO data_transfer_process_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_data_transfer_type_id, fk_data_transfer_type_uuid,location,source_internal_object_id) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_data_transfer_type_id, (SELECT uuid FROM data_transfer_type WHERE id=new.fk_data_transfer_type_id),new.location,new.source_internal_object_id);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_data_transfer_process_log_DELETE;
CREATE TRIGGER TRIG_data_transfer_process_log_DELETE AFTER DELETE
ON data_transfer_process
BEGIN
   INSERT INTO data_transfer_process_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_data_transfer_type_id, fk_data_transfer_type_uuid,location,source_internal_object_id) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.fk_data_transfer_type_id, (SELECT uuid FROM data_transfer_type WHERE id=old.fk_data_transfer_type_id),old.location,old.source_internal_object_id);
END;


DROP TABLE IF EXISTS data_transfer_type_log;
CREATE TABLE data_transfer_type_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   name TEXT(250),
   description TEXT(4000)
);

DROP TRIGGER IF EXISTS TRIG_data_transfer_type_log_INSERT;
CREATE TRIGGER TRIG_data_transfer_type_log_INSERT AFTER INSERT
ON data_transfer_type
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO data_transfer_type_log (log_action, id,uuid,name,description) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.name,new.description);
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
   INSERT INTO data_transfer_type_log (log_action, id,uuid,name,description) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.name,new.description);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_data_transfer_type_log_DELETE;
CREATE TRIGGER TRIG_data_transfer_type_log_DELETE AFTER DELETE
ON data_transfer_type
BEGIN
   INSERT INTO data_transfer_type_log (log_action, id,uuid,name,description) VALUES ('DELETE',old.id,old.uuid,old.name,old.description);
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
   short_name TEXT
);

DROP TRIGGER IF EXISTS TRIG_contact_group_log_INSERT;
CREATE TRIGGER TRIG_contact_group_log_INSERT AFTER INSERT
ON contact_group
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO contact_group_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_client_id, fk_client_uuid,name,description,short_name) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_client_id, (SELECT uuid FROM client WHERE id=new.fk_client_id),new.name,new.description,new.short_name);
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
   INSERT INTO contact_group_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_client_id, fk_client_uuid,name,description,short_name) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_client_id, (SELECT uuid FROM client WHERE id=new.fk_client_id),new.name,new.description,new.short_name);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_contact_group_log_DELETE;
CREATE TRIGGER TRIG_contact_group_log_DELETE AFTER DELETE
ON contact_group
BEGIN
   INSERT INTO contact_group_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_client_id, fk_client_uuid,name,description,short_name) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_client_id, (SELECT uuid FROM client WHERE id=old.fk_client_id),old.name,old.description,old.short_name);
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
   description TEXT
);

DROP TRIGGER IF EXISTS TRIG_contact_log_INSERT;
CREATE TRIGGER TRIG_contact_log_INSERT AFTER INSERT
ON contact
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO contact_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_contact_group_id, fk_contact_group_uuid,fk_client_id, fk_client_uuid,givenname,surname,email,phone,mobile,ldap_cn,description) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_contact_group_id, (SELECT uuid FROM contact_group WHERE id=new.fk_contact_group_id),new.fk_client_id, (SELECT uuid FROM client WHERE id=new.fk_client_id),new.givenname,new.surname,new.email,new.phone,new.mobile,new.ldap_cn,new.description);
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
   INSERT INTO contact_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_contact_group_id, fk_contact_group_uuid,fk_client_id, fk_client_uuid,givenname,surname,email,phone,mobile,ldap_cn,description) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_contact_group_id, (SELECT uuid FROM contact_group WHERE id=new.fk_contact_group_id),new.fk_client_id, (SELECT uuid FROM client WHERE id=new.fk_client_id),new.givenname,new.surname,new.email,new.phone,new.mobile,new.ldap_cn,new.description);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_contact_log_DELETE;
CREATE TRIGGER TRIG_contact_log_DELETE AFTER DELETE
ON contact
BEGIN
   INSERT INTO contact_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_contact_group_id, fk_contact_group_uuid,fk_client_id, fk_client_uuid,givenname,surname,email,phone,mobile,ldap_cn,description) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_contact_group_id, (SELECT uuid FROM contact_group WHERE id=old.fk_contact_group_id),old.fk_client_id, (SELECT uuid FROM client WHERE id=old.fk_client_id),old.givenname,old.surname,old.email,old.phone,old.mobile,old.ldap_cn,old.description);
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
   fk_object_type_uuid_as_searchFilter TEXT
);

DROP TRIGGER IF EXISTS TRIG_bracket_log_INSERT;
CREATE TRIGGER TRIG_bracket_log_INSERT AFTER INSERT
ON bracket
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO bracket_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_attribute_id, fk_attribute_uuid,fk_object_type_id_as_searchFilter, fk_object_type_uuid_as_searchFilter) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_attribute_id, (SELECT uuid FROM attribute WHERE id=new.fk_attribute_id),new.fk_object_type_id_as_searchFilter, (SELECT uuid FROM object_type_as_searchFilter WHERE id=new.fk_object_type_id_as_searchFilter));
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
   INSERT INTO bracket_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_attribute_id, fk_attribute_uuid,fk_object_type_id_as_searchFilter, fk_object_type_uuid_as_searchFilter) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_attribute_id, (SELECT uuid FROM attribute WHERE id=new.fk_attribute_id),new.fk_object_type_id_as_searchFilter, (SELECT uuid FROM object_type_as_searchFilter WHERE id=new.fk_object_type_id_as_searchFilter));
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_bracket_log_DELETE;
CREATE TRIGGER TRIG_bracket_log_DELETE AFTER DELETE
ON bracket
BEGIN
   INSERT INTO bracket_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_attribute_id, fk_attribute_uuid,fk_object_type_id_as_searchFilter, fk_object_type_uuid_as_searchFilter) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.fk_attribute_id, (SELECT uuid FROM attribute WHERE id=old.fk_attribute_id),old.fk_object_type_id_as_searchFilter, (SELECT uuid FROM object_type_as_searchFilter WHERE id=old.fk_object_type_id_as_searchFilter));
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
   searchPattern TEXT(500)
);

DROP TRIGGER IF EXISTS TRIG_bracket_searchPattern_log_INSERT;
CREATE TRIGGER TRIG_bracket_searchPattern_log_INSERT AFTER INSERT
ON bracket_searchPattern
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO bracket_searchPattern_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_bracket_id, fk_bracket_uuid,searchPattern) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_bracket_id, (SELECT uuid FROM bracket WHERE id=new.fk_bracket_id),new.searchPattern);
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
   INSERT INTO bracket_searchPattern_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_bracket_id, fk_bracket_uuid,searchPattern) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_bracket_id, (SELECT uuid FROM bracket WHERE id=new.fk_bracket_id),new.searchPattern);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_bracket_searchPattern_log_DELETE;
CREATE TRIGGER TRIG_bracket_searchPattern_log_DELETE AFTER DELETE
ON bracket_searchPattern
BEGIN
   INSERT INTO bracket_searchPattern_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_bracket_id, fk_bracket_uuid,searchPattern) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_bracket_id, (SELECT uuid FROM bracket WHERE id=old.fk_bracket_id),old.searchPattern);
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
--    _import_date TIMESTAMP
-- );
-- 
-- DROP TRIGGER IF EXISTS TRIG_import_stage_db_table_log_INSERT;
-- CREATE TRIGGER TRIG_import_stage_db_table_log_INSERT AFTER INSERT
-- ON import_stage_db_table
-- BEGIN
--    INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
--    INSERT INTO import_stage_db_table_log (log_action, id,client_name,project_name,db_table_name,db_table_description,db_table_field_name,db_table_field_datatype,db_table_field_description,db_table_type_name,db_table_context_name,db_table_context_prefix,isPrimaryKeyField,isForeignKeyField,foreignKey_table_name,foreignKey_table_field_name,_import_state,_import_date) VALUES ('INSERT',new.id,new.client_name,new.project_name,new.db_table_name,new.db_table_description,new.db_table_field_name,new.db_table_field_datatype,new.db_table_field_description,new.db_table_type_name,new.db_table_context_name,new.db_table_context_prefix,new.isPrimaryKeyField,new.isForeignKeyField,new.foreignKey_table_name,new.foreignKey_table_field_name,new._import_state,new._import_date);
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
--    INSERT INTO import_stage_db_table_log (log_action, id,client_name,project_name,db_table_name,db_table_description,db_table_field_name,db_table_field_datatype,db_table_field_description,db_table_type_name,db_table_context_name,db_table_context_prefix,isPrimaryKeyField,isForeignKeyField,foreignKey_table_name,foreignKey_table_field_name,_import_state,_import_date) VALUES ('UPDATE',new.id,new.client_name,new.project_name,new.db_table_name,new.db_table_description,new.db_table_field_name,new.db_table_field_datatype,new.db_table_field_description,new.db_table_type_name,new.db_table_context_name,new.db_table_context_prefix,new.isPrimaryKeyField,new.isForeignKeyField,new.foreignKey_table_name,new.foreignKey_table_field_name,new._import_state,new._import_date);
--    DELETE FROM _newUUID;
-- END;

-- DROP TRIGGER IF EXISTS TRIG_import_stage_db_table_log_DELETE;
-- CREATE TRIGGER TRIG_import_stage_db_table_log_DELETE AFTER DELETE
-- ON import_stage_db_table
-- BEGIN
--    INSERT INTO import_stage_db_table_log (log_action, id,client_name,project_name,db_table_name,db_table_description,db_table_field_name,db_table_field_datatype,db_table_field_description,db_table_type_name,db_table_context_name,db_table_context_prefix,isPrimaryKeyField,isForeignKeyField,foreignKey_table_name,foreignKey_table_field_name,_import_state,_import_date) VALUES ('DELETE',old.id,old.client_name,old.project_name,old.db_table_name,old.db_table_description,old.db_table_field_name,old.db_table_field_datatype,old.db_table_field_description,old.db_table_type_name,old.db_table_context_name,old.db_table_context_prefix,old.isPrimaryKeyField,old.isForeignKeyField,old.foreignKey_table_name,old.foreignKey_table_field_name,old._import_state,old._import_date);
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
   ref_fk_object_type_id INTEGER
);

DROP TRIGGER IF EXISTS TRIG_perspective_filter_log_INSERT;
CREATE TRIGGER TRIG_perspective_filter_log_INSERT AFTER INSERT
ON perspective_filter
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO perspective_filter_log (log_action, id,uuid,fk_language_id, fk_object_type_id, fk_object_type_uuid,filter_attribute_name,filter_value,ref_fk_object_type_id) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_language_id, new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.filter_attribute_name,new.filter_value,new.ref_fk_object_type_id);
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
   INSERT INTO perspective_filter_log (log_action, id,uuid,fk_language_id, fk_object_type_id, fk_object_type_uuid,filter_attribute_name,filter_value,ref_fk_object_type_id) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_language_id, new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.filter_attribute_name,new.filter_value,new.ref_fk_object_type_id);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_perspective_filter_log_DELETE;
CREATE TRIGGER TRIG_perspective_filter_log_DELETE AFTER DELETE
ON perspective_filter
BEGIN
   INSERT INTO perspective_filter_log (log_action, id,uuid,fk_language_id, fk_object_type_id, fk_object_type_uuid,filter_attribute_name,filter_value,ref_fk_object_type_id) VALUES ('DELETE',old.id,old.uuid,old.fk_language_id, (SELECT uuid FROM language WHERE id=old.fk_language_id),old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.filter_attribute_name,old.filter_value,old.ref_fk_object_type_id);
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
   needs_object_depends_on BOOLEAN
);

DROP TRIGGER IF EXISTS TRIG_mapping_qualifier_log_INSERT;
CREATE TRIGGER TRIG_mapping_qualifier_log_INSERT AFTER INSERT
ON mapping_qualifier
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO mapping_qualifier_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,name,short_name,description,needs_object_depends_on) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.name,new.short_name,new.description,new.needs_object_depends_on);
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
   INSERT INTO mapping_qualifier_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,name,short_name,description,needs_object_depends_on) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.name,new.short_name,new.description,new.needs_object_depends_on);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_mapping_qualifier_log_DELETE;
CREATE TRIGGER TRIG_mapping_qualifier_log_DELETE AFTER DELETE
ON mapping_qualifier
BEGIN
   INSERT INTO mapping_qualifier_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,name,short_name,description,needs_object_depends_on) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.name,old.short_name,old.description,old.needs_object_depends_on);
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
   url TEXT(4000)
);

DROP TRIGGER IF EXISTS TRIG_url_log_INSERT;
CREATE TRIGGER TRIG_url_log_INSERT AFTER INSERT
ON url
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO url_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,url) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.url);
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
   INSERT INTO url_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,url) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.url);
   DELETE FROM _newUUID;
END;

DROP TRIGGER IF EXISTS TRIG_url_log_DELETE;
CREATE TRIGGER TRIG_url_log_DELETE AFTER DELETE
ON url
BEGIN
   INSERT INTO url_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,url) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.url);
END;

