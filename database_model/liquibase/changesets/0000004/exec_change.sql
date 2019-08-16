DROP TRIGGER TRIG_keyfigure_log_INSERT;
DROP TRIGGER TRIG_keyfigure_log_UPDATE;
DROP TRIGGER TRIG_keyfigure_log_DELETE;

ALTER TABLE keyfigure RENAME TO keyfigure_dd9131adbd0d45728c69e14a8b3b97c0;

CREATE TABLE keyfigure (
id INTEGER NOT NULL  DEFAULT NULL PRIMARY KEY AUTOINCREMENT,
uuid TEXT DEFAULT NULL,
fk_object_type_id INTEGER NOT NULL  DEFAULT 1 REFERENCES object_type (id),
fk_project_id INTEGER DEFAULT NULL REFERENCES project (id),
name TEXT(250) DEFAULT NULL,
description TEXT(4000) DEFAULT NULL,
formula TEXT(4000) DEFAULT NULL
, aggregation TEXT(500), character TEXT(500), type TEXT(500), unit TEXT(500), value_range TEXT(500), cumulation_possible BOOLEAN)
;

INSERT INTO keyfigure
(
	id,uuid,fk_object_type_id,fk_project_id,name,description,formula,aggregation,character,type,unit,value_range,cumulation_possible
)
SELECT * FROM keyfigure_dd9131adbd0d45728c69e14a8b3b97c0;


CREATE TRIGGER TRIG_keyfigure_log_INSERT AFTER INSERT
ON keyfigure
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   INSERT INTO keyfigure_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,formula,aggregation,character,type,unit,value_range,cumulation_possible) VALUES ('INSERT',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.formula,new.aggregation,new.character,new.type,new.unit,new.value_range,new.cumulation_possible);
   UPDATE keyfigure SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   DELETE FROM _newUUID;
   DELETE FROM keyfigure_log WHERE log_id=(SELECT MAX(log_id)+1 FROM keyfigure_log WHERE log_action='INSERT' AND id=new.id) AND log_action='UPDATE' AND id=new.id; --Aufraeumen des ungewollten Datensatz beim INSERT (erzeugt durch den UPDATE TRIGGER)
END;

CREATE TRIGGER TRIG_keyfigure_log_UPDATE AFTER UPDATE
ON keyfigure
BEGIN
   INSERT INTO _newUUID (uuid) VALUES (hex(randomblob(16)));
   UPDATE keyfigure SET uuid=(SELECT uuid FROM _newUUID) WHERE id=new.id;
   INSERT INTO keyfigure_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,formula,aggregation,character,type,unit,value_range,cumulation_possible) VALUES ('UPDATE',new.id,(SELECT uuid FROM _newUUID),new.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=new.fk_object_type_id),new.fk_project_id, (SELECT uuid FROM project WHERE id=new.fk_project_id),new.name,new.description,new.formula,new.aggregation,new.character,new.type,new.unit,new.value_range,new.cumulation_possible);
   DELETE FROM _newUUID;
END;

CREATE TRIGGER TRIG_keyfigure_log_DELETE AFTER DELETE
ON keyfigure
BEGIN
   INSERT INTO keyfigure_log (log_action, id,uuid,fk_object_type_id, fk_object_type_uuid,fk_project_id, fk_project_uuid,name,description,formula,aggregation,character,type,unit,value_range,cumulation_possible) VALUES ('DELETE',old.id,old.uuid,old.fk_object_type_id, (SELECT uuid FROM object_type WHERE id=old.fk_object_type_id),old.fk_project_id, (SELECT uuid FROM project WHERE id=old.fk_project_id),old.name,old.description,old.formula,old.aggregation,old.character,old.type,old.unit,old.value_range,old.cumulation_possible);
END;

DROP TABLE keyfigure_dd9131adbd0d45728c69e14a8b3b97c0;

ALTER TABLE keyfigure_log RENAME TO keyfigure_log_e73b3533056a49f5b4113c795463f89e;

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
   formula TEXT(4000)
, aggregation TEXT(500), character TEXT(500), type TEXT(500), unit TEXT(500), value_range TEXT(500), cumulation_possible BOOLEAN)
;

INSERT INTO keyfigure_log
(
	log_id,log_datetime,log_action,id,uuid,fk_object_type_id,fk_object_type_uuid,fk_project_id,fk_project_uuid,name,description,formula,aggregation,character,type,unit,value_range,cumulation_possible
)
SELECT * FROM keyfigure_log_e73b3533056a49f5b4113c795463f89e;


DROP TABLE keyfigure_log_e73b3533056a49f5b4113c795463f89e;
