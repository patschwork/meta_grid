-- Manual script execution via sqlite3-cli 

DROP TRIGGER TRIG_sourcesystem_log_INSERT;
DROP TRIGGER TRIG_sourcesystem_log_UPDATE;
DROP TRIGGER TRIG_sourcesystem_log_DELETE;

ALTER TABLE sourcesystem RENAME TO sourcesystem_a8401d41ec5d4516bca7890552b43423;

CREATE TABLE sourcesystem (
id INTEGER NOT NULL  PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER NOT NULL  DEFAULT 2 REFERENCES object_type (id),
fk_project_id INTEGER DEFAULT NULL REFERENCES project (id),
name TEXT(250) DEFAULT NULL,
description TEXT(4000) DEFAULT NULL, 
fk_contact_group_id_as_supporter INTEGER
)
;

INSERT INTO sourcesystem
(
    id,uuid,fk_object_type_id,fk_project_id,name,description,fk_contact_group_id_as_supporter
)
SELECT * FROM sourcesystem_a8401d41ec5d4516bca7890552b43423;


CREATE TRIGGER TRIG_sourcesystem_log_INSERT AFTER INSERT
ON sourcesystem
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO sourcesystem_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_contact_group_id_as_supporter, fk_contact_group_uuid_as_supporter) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_contact_group_id_as_supporter, (SELECT uuid FROM contact_group WHERE id=new.fk_contact_group_id_as_supporter));
   UPDATE sourcesystem SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM sourcesystem_log WHERE log_id=(SELECT MAX(log_id)+1 FROM sourcesystem_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

CREATE TRIGGER TRIG_sourcesystem_log_UPDATE AFTER UPDATE
ON sourcesystem
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE sourcesystem SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO sourcesystem_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_contact_group_id_as_supporter, fk_contact_group_uuid_as_supporter) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_contact_group_id_as_supporter, (SELECT uuid FROM contact_group WHERE id=new.fk_contact_group_id_as_supporter));
   DELETE FROM _newUUID;
END;

CREATE TRIGGER TRIG_sourcesystem_log_DELETE AFTER DELETE
ON sourcesystem
BEGIN
   INSERT INTO sourcesystem_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_contact_group_id_as_supporter, fk_contact_group_uuid_as_supporter) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.fk_contact_group_id_as_supporter, (SELECT uuid FROM contact_group WHERE id=old.fk_contact_group_id_as_supporter));
END;

DROP TABLE sourcesystem_a8401d41ec5d4516bca7890552b43423;

ALTER TABLE sourcesystem_log RENAME TO sourcesystem_log_a36e614181c64a6fb45cbdd6269cb115;

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
   description TEXT(4000), 
   fk_contact_group_id_as_supporter INTEGER, 
   fk_contact_group_uuid_as_supporter TEXT
)
;

INSERT INTO sourcesystem_log
(
    log_id,log_datetime,log_action,id,uuid,fk_object_type_id,fk_object_type_uuid,fk_project_id,fk_project_uuid,name,description,fk_contact_group_id_as_supporter,fk_contact_group_uuid_as_supporter
)
SELECT * FROM sourcesystem_log_a36e614181c64a6fb45cbdd6269cb115;

DROP TABLE sourcesystem_log_a36e614181c64a6fb45cbdd6269cb115;

DROP TRIGGER TRIG_attribute_log_INSERT;
DROP TRIGGER TRIG_attribute_log_UPDATE;
DROP TRIGGER TRIG_attribute_log_DELETE;

ALTER TABLE attribute RENAME TO attribute_5588c4095ec14a6a8276fde71a401ac7;

CREATE TABLE attribute (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER NOT NULL  DEFAULT 9 REFERENCES object_type (id),
fk_project_id INTEGER NOT NULL  DEFAULT NULL REFERENCES project (id),
name TEXT(250) DEFAULT NULL,
description TEXT(4000) DEFAULT NULL,
formula TEXT(4000) DEFAULT NULL
)
;

INSERT INTO attribute
(
    id,uuid,fk_object_type_id,fk_project_id,name,description,formula
)
SELECT * FROM attribute_5588c4095ec14a6a8276fde71a401ac7;


CREATE TRIGGER TRIG_attribute_log_INSERT AFTER INSERT
ON attribute
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO attribute_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,formula) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.formula);
   UPDATE attribute SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM attribute_log WHERE log_id=(SELECT MAX(log_id)+1 FROM attribute_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

CREATE TRIGGER TRIG_attribute_log_UPDATE AFTER UPDATE
ON attribute
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE attribute SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO attribute_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,formula) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.formula);
   DELETE FROM _newUUID;
END;

CREATE TRIGGER TRIG_attribute_log_DELETE AFTER DELETE
ON attribute
BEGIN
   INSERT INTO attribute_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,formula) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.formula);
END;

DROP TABLE attribute_5588c4095ec14a6a8276fde71a401ac7;

ALTER TABLE attribute_log RENAME TO attribute_log_1f88fa90e2224ff09fa6cf5a2ef90c20;

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
)
;

INSERT INTO attribute_log
(
    log_id,log_datetime,log_action,id,uuid,fk_object_type_id,fk_object_type_uuid,fk_project_id,fk_project_uuid,name,description,formula
)
SELECT * FROM attribute_log_1f88fa90e2224ff09fa6cf5a2ef90c20;


