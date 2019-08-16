-- View für alle Objekte
	DROP VIEW v_All_Objects_Union;
	CREATE VIEW v_All_Objects_Union AS
		SELECT 
			 obj.id
			,obj.fk_object_type_id
			,obj.NAME
			,object_type.NAME AS object_type_name
			,obj.NAME || ' - ' || object_type.NAME AS listvalue_1
			,object_type.NAME || ' - ' || obj.NAME AS listvalue_2
			,CAST(obj.id AS varchar(10)) || ';' || CAST(obj.fk_object_type_id AS varchar(10)) AS listkey
			,NULL AS fk_client_id
			,obj.fk_project_id
		FROM sourcesystem obj
		LEFT JOIN object_type ON object_type.id = obj.fk_object_type_id
			UNION
		SELECT 
			 obj.id
			,obj.fk_object_type_id
			,obj.NAME
			,object_type.NAME AS object_type_name
			,obj.NAME || ' - ' || object_type.NAME AS listvalue_1
			,object_type.NAME || ' - ' || obj.NAME AS listvalue_2
			,CAST(obj.id AS varchar(10)) || ';' || CAST(obj.fk_object_type_id AS varchar(10)) AS listkey
			,NULL AS fk_client_id
			,obj.fk_project_id
		FROM glossary obj
		LEFT JOIN object_type ON object_type.id = obj.fk_object_type_id
			UNION
		SELECT 
			 obj.id
			,obj.fk_object_type_id
			,obj.COMMENT
			,object_type.NAME AS object_type_name
			,obj.COMMENT || ' - ' || object_type.NAME AS listvalue_1
			,object_type.NAME || ' - ' || obj.COMMENT AS listvalue_2
			,CAST(obj.id AS varchar(10)) || ';' || CAST(obj.fk_object_type_id AS varchar(10)) AS listkey
			,CASE
				WHEN sourcesystem.fk_project_id IS NOT NULL THEN sourcesystem.fk_project_id
				WHEN glossary.fk_project_id IS NOT NULL THEN glossary.fk_project_id
				WHEN data_delivery_object.fk_project_id IS NOT NULL THEN data_delivery_object.fk_project_id
				WHEN db_database.fk_project_id IS NOT NULL THEN db_database.fk_project_id
				WHEN db_table.fk_project_id IS NOT NULL THEN db_table.fk_project_id
				WHEN db_table_field.fk_project_id IS NOT NULL THEN db_table_field.fk_project_id
				WHEN scheduling.fk_project_id IS NOT NULL THEN scheduling.fk_project_id
				WHEN keyfigure.fk_project_id IS NOT NULL THEN keyfigure.fk_project_id
				WHEN db_table_context.fk_project_id IS NOT NULL THEN db_table_context.fk_project_id
				WHEN parameter.fk_project_id IS NOT NULL THEN parameter.fk_project_id
				WHEN attribute.fk_project_id IS NOT NULL THEN attribute.fk_project_id
				WHEN data_transfer_process.fk_project_id IS NOT NULL THEN data_transfer_process.fk_project_id
				ELSE NULL END AS fk_project_id
			,CASE
				WHEN contact_group.fk_client_id IS NOT NULL THEN contact_group.fk_client_id
				WHEN contact.fk_client_id IS NOT NULL THEN contact.fk_client_id
				ELSE NULL END AS fk_client_id
		FROM object_comment obj
		LEFT JOIN object_type ON object_type.id = obj.fk_object_type_id
		LEFT JOIN sourcesystem ON sourcesystem.fk_object_type_id=obj.ref_fk_object_type_id AND sourcesystem.id=obj.ref_fk_object_id
		LEFT JOIN glossary ON glossary.fk_object_type_id=obj.ref_fk_object_type_id AND glossary.id=obj.ref_fk_object_id
		LEFT JOIN data_delivery_object ON data_delivery_object.fk_object_type_id=obj.ref_fk_object_type_id AND data_delivery_object.id=obj.ref_fk_object_id
		LEFT JOIN db_database ON db_database.fk_object_type_id=obj.ref_fk_object_type_id AND db_database.id=obj.ref_fk_object_id
		LEFT JOIN db_table ON db_table.fk_object_type_id=obj.ref_fk_object_type_id AND db_table.id=obj.ref_fk_object_id
		LEFT JOIN db_table_field ON db_table_field.fk_object_type_id=obj.ref_fk_object_type_id AND db_table_field.id=obj.ref_fk_object_id
		LEFT JOIN scheduling ON scheduling.fk_object_type_id=obj.ref_fk_object_type_id AND scheduling.id=obj.ref_fk_object_id
		LEFT JOIN keyfigure ON keyfigure.fk_object_type_id=obj.ref_fk_object_type_id AND keyfigure.id=obj.ref_fk_object_id
		LEFT JOIN db_table_context ON db_table_context.fk_object_type_id=obj.ref_fk_object_type_id AND db_table_context.id=obj.ref_fk_object_id
		LEFT JOIN parameter ON parameter.fk_object_type_id=obj.ref_fk_object_type_id AND parameter.id=obj.ref_fk_object_id
		LEFT JOIN attribute ON attribute.fk_object_type_id=obj.ref_fk_object_type_id AND attribute.id=obj.ref_fk_object_id
		LEFT JOIN data_transfer_process ON data_transfer_process.fk_object_type_id=obj.ref_fk_object_type_id AND data_transfer_process.id=obj.ref_fk_object_id
		LEFT JOIN contact_group ON contact_group.fk_object_type_id=obj.ref_fk_object_type_id AND contact_group.id=obj.ref_fk_object_id
		LEFT JOIN contact ON contact.fk_object_type_id=obj.ref_fk_object_type_id AND contact.id=obj.ref_fk_object_id
			UNION
		SELECT 
			 obj.id
			,obj.fk_object_type_id
			,obj.name
			,object_type.NAME AS object_type_name
			,obj.name || ' - ' || object_type.NAME || ' ('|| data_delivery_type.name || ')' AS listvalue_1
			,object_type.NAME || ' ('|| data_delivery_type.name || ')' || ' - ' || obj.name AS listvalue_2
			,CAST(obj.id AS varchar(10)) || ';' || CAST(obj.fk_object_type_id AS varchar(10)) AS listkey
			,NULL AS fk_client_id
			,obj.fk_project_id
		FROM data_delivery_object obj
		LEFT JOIN object_type ON object_type.id = obj.fk_object_type_id
		LEFT JOIN data_delivery_type ON data_delivery_type.id=obj.fk_data_delivery_type_id
			UNION
		SELECT 
			 obj.id
			,obj.fk_object_type_id
			,obj.name
			,object_type.NAME AS object_type_name
			,obj.name || ' - ' || object_type.NAME AS listvalue_1
			,object_type.NAME || ' - ' || obj.name AS listvalue_2
			,CAST(obj.id AS varchar(10)) || ';' || CAST(obj.fk_object_type_id AS varchar(10)) AS listkey
			,NULL AS fk_client_id
			,obj.fk_project_id
		FROM db_database obj
		LEFT JOIN object_type ON object_type.id = obj.fk_object_type_id
			UNION
		SELECT 
			 obj.id
			,obj.fk_object_type_id
			,obj.name
			,object_type.NAME AS object_type_name
			,obj.name || ' - ' || object_type.NAME || ' ('|| db_table_type.name || '/' || IFNULL(db_table_context.name,'') || ')' AS listvalue_1
			,object_type.NAME || ' ('|| db_table_type.name || '/' || IFNULL(db_table_context.name,'') || ')' || ' - ' || obj.name AS listvalue_2
			,CAST(obj.id AS varchar(10)) || ';' || CAST(obj.fk_object_type_id AS varchar(10)) AS listkey
			,NULL AS fk_client_id
			,obj.fk_project_id
		FROM db_table obj
		LEFT JOIN object_type ON object_type.id = obj.fk_object_type_id
		LEFT JOIN db_table_type ON db_table_type.id=obj.fk_db_table_type_id
		LEFT JOIN db_table_context ON db_table_context.id=obj.fk_db_table_context_id
			UNION
		SELECT 
			 obj.id
			,obj.fk_object_type_id
			,obj.name
			,object_type.NAME AS object_type_name
			,obj.name || ' (db_table: ' || IFNULL(db_table.name,'-') || ')' || ' - ' || object_type.NAME AS listvalue_1
			,object_type.NAME || ' - ' || obj.name || ' (db_table: ' || IFNULL(db_table.name,'-') || ')' AS listvalue_2
			,CAST(obj.id AS varchar(10)) || ';' || CAST(obj.fk_object_type_id AS varchar(10)) AS listkey
			,NULL AS fk_client_id
			,obj.fk_project_id
		FROM db_table_field obj
		LEFT JOIN object_type ON object_type.id = obj.fk_object_type_id
		LEFT JOIN db_table ON db_table.id = obj.fk_db_table_id
			UNION
		SELECT 
			 obj.id
			,obj.fk_object_type_id
			,obj.name
			,object_type.NAME AS object_type_name
			,obj.name || ' - ' || object_type.NAME AS listvalue_1
			,object_type.NAME || ' - ' || obj.name AS listvalue_2
			,CAST(obj.id AS varchar(10)) || ';' || CAST(obj.fk_object_type_id AS varchar(10)) AS listkey
			,NULL AS fk_client_id
			,obj.fk_project_id
		FROM scheduling obj
		LEFT JOIN object_type ON object_type.id = obj.fk_object_type_id
			UNION
		SELECT 
			 obj.id
			,obj.fk_object_type_id
			,obj.name
			,object_type.NAME AS object_type_name
			,obj.name || ' - ' || object_type.NAME AS listvalue_1
			,object_type.NAME || ' - ' || obj.name AS listvalue_2
			,CAST(obj.id AS varchar(10)) || ';' || CAST(obj.fk_object_type_id AS varchar(10)) AS listkey
			,NULL AS fk_client_id
			,obj.fk_project_id
		FROM keyfigure obj
		LEFT JOIN object_type ON object_type.id = obj.fk_object_type_id
			UNION
		SELECT 
			 obj.id
			,obj.fk_object_type_id
			,obj.name
			,object_type.NAME AS object_type_name
			,obj.name || ' - ' || object_type.NAME AS listvalue_1
			,object_type.NAME || ' - ' || obj.name AS listvalue_2
			,CAST(obj.id AS varchar(10)) || ';' || CAST(obj.fk_object_type_id AS varchar(10)) AS listkey
			,NULL AS fk_client_id
			,obj.fk_project_id
		FROM db_table_context obj
		LEFT JOIN object_type ON object_type.id = obj.fk_object_type_id
			UNION
		SELECT 
			 obj.id
			,obj.fk_object_type_id
			,obj.name
			,object_type.NAME AS object_type_name
			,obj.name || ' - ' || object_type.NAME AS listvalue_1
			,object_type.NAME || ' - ' || obj.name AS listvalue_2
			,CAST(obj.id AS varchar(10)) || ';' || CAST(obj.fk_object_type_id AS varchar(10)) AS listkey
			,NULL AS fk_client_id
			,obj.fk_project_id
		FROM parameter obj
		LEFT JOIN object_type ON object_type.id = obj.fk_object_type_id
			UNION
		SELECT 
			 obj.id
			,obj.fk_object_type_id
			,obj.name
			,object_type.NAME AS object_type_name
			,obj.name || ' - ' || object_type.NAME AS listvalue_1
			,object_type.NAME || ' - ' || obj.name AS listvalue_2
			,CAST(obj.id AS varchar(10)) || ';' || CAST(obj.fk_object_type_id AS varchar(10)) AS listkey
			,NULL AS fk_client_id
			,obj.fk_project_id
		FROM attribute obj
		LEFT JOIN object_type ON object_type.id = obj.fk_object_type_id
			UNION
		SELECT 
			 obj.id
			,obj.fk_object_type_id
			,obj.name
			,object_type.NAME AS object_type_name
			,obj.name || ' - ' || object_type.NAME AS listvalue_1
			,object_type.NAME || ' - ' || obj.name AS listvalue_2
			,CAST(obj.id AS varchar(10)) || ';' || CAST(obj.fk_object_type_id AS varchar(10)) AS listkey
			,NULL AS fk_client_id
			,obj.fk_project_id
		FROM data_transfer_process obj
		LEFT JOIN object_type ON object_type.id = obj.fk_object_type_id
			UNION
		SELECT 
			 obj.id
			,obj.fk_object_type_id
			,obj.name
			,object_type.NAME AS object_type_name
			,obj.name || ' - ' || object_type.NAME AS listvalue_1
			,object_type.NAME || ' - ' || obj.name AS listvalue_2
			,CAST(obj.id AS varchar(10)) || ';' || CAST(obj.fk_object_type_id AS varchar(10)) AS listkey
			,obj.fk_client_id
			,NULL AS fk_project_id
		FROM contact_group obj
		LEFT JOIN object_type ON object_type.id = obj.fk_object_type_id
			UNION
		SELECT 
			 obj.id
			,obj.fk_object_type_id
			,obj.surname || ' ' || obj.givenname AS name
			,object_type.NAME AS object_type_name
			,obj.surname || ' ' || obj.givenname || ' - ' || object_type.NAME AS listvalue_1
			,object_type.NAME || ' - ' || obj.surname || ' ' || obj.givenname AS listvalue_2
			,CAST(obj.id AS varchar(10)) || ';' || CAST(obj.fk_object_type_id AS varchar(10)) AS listkey
			,obj.fk_client_id
			,NULL AS fk_project_id
		FROM contact obj
		LEFT JOIN object_type ON object_type.id = obj.fk_object_type_id
			UNION
		SELECT 
			 obj.id
			,obj.fk_object_type_id
			,obj.name AS name
			,object_type.NAME AS object_type_name
			,IFNULL(attribute.name,'') || ' - ' || object_type.NAME AS listvalue_1
			,object_type.NAME || ' - ' || IFNULL(attribute.name,'') AS listvalue_2
			,CAST(obj.id AS varchar(10)) || ';' || CAST(obj.fk_object_type_id AS varchar(10)) AS listkey
			,NULL AS fk_client_id
			,obj.fk_project_id AS fk_project_id
		FROM bracket obj
		LEFT JOIN object_type ON object_type.id = obj.fk_object_type_id
		LEFT JOIN attribute ON attribute.id = obj.fk_attribute_id 
			UNION
		SELECT 
			 obj.id
			,obj.fk_object_type_id
			,obj.searchPattern AS name
			,object_type.NAME AS object_type_name
			,IFNULL(bracket.name,'') || ' (' || obj.searchPattern || ')' || ' - ' || object_type.NAME AS listvalue_1
			,object_type.NAME || ' - ' || IFNULL(bracket.name,'') || ' (' || obj.searchPattern || ')' AS listvalue_2
			,CAST(obj.id AS varchar(10)) || ';' || CAST(obj.fk_object_type_id AS varchar(10)) AS listkey
			,NULL AS fk_client_id
			,bracket.fk_project_id AS fk_project_id
		FROM bracket_searchPattern obj
		LEFT JOIN object_type ON object_type.id = obj.fk_object_type_id
		LEFT JOIN bracket ON bracket.id = obj.fk_bracket_id
		ORDER BY fk_object_type_id;

