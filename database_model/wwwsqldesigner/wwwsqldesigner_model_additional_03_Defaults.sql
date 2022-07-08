-- object_type
INSERT INTO object_type (id,name) VALUES (1,'keyfigure');
INSERT INTO object_type (id,name) VALUES (2,'sourcesystem');
INSERT INTO object_type (id,name) VALUES (3,'data_delivery_object');
INSERT INTO object_type (id,name) VALUES (4,'db_table');
INSERT INTO object_type (id,name) VALUES (5,'db_table_field');
INSERT INTO object_type (id,name) VALUES (6,'db_table_context');
INSERT INTO object_type (id,name) VALUES (7,'parameter');
INSERT INTO object_type (id,name) VALUES (8,'scheduling');
INSERT INTO object_type (id,name) VALUES (9,'attribute');
INSERT INTO object_type (id,name) VALUES (10,'glossary');
INSERT INTO object_type (id,name) VALUES (11,'db_database');
INSERT INTO object_type (id,name) VALUES (12,'comment');
INSERT INTO object_type (id,name) VALUES (13,'data_transfer_process');
INSERT INTO object_type (id,name) VALUES (14,'contact_group');
INSERT INTO object_type (id,name) VALUES (15,'contact');
INSERT INTO object_type (id,name) VALUES (16,'bracket');
INSERT INTO object_type (id,name) VALUES (17,'bracket_searchPattern');
INSERT INTO object_type (id,name) VALUES (18,'perspective_filter');
INSERT INTO object_type (id,name) VALUES (19,'mapping_qualifier');
-- 20-23 reservation for branch feature_interface_contract and tagging
INSERT INTO object_type (id,name) VALUES (20,'tag');
INSERT INTO object_type (id,name) VALUES (24,'url');
INSERT INTO object_type (id,name) VALUES (25,'deleted_status');
INSERT INTO object_type (id,name) VALUES (26,'object_persistence_method');
INSERT INTO object_type (id,name) VALUES (27,'datamanagement_process');

-- app_config
INSERT INTO app_config (key,valueINT,description) VALUES ('project_filter',2,'Set a filter to a specific project id (INT). Maybe more than one row/entry.');
INSERT INTO app_config ("key", valueINT, valueSTRING, description) VALUES('csv_files_meta_import', NULL, NULL, 'Set a path for importing meta data of csv flatfiles via Pentaho Kettle into table import_stage_db_table');
INSERT INTO app_config ("key", valueINT, valueSTRING, description) VALUES('web_app_header_bckcolor', NULL, NULL, 'Override background color for header');
INSERT INTO app_config ("key", valueINT, valueSTRING, description) VALUES('web_app_header_brandlabel_additional_text', NULL, NULL, 'Add a note as additional text after applicationtitle');

-- db_table_type
-- important for bulk import of database tables with Pentaho Data Integration (Kettle)
INSERT INTO db_table_type (name,description) VALUES ('TABLE', 'Table in a DBMS');
INSERT INTO db_table_type (name,description) VALUES ('VIEW', 'View in a DBMS');

-- mapping_qualifier
INSERT INTO mapping_qualifier (name,short_name,description,needs_object_depends_on) VALUES ('Database Foreign Key','FK','Database foreign key relashionship', 1);
INSERT INTO mapping_qualifier (name,short_name,description,needs_object_depends_on) VALUES ('Website Link','URL','Http or https Link to website (http://...)', 0);
INSERT INTO mapping_qualifier (name,short_name,description,needs_object_depends_on) VALUES ('File Reference','FILE','Link to a file to a disc or network-drive (file://...)', 0);

-- data_transfer_type
INSERT INTO data_transfer_type (name,description) VALUES ('Stored Procedure','PROCEDURES of a RDBMS to manipulate data (e.g. in a ETL workflow)');

-- deleted_status
INSERT INTO deleted_status (name,description) VALUES ('Deleted by user/frontend', 'Mark object as deleted in frontend');
INSERT INTO deleted_status (name,description) VALUES ('Deleted source', 'Object can not be found anymore in source');