DROP TABLE attribute_log_1f88fa90e2224ff09fa6cf5a2ef90c20;
DROP TRIGGER TRIG_bracket_log_INSERT;
DROP TRIGGER TRIG_bracket_log_UPDATE;
DROP TRIGGER TRIG_bracket_log_DELETE;
DROP TRIGGER TRIG_bracket_searchPattern_log_INSERT;
DROP TRIGGER TRIG_bracket_searchPattern_log_UPDATE;
DROP TRIGGER TRIG_bracket_searchPattern_log_DELETE;

ALTER TABLE bracket RENAME TO bracket_84d6a6f50a6642e6a559204a17cc1895;

CREATE TABLE bracket (
id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER NOT NULL  DEFAULT 16 REFERENCES object_type (id),
fk_project_id INTEGER DEFAULT NULL REFERENCES project (id),
name TEXT(250) DEFAULT NULL,
description TEXT(4000) DEFAULT NULL,
fk_attribute_id INTEGER NULL DEFAULT NULL REFERENCES attribute (id),
fk_object_type_id_as_searchFilter INTEGER DEFAULT NULL REFERENCES object_type (id)
)
;

INSERT INTO bracket
(
    id,uuid,fk_object_type_id,fk_project_id,name,description,fk_attribute_id,fk_object_type_id_as_searchFilter
)
SELECT * FROM bracket_84d6a6f50a6642e6a559204a17cc1895;


CREATE TRIGGER TRIG_bracket_log_INSERT AFTER INSERT
ON bracket
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO bracket_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_attribute_id, fk_attribute_uuid,fk_object_type_id_as_searchFilter, fk_object_type_uuid_as_searchFilter) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_attribute_id, (SELECT uuid FROM attribute WHERE id=new.fk_attribute_id),new.fk_object_type_id_as_searchFilter, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id_as_searchFilter));
   UPDATE bracket SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM bracket_log WHERE log_id=(SELECT MAX(log_id)+1 FROM bracket_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

CREATE TRIGGER TRIG_bracket_log_UPDATE AFTER UPDATE
ON bracket
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE bracket SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO bracket_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_attribute_id, fk_attribute_uuid,fk_object_type_id_as_searchFilter, fk_object_type_uuid_as_searchFilter) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_attribute_id, (SELECT uuid FROM attribute WHERE id=new.fk_attribute_id),new.fk_object_type_id_as_searchFilter, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id_as_searchFilter));
   DELETE FROM _newUUID;
END;

CREATE TRIGGER TRIG_bracket_log_DELETE AFTER DELETE
ON bracket
BEGIN
   INSERT INTO bracket_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_attribute_id, fk_attribute_uuid,fk_object_type_id_as_searchFilter, fk_object_type_uuid_as_searchFilter) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.fk_attribute_id, (SELECT uuid FROM attribute WHERE id=old.fk_attribute_id),old.fk_object_type_id_as_searchFilter, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id_as_searchFilter));
END;

CREATE TRIGGER TRIG_bracket_searchPattern_log_INSERT AFTER INSERT
ON bracket_searchPattern
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO bracket_searchPattern_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_bracket_id, fk_bracket_uuid,searchPattern) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_bracket_id, (SELECT uuid FROM bracket WHERE id=new.fk_bracket_id),new.searchPattern);
   UPDATE bracket_searchPattern SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM bracket_searchPattern_log WHERE log_id=(SELECT MAX(log_id)+1 FROM bracket_searchPattern_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

CREATE TRIGGER TRIG_bracket_searchPattern_log_UPDATE AFTER UPDATE
ON bracket_searchPattern
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE bracket_searchPattern SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO bracket_searchPattern_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_bracket_id, fk_bracket_uuid,searchPattern) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_bracket_id, (SELECT uuid FROM bracket WHERE id=new.fk_bracket_id),new.searchPattern);
   DELETE FROM _newUUID;
END;

CREATE TRIGGER TRIG_bracket_searchPattern_log_DELETE AFTER DELETE
ON bracket_searchPattern
BEGIN
   INSERT INTO bracket_searchPattern_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_bracket_id, fk_bracket_uuid,searchPattern) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_bracket_id, (SELECT uuid FROM bracket WHERE id=old.fk_bracket_id),old.searchPattern);
END;

DROP TABLE bracket_84d6a6f50a6642e6a559204a17cc1895;

ALTER TABLE bracket_log RENAME TO bracket_log_d53c7f5d7e08469aad7fb30678342846;

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
)
;

INSERT INTO bracket_log
(
    log_id,log_datetime,log_action,id,uuid,fk_object_type_id,fk_object_type_uuid,fk_project_id,fk_project_uuid,name,description,fk_attribute_id,fk_attribute_uuid,fk_object_type_id_as_searchFilter,fk_object_type_uuid_as_searchFilter
)
SELECT * FROM bracket_log_d53c7f5d7e08469aad7fb30678342846;


DROP TABLE bracket_log_d53c7f5d7e08469aad7fb30678342846;
DROP TRIGGER TRIG_client_log_INSERT;
DROP TRIGGER TRIG_client_log_UPDATE;
DROP TRIGGER TRIG_client_log_DELETE;

ALTER TABLE client RENAME TO client_f9a8a9494b7d444eb32b85ea5b467eb1;