-- View für alle Mappings
	DROP VIEW v_All_Mappings_Union;
	CREATE VIEW v_All_Mappings_Union AS 
	WITH cte_v_All_Objects_Union AS
	(
	  SELECT 
				 v_All_Objects_Union.listvalue_1
				,v_All_Objects_Union.listvalue_2
				,v_All_Objects_Union.name
				,v_All_Objects_Union.object_type_name
				,v_All_Objects_Union.listkey
				,v_All_Objects_Union.id
				,v_All_Objects_Union.fk_object_type_id
	  FROM v_All_Objects_Union
	)
	,
	cte_map_object_2_object AS
	(
		SELECT 			
			 ref_fk_object_id_1
			,ref_fk_object_type_id_1
			,ref_fk_object_id_2
			,ref_fk_object_type_id_2
			,ref_fk_object_id_1  
		FROM map_object_2_object
	)
	  SELECT  *
		FROM
		(
			SELECT 
				 '->' 							AS connectiondirection
				,v_All_Objects_Union.listvalue_1
				,v_All_Objects_Union.listvalue_2
				,v_All_Objects_Union.name
				,v_All_Objects_Union.object_type_name
				,v_All_Objects_Union.listkey
				,ref_fk_object_id_1
				,ref_fk_object_type_id_1
				,ref_fk_object_id_2
				,ref_fk_object_type_id_2
				,ref_fk_object_id_1 			AS filter_ref_fk_object_id
				,ref_fk_object_type_id_1 		AS filter_ref_fk_object_type_id
				,parent.ref_fk_object_id_parent
				,parent.ref_fk_object_type_id_parent
				,parentAttr.name				AS parentAttr_name
				,parentAttr.object_type_name	AS parentAttr_object_type_name
				,child.ref_fk_object_id_child	
				,child.ref_fk_object_type_id_child
				,childAttr.name 				AS childAttr_name
				,childAttr.object_type_name		AS childAttr_object_type_name
			FROM cte_map_object_2_object map_object_2_object
			LEFT JOIN cte_v_All_Objects_Union v_All_Objects_Union
				ON v_All_Objects_Union.id							=	map_object_2_object.ref_fk_object_id_2 
				AND v_All_Objects_Union.fk_object_type_id			=	map_object_2_object.ref_fk_object_type_id_2

			LEFT JOIN object_depends_on parent
				ON parent.ref_fk_object_id_parent					=	map_object_2_object.ref_fk_object_id_2 
				AND parent.ref_fk_object_type_id_parent				=	map_object_2_object.ref_fk_object_type_id_2
			LEFT JOIN cte_v_All_Objects_Union parentAttr
				ON parentAttr.id									=	parent.ref_fk_object_id_parent 
				AND parentAttr.fk_object_type_id					=	parent.ref_fk_object_type_id_parent

			LEFT JOIN object_depends_on child
				ON child.ref_fk_object_id_child						=	map_object_2_object.ref_fk_object_id_2 
				AND child.ref_fk_object_type_id_child				=	map_object_2_object.ref_fk_object_type_id_2
			LEFT JOIN cte_v_All_Objects_Union childAttr
				ON childAttr.id										=	child.ref_fk_object_id_parent 
				AND childAttr.fk_object_type_id						=	child.ref_fk_object_type_id_parent
		UNION
			SELECT 
				 '<-' 							AS connectiondirection
				,v_All_Objects_Union.listvalue_1
				,v_All_Objects_Union.listvalue_2
				,v_All_Objects_Union.name
				,v_All_Objects_Union.object_type_name
				,v_All_Objects_Union.listkey
				,ref_fk_object_id_1
				,ref_fk_object_type_id_1
				,ref_fk_object_id_2
				,ref_fk_object_type_id_2
				,ref_fk_object_id_2 			AS filter_ref_fk_object_id
				,ref_fk_object_type_id_2 		AS filter_ref_fk_object_type_id
				,parent.ref_fk_object_id_parent
				,parent.ref_fk_object_type_id_parent
				,parentAttr.name
				,parentAttr.object_type_name
				,child.ref_fk_object_id_child
				,child.ref_fk_object_type_id_child
				,childAttr.name 				AS childAttr_name
				,childAttr.object_type_name		AS childAttr_object_type_name
			FROM cte_map_object_2_object map_object_2_object
			LEFT JOIN cte_v_All_Objects_Union v_All_Objects_Union 
				ON v_All_Objects_Union.id							=	map_object_2_object.ref_fk_object_id_1 
				AND v_All_Objects_Union.fk_object_type_id			=	map_object_2_object.ref_fk_object_type_id_1

			LEFT JOIN object_depends_on parent
				ON parent.ref_fk_object_id_parent					=	map_object_2_object.ref_fk_object_id_1 
				AND parent.ref_fk_object_type_id_parent				=	map_object_2_object.ref_fk_object_type_id_1
			LEFT JOIN cte_v_All_Objects_Union parentAttr
				ON parentAttr.id									=	parent.ref_fk_object_id_parent 
				AND parentAttr.fk_object_type_id					=	parent.ref_fk_object_type_id_parent

			LEFT JOIN object_depends_on child
				ON child.ref_fk_object_id_child						=	map_object_2_object.ref_fk_object_id_1 
				AND child.ref_fk_object_type_id_child				=	map_object_2_object.ref_fk_object_type_id_1
			LEFT JOIN cte_v_All_Objects_Union childAttr
				ON childAttr.id										=	child.ref_fk_object_id_parent 
				AND childAttr.fk_object_type_id						=	child.ref_fk_object_type_id_parent
		) AS un;

