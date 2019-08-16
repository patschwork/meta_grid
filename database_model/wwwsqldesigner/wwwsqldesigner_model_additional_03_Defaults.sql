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

-- app_config
INSERT INTO app_config (key,valueINT,description) VALUES ('project_filter',2,'Set a filter to a specific project id (INT). Maybe more than one row/entry.');
INSERT INTO app_config ("key", valueINT, valueSTRING, description) VALUES('csv_files_meta_import', NULL, NULL, 'Set a path for importing meta data of csv flatfiles via Pentaho Kettle into table import_stage_db_table');
INSERT INTO app_config ("key", valueINT, valueSTRING, description) VALUES('web_app_header_bckcolor', NULL, NULL, 'Override background color for header');
INSERT INTO app_config ("key", valueINT, valueSTRING, description) VALUES('web_app_header_brandlabel_additional_text', NULL, NULL, 'Add a note as additional text after applicationtitle');

-- db_table_type
-- important for bulk import of database tables with Pentaho Data Integration (Kettle)
INSERT INTO db_table_type (name,description) VALUES ('TABLE', 'Table in a DBMS');
INSERT INTO db_table_type (name,description) VALUES ('TABLE', 'Table in a DBMS');

-- mapping_qualifier
INSERT INTO mapping_qualifier (name,short_name,description,needs_object_depends_on) VALUES ('Database Foreign Key','FK','Database foreign key relashionship', 1);