CREATE TABLE client (
id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
name TEXT(250) DEFAULT NULL,
description TEXT(4000) DEFAULT NULL
)
;

INSERT INTO client
(
    id,uuid,name,description
)
SELECT * FROM client_f9a8a9494b7d444eb32b85ea5b467eb1;


CREATE TRIGGER TRIG_client_log_INSERT AFTER INSERT
ON client
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO client_log (log_action, id,uuid,name,description) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.name,new.description);
   UPDATE client SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM client_log WHERE log_id=(SELECT MAX(log_id)+1 FROM client_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

CREATE TRIGGER TRIG_client_log_UPDATE AFTER UPDATE
ON client
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE client SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO client_log (log_action, id,uuid,name,description) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.name,new.description);
   DELETE FROM _newUUID;
END;

CREATE TRIGGER TRIG_client_log_DELETE AFTER DELETE
ON client
BEGIN
   INSERT INTO client_log (log_action, id,uuid,name,description) VALUES ('DELETE',old.id,old.uuid,old.name,old.description);
END;

DROP TABLE client_f9a8a9494b7d444eb32b85ea5b467eb1;

ALTER TABLE client_log RENAME TO client_log_ceb568d74cbd42549e0441561c6576a7;

CREATE TABLE client_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   name TEXT(250),
   description TEXT(4000)
)
;

INSERT INTO client_log
(
    log_id,log_datetime,log_action,id,uuid,name,description
)
SELECT * FROM client_log_ceb568d74cbd42549e0441561c6576a7;


DROP TABLE client_log_ceb568d74cbd42549e0441561c6576a7;
DROP TRIGGER TRIG_data_delivery_object_log_INSERT;
DROP TRIGGER TRIG_data_delivery_object_log_UPDATE;
DROP TRIGGER TRIG_data_delivery_object_log_DELETE;

ALTER TABLE data_delivery_object RENAME TO data_delivery_object_94afaf92f0dd4ac4b6b898edba7e66f2;

CREATE TABLE data_delivery_object (
id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER NOT NULL  DEFAULT 3 REFERENCES object_type (id),
fk_project_id INTEGER DEFAULT NULL REFERENCES project (id),
name TEXT(250) DEFAULT NULL,
description TEXT(4000) DEFAULT NULL,
fk_tool_id INTEGER NOT NULL  DEFAULT NULL REFERENCES tool (id),
fk_data_delivery_type_id INTEGER DEFAULT NULL REFERENCES data_delivery_type (id),
fk_contact_group_id_as_data_owner INTEGER DEFAULT NULL REFERENCES contact_group (id)
)
;

INSERT INTO data_delivery_object
(
    id,uuid,fk_object_type_id,fk_project_id,name,description,fk_tool_id,fk_data_delivery_type_id,fk_contact_group_id_as_data_owner
)
SELECT * FROM data_delivery_object_94afaf92f0dd4ac4b6b898edba7e66f2;


CREATE TRIGGER TRIG_data_delivery_object_log_INSERT AFTER INSERT
ON data_delivery_object
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO data_delivery_object_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_tool_id, fk_tool_uuid,fk_data_delivery_type_id, fk_data_delivery_type_uuid,fk_contact_group_id_as_data_owner, fk_contact_group_uuid_as_data_owner) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_tool_id, (SELECT uuid FROM tool WHERE id=new.fk_tool_id),new.fk_data_delivery_type_id, (SELECT uuid FROM data_delivery_type WHERE id=new.fk_data_delivery_type_id),new.fk_contact_group_id_as_data_owner, (SELECT uuid FROM contact_group WHERE id=new.fk_contact_group_id_as_data_owner));
   UPDATE data_delivery_object SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM data_delivery_object_log WHERE log_id=(SELECT MAX(log_id)+1 FROM data_delivery_object_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

CREATE TRIGGER TRIG_data_delivery_object_log_UPDATE AFTER UPDATE
ON data_delivery_object
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE data_delivery_object SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO data_delivery_object_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_tool_id, fk_tool_uuid,fk_data_delivery_type_id, fk_data_delivery_type_uuid,fk_contact_group_id_as_data_owner, fk_contact_group_uuid_as_data_owner) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_tool_id, (SELECT uuid FROM tool WHERE id=new.fk_tool_id),new.fk_data_delivery_type_id, (SELECT uuid FROM data_delivery_type WHERE id=new.fk_data_delivery_type_id),new.fk_contact_group_id_as_data_owner, (SELECT uuid FROM contact_group WHERE id=new.fk_contact_group_id_as_data_owner));
   DELETE FROM _newUUID;
END;

CREATE TRIGGER TRIG_data_delivery_object_log_DELETE AFTER DELETE
ON data_delivery_object
BEGIN
   INSERT INTO data_delivery_object_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_tool_id, fk_tool_uuid,fk_data_delivery_type_id, fk_data_delivery_type_uuid,fk_contact_group_id_as_data_owner, fk_contact_group_uuid_as_data_owner) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.fk_tool_id, (SELECT uuid FROM tool WHERE id=old.fk_tool_id),old.fk_data_delivery_type_id, (SELECT uuid FROM data_delivery_type WHERE id=old.fk_data_delivery_type_id),old.fk_contact_group_id_as_data_owner, (SELECT uuid FROM contact_group WHERE id=old.fk_contact_group_id_as_data_owner));