-- View fuer einen Lookup zu allen verfuegbaren Datentypen
	DROP VIEW v_datatypes_Lookup;
	CREATE VIEW v_datatypes_Lookup AS 
	SELECT 
		DISTINCT
			datatype 
	FROM db_table_field dtf
	UNION
	SELECT * FROM
	(
		SELECT 'INTEGER' AS datatype
			UNION
		SELECT 'DATETIME' AS datatype
			UNION
		SELECT 'BOOLEAN' AS datatype
			UNION
		SELECT 'DATE' AS datatype
			UNION
		SELECT 'TIME' AS datatype
			UNION
		SELECT 'BIT' AS datatype
			UNION
		SELECT 'TIMESTAMP' AS datatype
			UNION
		SELECT 'NVARCHAR(255)' AS datatype
			UNION
		SELECT 'NVARCHAR(4000)' AS datatype
			UNION
		SELECT 'VARCHAR(8000)' AS datatype
			UNION
		SELECT 'VARCHAR(100)' AS datatype
			UNION
		SELECT 'VARCHAR(50)' AS datatype
			UNION
		SELECT 'VARCHAR(1)' AS datatype
			UNION
		SELECT 'DATETIME2' AS datatype
			UNION
		SELECT 'NUMERIC(18,2)' AS datatype
			UNION
		SELECT 'CURRENCY' AS datatype
	);