END;

DROP TABLE data_delivery_object_94afaf92f0dd4ac4b6b898edba7e66f2;

ALTER TABLE data_delivery_object_log RENAME TO data_delivery_object_log_bba00cc5b9b64714893e64c33dafa3e3;

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
   description TEXT(4000),
   fk_tool_id INTEGER,
   fk_tool_uuid TEXT,
   fk_data_delivery_type_id INTEGER,
   fk_data_delivery_type_uuid TEXT,
   fk_contact_group_id_as_data_owner INTEGER,
   fk_contact_group_uuid_as_data_owner TEXT
)
;

INSERT INTO data_delivery_object_log
(
    log_id,log_datetime,log_action,id,uuid,fk_object_type_id,fk_object_type_uuid,fk_project_id,fk_project_uuid,name,description,fk_tool_id,fk_tool_uuid,fk_data_delivery_type_id,fk_data_delivery_type_uuid,fk_contact_group_id_as_data_owner,fk_contact_group_uuid_as_data_owner
)
SELECT * FROM data_delivery_object_log_bba00cc5b9b64714893e64c33dafa3e3;


DROP TABLE data_delivery_object_log_bba00cc5b9b64714893e64c33dafa3e3;
DROP TRIGGER TRIG_data_delivery_type_log_INSERT;
DROP TRIGGER TRIG_data_delivery_type_log_UPDATE;
DROP TRIGGER TRIG_data_delivery_type_log_DELETE;

ALTER TABLE data_delivery_type RENAME TO data_delivery_type_bdf2aa61f85c4960b08e507ea2e93fdf;

CREATE TABLE data_delivery_type (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
name TEXT(250) DEFAULT NULL,
description TEXT(4000) DEFAULT NULL
)
;

INSERT INTO data_delivery_type
(
    id,uuid,name,description
)
SELECT * FROM data_delivery_type_bdf2aa61f85c4960b08e507ea2e93fdf;


CREATE TRIGGER TRIG_data_delivery_type_log_INSERT AFTER INSERT
ON data_delivery_type
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO data_delivery_type_log (log_action, id,uuid,name,description) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.name,new.description);
   UPDATE data_delivery_type SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM data_delivery_type_log WHERE log_id=(SELECT MAX(log_id)+1 FROM data_delivery_type_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

CREATE TRIGGER TRIG_data_delivery_type_log_UPDATE AFTER UPDATE
ON data_delivery_type
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE data_delivery_type SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO data_delivery_type_log (log_action, id,uuid,name,description) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.name,new.description);
   DELETE FROM _newUUID;
END;

CREATE TRIGGER TRIG_data_delivery_type_log_DELETE AFTER DELETE
ON data_delivery_type
BEGIN
   INSERT INTO data_delivery_type_log (log_action, id,uuid,name,description) VALUES ('DELETE',old.id,old.uuid,old.name,old.description);
END;

DROP TABLE data_delivery_type_bdf2aa61f85c4960b08e507ea2e93fdf;

ALTER TABLE data_delivery_type_log RENAME TO data_delivery_type_log_87ff13688b6a4bf8a82bd452e5569900;

CREATE TABLE data_delivery_type_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   name TEXT(250),
   description TEXT(4000)
)
;

INSERT INTO data_delivery_type_log
(
    log_id,log_datetime,log_action,id,uuid,name,description
)
SELECT * FROM data_delivery_type_log_87ff13688b6a4bf8a82bd452e5569900;


DROP TABLE data_delivery_type_log_87ff13688b6a4bf8a82bd452e5569900;
DROP TRIGGER TRIG_data_transfer_process_log_INSERT;
DROP TRIGGER TRIG_data_transfer_process_log_UPDATE;
DROP TRIGGER TRIG_data_transfer_process_log_DELETE;

ALTER TABLE data_transfer_process RENAME TO data_transfer_process_e26d56c7bd334a999d2ea7f13a146c06;

CREATE TABLE data_transfer_process (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER NOT NULL  DEFAULT 13 REFERENCES object_type (id),
fk_project_id INTEGER NOT NULL  DEFAULT NULL REFERENCES project (id),
name TEXT(250) DEFAULT NULL,
description TEXT(4000) DEFAULT NULL,
fk_data_transfer_type_id INTEGER DEFAULT NULL REFERENCES data_transfer_type (id)
)
;

INSERT INTO data_transfer_process
(
    id,uuid,fk_object_type_id,fk_project_id,name,description,fk_data_transfer_type_id
)
SELECT * FROM data_transfer_process_e26d56c7bd334a999d2ea7f13a146c06;


CREATE TRIGGER TRIG_data_transfer_process_log_INSERT AFTER INSERT
ON data_transfer_process
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO data_transfer_process_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_data_transfer_type_id, fk_data_transfer_type_uuid) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_data_transfer_type_id, (SELECT uuid FROM data_transfer_type WHERE id=new.fk_data_transfer_type_id));
   UPDATE data_transfer_process SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM data_transfer_process_log WHERE log_id=(SELECT MAX(log_id)+1 FROM data_transfer_process_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

CREATE TRIGGER TRIG_data_transfer_process_log_UPDATE AFTER UPDATE
ON data_transfer_process
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE data_transfer_process SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO data_transfer_process_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_data_transfer_type_id, fk_data_transfer_type_uuid) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_data_transfer_type_id, (SELECT uuid FROM data_transfer_type WHERE id=new.fk_data_transfer_type_id));
   DELETE FROM _newUUID;