-- View fuer um eine Ansicht aller gefilterten Projekte zu liefern
	DROP VIEW v_Project_Filter;
	CREATE VIEW v_Project_Filter
	AS
	SELECT valueINT AS id FROM app_config WHERE key='project_filter'
	UNION
	SELECT id FROM project WHERE (SELECT COUNT(valueINT) FROM app_config WHERE key='project_filter')=0		
	;

-- View fuer um eine Liste der letzten Aenderungen aus den Log-Tabellen
	DROP VIEW v_LastChangesLog_List;
	CREATE VIEW v_LastChangesLog_List AS
	SELECT
		 log_datetime
		,log_action
		,name
		,tablename
		,id
	FROM
	(
		SELECT * FROM (SELECT log_id,log_datetime,log_action,id,uuid,name,'attribute_log' AS tablename FROM attribute_log ORDER BY log_id DESC LIMIT 1)
		UNION 
		SELECT * FROM (SELECT log_id,log_datetime,log_action,id,uuid,name,'client_log' AS tablename FROM client_log ORDER BY log_id DESC LIMIT 1)
		UNION 
		SELECT * FROM (SELECT log_id,log_datetime,log_action,id,uuid,name,'data_delivery_object_log' AS tablename FROM data_delivery_object_log ORDER BY log_id DESC LIMIT 1)
		UNION 
		SELECT * FROM (SELECT log_id,log_datetime,log_action,id,uuid,name,'data_delivery_type_log' AS tablename FROM data_delivery_type_log ORDER BY log_id DESC LIMIT 1)
		UNION 
		SELECT * FROM (SELECT log_id,log_datetime,log_action,id,uuid,name,'data_transfer_process_log' AS tablename FROM data_transfer_process_log ORDER BY log_id DESC LIMIT 1)
		UNION 
		SELECT * FROM (SELECT log_id,log_datetime,log_action,id,uuid,name,'data_transfer_type_log' AS tablename FROM data_transfer_type_log ORDER BY log_id DESC LIMIT 1)
		UNION 
		SELECT * FROM (SELECT log_id,log_datetime,log_action,id,uuid,name,'db_database_log' AS tablename FROM db_database_log ORDER BY log_id DESC LIMIT 1)
		UNION 
		SELECT * FROM (SELECT log_id,log_datetime,log_action,id,uuid,name,'db_table_context_log' AS tablename FROM db_table_context_log ORDER BY log_id DESC LIMIT 1)
		UNION 
		SELECT * FROM (SELECT log_id,log_datetime,log_action,id,uuid,name,'db_table_field_log' AS tablename FROM db_table_field_log ORDER BY log_id DESC LIMIT 1)
		UNION 
		SELECT * FROM (SELECT log_id,log_datetime,log_action,id,uuid,name,'db_table_log' AS tablename FROM db_table_log ORDER BY log_id DESC LIMIT 1)
		UNION 
		SELECT * FROM (SELECT log_id,log_datetime,log_action,id,uuid,name,'db_table_type_log' AS tablename FROM db_table_type_log ORDER BY log_id DESC LIMIT 1)
		UNION 
		SELECT * FROM (SELECT log_id,log_datetime,log_action,id,uuid,name,'glossary_log' AS tablename FROM glossary_log ORDER BY log_id DESC LIMIT 1)
		UNION 
		SELECT * FROM (SELECT log_id,log_datetime,log_action,id,uuid,name,'keyfigure_log' AS tablename FROM keyfigure_log ORDER BY log_id DESC LIMIT 1)
		UNION 
		SELECT * FROM (SELECT log_id,log_datetime,log_action,id,uuid,NULL AS name,'map_object_2_object_log' AS tablename FROM map_object_2_object_log ORDER BY log_id DESC LIMIT 1)
		UNION 
		SELECT * FROM (SELECT log_id,log_datetime,log_action,id,uuid,NULL AS name,'object_comment_log' AS tablename FROM object_comment_log ORDER BY log_id DESC LIMIT 1)
		UNION 
		SELECT * FROM (SELECT log_id,log_datetime,log_action,id,uuid,NULL AS name,'object_depends_on_log' AS tablename FROM object_depends_on_log ORDER BY log_id DESC LIMIT 1)
		UNION 
		SELECT * FROM (SELECT log_id,log_datetime,log_action,id,uuid,name,'object_type_log' AS tablename FROM object_type_log ORDER BY log_id DESC LIMIT 1)
		UNION 
		SELECT * FROM (SELECT log_id,log_datetime,log_action,id,uuid,name,'parameter_log' AS tablename FROM parameter_log ORDER BY log_id DESC LIMIT 1)
		UNION 
		SELECT * FROM (SELECT log_id,log_datetime,log_action,id,uuid,name,'project_log' AS tablename FROM project_log ORDER BY log_id DESC LIMIT 1)
		UNION 
		SELECT * FROM (SELECT log_id,log_datetime,log_action,id,uuid,name,'scheduling_log' AS tablename FROM scheduling_log ORDER BY log_id DESC LIMIT 1)
		UNION 
		SELECT * FROM (SELECT log_id,log_datetime,log_action,id,uuid,name,'sourcesystem_log' AS tablename FROM sourcesystem_log ORDER BY log_id DESC LIMIT 1)
		UNION 
		SELECT * FROM (SELECT log_id,log_datetime,log_action,id,uuid,tool_name AS name,'tool_log' AS tablename FROM tool_log ORDER BY log_id DESC LIMIT 1)
		UNION 
		SELECT * FROM (SELECT log_id,log_datetime,log_action,id,uuid,name,'tool_type_log' AS tablename FROM tool_type_log ORDER BY log_id DESC LIMIT 1)
		UNION 
		SELECT * FROM (SELECT log_id,log_datetime,log_action,id,uuid,name,'contact_group_log' AS tablename FROM contact_group_log ORDER BY log_id DESC LIMIT 1)
		UNION 
		SELECT * FROM (SELECT log_id,log_datetime,log_action,id,uuid,surname AS name,'contact_log' AS tablename FROM contact_log ORDER BY log_id DESC LIMIT 1)
		UNION 
		SELECT * FROM (SELECT log_id,log_datetime,log_action,id,uuid,name,'bracket_log' AS tablename FROM bracket_log ORDER BY log_id DESC LIMIT 1)
		UNION 
		SELECT * FROM (SELECT log_id,log_datetime,log_action,id,uuid,searchPattern AS name,'bracket_searchPattern_log' AS tablename FROM bracket_searchPattern_log ORDER BY log_id DESC LIMIT 1)
	) 
	ORDER BY log_datetime DESC

-- View fuer Bracket Definitionen	
	DROP VIEW v_Bracket_Definitions;
	CREATE VIEW v_Bracket_Definitions AS 
	SELECT 
		 BRA.*
		,DBF.db_table_name					AS db_table_name
		,DBF.db_table_field_name 			AS db_table_field_name
		,DBF.db_table_field_id				AS db_table_field_id
		,DBF.db_table_field_fk_object_type_id 	AS db_table_field_fk_object_type_id
		,ATT.name							AS attribute_name
		,ATT.id							AS attribute_id
		,ATT.fk_object_type_id				AS attribute_fk_object_type_id
	FROM 
	(
		SELECT 
			 BSP.searchPattern					AS bracket_searchPattern
			,B.name							AS bracket_name
			,B.description						AS bracket_description
			,B.fk_attribute_id					AS bracket_fk_attribute_id
			,B.fk_object_type_id_as_searchFilter 
			,B.fk_project_id					AS bracket_fk_project_id
			,BSP.fk_bracket_id
		FROM 
			bracket_searchPattern BSP
		LEFT JOIN bracket B ON
			B.id=BSP.fk_bracket_id
	) BRA
	LEFT JOIN
	(
		SELECT 
			 F.id							AS db_table_field_id
			,F.name							AS db_table_field_name
			,F.fk_object_type_id				AS db_table_field_fk_object_type_id
			,F.fk_project_id					AS db_table_fk_project_id
			,T.name							AS db_table_name
		FROM 
			db_table_field F
		LEFT JOIN
			db_table T ON
				T.id=F.fk_db_table_id
	) DBF ON
		DBF.db_table_field_name LIKE BRA.bracket_searchPattern
			AND
		DBF.db_table_field_fk_object_type_id=IFNULL(BRA.fk_object_type_id_as_searchFilter,DBF.db_table_field_fk_object_type_id)
			AND
		DBF.db_table_fk_project_id=BRA.bracket_fk_project_id
	LEFT JOIN attribute ATT ON
		ATT.id=BRA.bracket_fk_attribute_id
			AND
		ATT.fk_project_id=BRA.bracket_fk_project_id
	;