END;

CREATE TRIGGER TRIG_data_transfer_process_log_DELETE AFTER DELETE
ON data_transfer_process
BEGIN
   INSERT INTO data_transfer_process_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_data_transfer_type_id, fk_data_transfer_type_uuid) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.fk_data_transfer_type_id, (SELECT uuid FROM data_transfer_type WHERE id=old.fk_data_transfer_type_id));
END;

DROP TABLE data_transfer_process_e26d56c7bd334a999d2ea7f13a146c06;

ALTER TABLE data_transfer_process_log RENAME TO data_transfer_process_log_bc5f862226e44b14807c70e6f2615f6a;

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
   fk_data_transfer_type_uuid TEXT
)
;

INSERT INTO data_transfer_process_log
(
    log_id,log_datetime,log_action,id,uuid,fk_object_type_id,fk_object_type_uuid,fk_project_id,fk_project_uuid,name,description,fk_data_transfer_type_id,fk_data_transfer_type_uuid
)
SELECT * FROM data_transfer_process_log_bc5f862226e44b14807c70e6f2615f6a;


DROP TABLE data_transfer_process_log_bc5f862226e44b14807c70e6f2615f6a;
DROP TRIGGER TRIG_data_transfer_type_log_INSERT;
DROP TRIGGER TRIG_data_transfer_type_log_UPDATE;
DROP TRIGGER TRIG_data_transfer_type_log_DELETE;

ALTER TABLE data_transfer_type RENAME TO data_transfer_type_5dfeb599f9634c02aff3ad7d5f3d4c09;

CREATE TABLE data_transfer_type (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
name TEXT(250) DEFAULT NULL,
description TEXT(4000) DEFAULT NULL
)
;

INSERT INTO data_transfer_type
(
    id,uuid,name,description
)
SELECT * FROM data_transfer_type_5dfeb599f9634c02aff3ad7d5f3d4c09;


CREATE TRIGGER TRIG_data_transfer_type_log_INSERT AFTER INSERT
ON data_transfer_type
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO data_transfer_type_log (log_action, id,uuid,name,description) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.name,new.description);
   UPDATE data_transfer_type SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM data_transfer_type_log WHERE log_id=(SELECT MAX(log_id)+1 FROM data_transfer_type_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

CREATE TRIGGER TRIG_data_transfer_type_log_UPDATE AFTER UPDATE
ON data_transfer_type
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE data_transfer_type SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO data_transfer_type_log (log_action, id,uuid,name,description) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.name,new.description);
   DELETE FROM _newUUID;
END;

CREATE TRIGGER TRIG_data_transfer_type_log_DELETE AFTER DELETE
ON data_transfer_type
BEGIN
   INSERT INTO data_transfer_type_log (log_action, id,uuid,name,description) VALUES ('DELETE',old.id,old.uuid,old.name,old.description);
END;

DROP TABLE data_transfer_type_5dfeb599f9634c02aff3ad7d5f3d4c09;

ALTER TABLE data_transfer_type_log RENAME TO data_transfer_type_log_579949c61b8f44e39b467c90040dd373;

CREATE TABLE data_transfer_type_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   name TEXT(250),
   description TEXT(4000)
)
;

INSERT INTO data_transfer_type_log
(
    log_id,log_datetime,log_action,id,uuid,name,description
)
SELECT * FROM data_transfer_type_log_579949c61b8f44e39b467c90040dd373;


DROP TABLE data_transfer_type_log_579949c61b8f44e39b467c90040dd373;
DROP TRIGGER TRIG_db_table_log_INSERT;
DROP TRIGGER TRIG_db_table_log_UPDATE;
DROP TRIGGER TRIG_db_table_log_DELETE;
DROP TRIGGER TRIG_db_table_type_log_INSERT;
DROP TRIGGER TRIG_db_table_type_log_UPDATE;
DROP TRIGGER TRIG_db_table_type_log_DELETE;
DROP TRIGGER TRIG_db_table_context_log_INSERT;
DROP TRIGGER TRIG_db_table_context_log_UPDATE;
DROP TRIGGER TRIG_db_table_context_log_DELETE;
DROP TRIGGER TRIG_db_table_field_log_INSERT;
DROP TRIGGER TRIG_db_table_field_log_UPDATE;
DROP TRIGGER TRIG_db_table_field_log_DELETE;

ALTER TABLE db_table RENAME TO db_table_5696fd4620d54a41a4e5ac7cea1cf464;

CREATE TABLE db_table (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER NOT NULL  DEFAULT 4 REFERENCES object_type (id),
fk_project_id INTEGER NOT NULL  DEFAULT NULL REFERENCES project (id),
name TEXT(250) DEFAULT NULL,
description TEXT(4000) DEFAULT NULL,
location TEXT DEFAULT NULL,
fk_db_table_context_id INTEGER DEFAULT NULL REFERENCES db_table_context (id),
fk_db_table_type_id INTEGER DEFAULT NULL REFERENCES db_table_type (id)
)
;

INSERT INTO db_table
(
    id,uuid,fk_object_type_id,fk_project_id,name,description,location,fk_db_table_context_id,fk_db_table_type_id
)
SELECT * FROM db_table_5696fd4620d54a41a4e5ac7cea1cf464;


CREATE TRIGGER TRIG_db_table_log_INSERT AFTER INSERT
ON db_table
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO db_table_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,location,fk_db_table_context_id, fk_db_table_context_uuid,fk_db_table_type_id, fk_db_table_type_uuid) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.location,new.fk_db_table_context_id, (SELECT uuid FROM db_table_context WHERE id=new.fk_db_table_context_id),new.fk_db_table_type_id, (SELECT uuid FROM db_table_type WHERE id=new.fk_db_table_type_id));
   UPDATE db_table SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM db_table_log WHERE log_id=(SELECT MAX(log_id)+1 FROM db_table_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

CREATE TRIGGER TRIG_db_table_log_UPDATE AFTER UPDATE
ON db_table
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE db_table SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO db_table_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,location,fk_db_table_context_id, fk_db_table_context_uuid,fk_db_table_type_id, fk_db_table_type_uuid) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.location,new.fk_db_table_context_id, (SELECT uuid FROM db_table_context WHERE id=new.fk_db_table_context_id),new.fk_db_table_type_id, (SELECT uuid FROM db_table_type WHERE id=new.fk_db_table_type_id));
   DELETE FROM _newUUID;
END;

CREATE TRIGGER TRIG_db_table_log_DELETE AFTER DELETE
ON db_table
BEGIN
   INSERT INTO db_table_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,location,fk_db_table_context_id, fk_db_table_context_uuid,fk_db_table_type_id, fk_db_table_type_uuid) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.location,old.fk_db_table_context_id, (SELECT uuid FROM db_table_context WHERE id=old.fk_db_table_context_id),old.fk_db_table_type_id, (SELECT uuid FROM db_table_type WHERE id=old.fk_db_table_type_id));
END;

CREATE TRIGGER TRIG_db_table_type_log_INSERT AFTER INSERT
ON db_table_type
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO db_table_type_log (log_action, id,uuid,name,description) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.name,new.description);
   UPDATE db_table_type SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM db_table_type_log WHERE log_id=(SELECT MAX(log_id)+1 FROM db_table_type_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

CREATE TRIGGER TRIG_db_table_type_log_UPDATE AFTER UPDATE
ON db_table_type
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE db_table_type SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO db_table_type_log (log_action, id,uuid,name,description) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.name,new.description);
   DELETE FROM _newUUID;
END;

CREATE TRIGGER TRIG_db_table_type_log_DELETE AFTER DELETE
ON db_table_type
BEGIN
   INSERT INTO db_table_type_log (log_action, id,uuid,name,description) VALUES ('DELETE',old.id,old.uuid,old.name,old.description);
END;

CREATE TRIGGER TRIG_db_table_context_log_INSERT AFTER INSERT
ON db_table_context
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO db_table_context_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,prefix) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.prefix);
   UPDATE db_table_context SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM db_table_context_log WHERE log_id=(SELECT MAX(log_id)+1 FROM db_table_context_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

CREATE TRIGGER TRIG_db_table_context_log_UPDATE AFTER UPDATE
ON db_table_context
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE db_table_context SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO db_table_context_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,prefix) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.prefix);
   DELETE FROM _newUUID;
END;

CREATE TRIGGER TRIG_db_table_context_log_DELETE AFTER DELETE
ON db_table_context
BEGIN
   INSERT INTO db_table_context_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,prefix) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.prefix);
END;

CREATE TRIGGER TRIG_db_table_field_log_INSERT AFTER INSERT
ON db_table_field
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO db_table_field_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_db_table_id, fk_db_table_uuid,datatype,bulk_load_checksum) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_db_table_id, (SELECT uuid FROM db_table WHERE id=new.fk_db_table_id),new.datatype,new.bulk_load_checksum);
   UPDATE db_table_field SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM db_table_field_log WHERE log_id=(SELECT MAX(log_id)+1 FROM db_table_field_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

CREATE TRIGGER TRIG_db_table_field_log_UPDATE AFTER UPDATE
ON db_table_field
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE db_table_field SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO db_table_field_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_db_table_id, fk_db_table_uuid,datatype,bulk_load_checksum) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_db_table_id, (SELECT uuid FROM db_table WHERE id=new.fk_db_table_id),new.datatype,new.bulk_load_checksum);
   DELETE FROM _newUUID;
END;

CREATE TRIGGER TRIG_db_table_field_log_DELETE AFTER DELETE
ON db_table_field
BEGIN
   INSERT INTO db_table_field_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_db_table_id, fk_db_table_uuid,datatype,bulk_load_checksum) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.fk_db_table_id, (SELECT uuid FROM db_table WHERE id=old.fk_db_table_id),old.datatype,old.bulk_load_checksum);
END;

DROP TABLE db_table_5696fd4620d54a41a4e5ac7cea1cf464;
DROP TRIGGER TRIG_db_table_context_log_INSERT;
DROP TRIGGER TRIG_db_table_context_log_UPDATE;
DROP TRIGGER TRIG_db_table_context_log_DELETE;

ALTER TABLE db_table_context RENAME TO db_table_context_47bf83a7482145a3a168b837f5118317;

CREATE TABLE db_table_context (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER NOT NULL  DEFAULT 6 REFERENCES object_type (id),
fk_project_id INTEGER NOT NULL  DEFAULT NULL REFERENCES project (id),
name TEXT(250) DEFAULT NULL,
description TEXT(4000) DEFAULT NULL
, prefix TEXT(100) DEFAULT NULL)
;

INSERT INTO db_table_context
(
    id,uuid,fk_object_type_id,fk_project_id,name,description,prefix
)
SELECT * FROM db_table_context_47bf83a7482145a3a168b837f5118317;


CREATE TRIGGER TRIG_db_table_context_log_INSERT AFTER INSERT
ON db_table_context
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO db_table_context_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,prefix) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.prefix);
   UPDATE db_table_context SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM db_table_context_log WHERE log_id=(SELECT MAX(log_id)+1 FROM db_table_context_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

CREATE TRIGGER TRIG_db_table_context_log_UPDATE AFTER UPDATE
ON db_table_context
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE db_table_context SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO db_table_context_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,prefix) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.prefix);
   DELETE FROM _newUUID;
END;

CREATE TRIGGER TRIG_db_table_context_log_DELETE AFTER DELETE
ON db_table_context
BEGIN
   INSERT INTO db_table_context_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,prefix) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.prefix);
END;

DROP TABLE db_table_context_47bf83a7482145a3a168b837f5118317;

ALTER TABLE db_table_context_log RENAME TO db_table_context_log_b6bedeeba3ed4d9c87387029341ed323;

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
   description TEXT(4000)
, prefix TEXT(100) DEFAULT NULL)
;

INSERT INTO db_table_context_log
(
    log_id,log_datetime,log_action,id,uuid,fk_object_type_id,fk_object_type_uuid,fk_project_id,fk_project_uuid,name,description,prefix
)
SELECT * FROM db_table_context_log_b6bedeeba3ed4d9c87387029341ed323;


DROP TABLE db_table_context_log_b6bedeeba3ed4d9c87387029341ed323;
DROP TRIGGER TRIG_db_table_field_log_INSERT;
DROP TRIGGER TRIG_db_table_field_log_UPDATE;
DROP TRIGGER TRIG_db_table_field_log_DELETE;

ALTER TABLE db_table_field RENAME TO db_table_field_4bf0a23ebed84ebcb0becd7ad671725e;

CREATE TABLE db_table_field (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER NOT NULL  DEFAULT 5 REFERENCES object_type (id),
fk_project_id INTEGER NOT NULL  DEFAULT NULL REFERENCES project (id),
name TEXT(250) DEFAULT NULL,
description TEXT(4000) DEFAULT NULL,
fk_db_table_id INTEGER DEFAULT NULL REFERENCES db_table (id),
datatype TEXT(250) DEFAULT NULL
, bulk_load_checksum TEXT(200) DEFAULT NULL)
;

INSERT INTO db_table_field
(
    id,uuid,fk_object_type_id,fk_project_id,name,description,fk_db_table_id,datatype,bulk_load_checksum
)
SELECT * FROM db_table_field_4bf0a23ebed84ebcb0becd7ad671725e;


CREATE TRIGGER TRIG_db_table_field_log_INSERT AFTER INSERT
ON db_table_field
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO db_table_field_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_db_table_id, fk_db_table_uuid,datatype,bulk_load_checksum) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_db_table_id, (SELECT uuid FROM db_table WHERE id=new.fk_db_table_id),new.datatype,new.bulk_load_checksum);
   UPDATE db_table_field SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM db_table_field_log WHERE log_id=(SELECT MAX(log_id)+1 FROM db_table_field_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

CREATE TRIGGER TRIG_db_table_field_log_UPDATE AFTER UPDATE
ON db_table_field
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE db_table_field SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO db_table_field_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_db_table_id, fk_db_table_uuid,datatype,bulk_load_checksum) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.fk_db_table_id, (SELECT uuid FROM db_table WHERE id=new.fk_db_table_id),new.datatype,new.bulk_load_checksum);
   DELETE FROM _newUUID;
END;

CREATE TRIGGER TRIG_db_table_field_log_DELETE AFTER DELETE
ON db_table_field
BEGIN
   INSERT INTO db_table_field_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,fk_db_table_id, fk_db_table_uuid,datatype,bulk_load_checksum) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.fk_db_table_id, (SELECT uuid FROM db_table WHERE id=old.fk_db_table_id),old.datatype,old.bulk_load_checksum);
END;

DROP TABLE db_table_field_4bf0a23ebed84ebcb0becd7ad671725e;

ALTER TABLE db_table_field_log RENAME TO db_table_field_log_d1fae428a49b4213ba34abbebe84a029;

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
   description TEXT(4000),
   fk_db_table_id INTEGER,
   fk_db_table_uuid TEXT,
   datatype TEXT(250)
, bulk_load_checksum TEXT(200) DEFAULT NULL)
;

INSERT INTO db_table_field_log
(
    log_id,log_datetime,log_action,id,uuid,fk_object_type_id,fk_object_type_uuid,fk_project_id,fk_project_uuid,name,description,fk_db_table_id,fk_db_table_uuid,datatype,bulk_load_checksum
)
SELECT * FROM db_table_field_log_d1fae428a49b4213ba34abbebe84a029;


DROP TABLE db_table_field_log_d1fae428a49b4213ba34abbebe84a029;

ALTER TABLE db_table_log RENAME TO db_table_log_95058e848f9c488fa6e2583088ffef3d;

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
   fk_db_table_type_uuid TEXT
)
;

INSERT INTO db_table_log
(
    log_id,log_datetime,log_action,id,uuid,fk_object_type_id,fk_object_type_uuid,fk_project_id,fk_project_uuid,name,description,location,fk_db_table_context_id,fk_db_table_context_uuid,fk_db_table_type_id,fk_db_table_type_uuid
)
SELECT * FROM db_table_log_95058e848f9c488fa6e2583088ffef3d;


DROP TABLE db_table_log_95058e848f9c488fa6e2583088ffef3d;
DROP TRIGGER TRIG_project_log_INSERT;
DROP TRIGGER TRIG_project_log_UPDATE;
DROP TRIGGER TRIG_project_log_DELETE;

ALTER TABLE project RENAME TO project_658536f8818744ffb8906bc9b5d7cbdd;

CREATE TABLE project (
id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_client_id INTEGER NOT NULL  DEFAULT NULL REFERENCES client (id),
name TEXT(250) DEFAULT NULL,
description TEXT(4000) DEFAULT NULL
)
;

INSERT INTO project
(
    id,uuid,fk_client_id,name,description
)
SELECT * FROM project_658536f8818744ffb8906bc9b5d7cbdd;


CREATE TRIGGER TRIG_project_log_INSERT AFTER INSERT
ON project
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO project_log (log_action, id,uuid,fk_client_id, fk_client_uuid,name,description) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_client_id, (SELECT uuid FROM client WHERE id=new.fk_client_id),new.name,new.description);
   UPDATE project SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM project_log WHERE log_id=(SELECT MAX(log_id)+1 FROM project_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

CREATE TRIGGER TRIG_project_log_UPDATE AFTER UPDATE
ON project
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE project SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO project_log (log_action, id,uuid,fk_client_id, fk_client_uuid,name,description) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_client_id, (SELECT uuid FROM client WHERE id=new.fk_client_id),new.name,new.description);
   DELETE FROM _newUUID;
END;

CREATE TRIGGER TRIG_project_log_DELETE AFTER DELETE
ON project
BEGIN
   INSERT INTO project_log (log_action, id,uuid,fk_client_id, fk_client_uuid,name,description) VALUES ('DELETE',old.id,old.uuid,old.fk_client_id, (SELECT uuid FROM client WHERE id=old.fk_client_id),old.name,old.description);
END;

DROP TABLE project_658536f8818744ffb8906bc9b5d7cbdd;

ALTER TABLE project_log RENAME TO project_log_6998b608cab7427bb6da596bf4652f00;

CREATE TABLE project_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   fk_client_id INTEGER,
   fk_client_uuid TEXT,
   name TEXT(250),
   description TEXT(4000)
)
;

INSERT INTO project_log
(
    log_id,log_datetime,log_action,id,uuid,fk_client_id,fk_client_uuid,name,description
)
SELECT * FROM project_log_6998b608cab7427bb6da596bf4652f00;


DROP TABLE project_log_6998b608cab7427bb6da596bf4652f00;
DROP TRIGGER TRIG_tool_type_log_INSERT;
DROP TRIGGER TRIG_tool_type_log_UPDATE;
DROP TRIGGER TRIG_tool_type_log_DELETE;

ALTER TABLE tool_type RENAME TO tool_type_1c3e348476814bc89d6f294bec2b3945;

CREATE TABLE tool_type (
id INTEGER DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
name TEXT(250) DEFAULT NULL,
description TEXT(4000) DEFAULT NULL
)
;

INSERT INTO tool_type
(
    id,uuid,name,description
)
SELECT * FROM tool_type_1c3e348476814bc89d6f294bec2b3945;


CREATE TRIGGER TRIG_tool_type_log_INSERT AFTER INSERT
ON tool_type
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO tool_type_log (log_action, id,uuid,name,description) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.name,new.description);
   UPDATE tool_type SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM tool_type_log WHERE log_id=(SELECT MAX(log_id)+1 FROM tool_type_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

CREATE TRIGGER TRIG_tool_type_log_UPDATE AFTER UPDATE
ON tool_type
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE tool_type SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO tool_type_log (log_action, id,uuid,name,description) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.name,new.description);
   DELETE FROM _newUUID;
END;

CREATE TRIGGER TRIG_tool_type_log_DELETE AFTER DELETE
ON tool_type
BEGIN
   INSERT INTO tool_type_log (log_action, id,uuid,name,description) VALUES ('DELETE',old.id,old.uuid,old.name,old.description);
END;

DROP TABLE tool_type_1c3e348476814bc89d6f294bec2b3945;

ALTER TABLE tool_type_log RENAME TO tool_type_log_f47b6e738cf44287991e7e249d5af6fa;

CREATE TABLE tool_type_log (
   log_id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
   log_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   log_action TEXT,
   id INTEGER,
   uuid TEXT,
   name TEXT(250),
   description TEXT(4000)
)
;

INSERT INTO tool_type_log
(
    log_id,log_datetime,log_action,id,uuid,name,description
)
SELECT * FROM tool_type_log_f47b6e738cf44287991e7e249d5af6fa;

DROP TABLE tool_type_log_f47b6e738cf44287991e7e249d5af6fa;
