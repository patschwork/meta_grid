DROP VIEW IF EXISTS v_prepared_sql_for_log_trigger;

CREATE VIEW v_prepared_sql_for_log_trigger AS 
WITH 
-- Alle potentiellen Tabellen (mit einer Log-Tabelle)
tables_with_log_tables AS
(
    SELECT
        T_log.TABLE_NAME AS LOG_TABLE_NAME,
        T.TABLE_NAME AS TABLE_NAME,
        T_log.TABLE_SCHEMA AS LOG_TABLE_SCHEMA,
        T.TABLE_SCHEMA AS TABLE_SCHEMA,
        CASE
            WHEN T.TABLE_NAME IS NOT NULL THEN 1
            ELSE 0
        END AS has_log_table
    FROM
        INFORMATION_SCHEMA.TABLES T_log
    LEFT JOIN INFORMATION_SCHEMA.TABLES T ON 1=1
       AND CONCAT(T.TABLE_NAME, '_log') = T_log.TABLE_NAME
       AND T.TABLE_SCHEMA = T_log.TABLE_SCHEMA
       AND T.TABLE_TYPE = 'BASE TABLE'
    WHERE 1=1
       AND T_log.TABLE_NAME LIKE '%\_log'
       AND T_log.TABLE_TYPE = 'BASE TABLE'
    ORDER BY
      T_log.TABLE_NAME
)
-- Alle Tabellennamen (auch in Quotes), Feldnamen (auch in Quotes), ob es eine FK-Tabelle mit Alias ist (bla_id_as_blubb) und die Spalten-Position (das wird für den JOIN-Alias später verwendet)
,fields AS
(
    SELECT
        C.TABLE_SCHEMA,
        C.TABLE_NAME,
        CONCAT('"', C.TABLE_NAME, '"') AS TABLE_NAME_WITH_QUOTES,
        C.COLUMN_NAME,
        CONCAT('"', C.COLUMN_NAME, '"') AS COLUMN_NAME_WITH_QUOTES,
        CASE 
            WHEN C.COLUMN_NAME LIKE 'fk\_%\_id' THEN SPLIT_PART(SPLIT_PART(C.COLUMN_NAME,'fk_',2),'_id',1) 
            WHEN C.COLUMN_NAME LIKE 'fk\_%\_id\_as\_%' THEN SPLIT_PART(SPLIT_PART(C.COLUMN_NAME,'fk_',2),'_id_as_',1)
            ELSE '' 
        END FK_TABLE_NAME,
        CASE 
            WHEN C.COLUMN_NAME LIKE 'fk\_%\_id' THEN CONCAT('"', SPLIT_PART(SPLIT_PART(C.COLUMN_NAME,'fk_',2),'_id',1), '"')
            WHEN C.COLUMN_NAME LIKE 'fk\_%\_id\_as\_%' THEN CONCAT('"', SPLIT_PART(SPLIT_PART(C.COLUMN_NAME,'fk_',2),'_id_as_',1), '"')
            ELSE '' 
        END FK_TABLE_NAME_WITH_QUOTES,
        CASE WHEN C.COLUMN_NAME LIKE 'fk\_%\_id\_as\_%' THEN 1 ELSE 0 END FK_WITH_ALIAS,
        C.ORDINAL_POSITION
    FROM
        INFORMATION_SCHEMA.COLUMNS C
    WHERE
        1 = 1
        AND C.TABLE_NAME IN (
        SELECT
            table_name
        FROM
            tables_with_log_tables)
    ORDER BY
        C.TABLE_SCHEMA,
        C.TABLE_NAME,
        CASE
            WHEN C.COLUMN_NAME = 'id' THEN 1
            WHEN C.COLUMN_NAME = 'uuid' THEN 2
            WHEN C.COLUMN_NAME LIKE 'fk\_%\_id' THEN 4
            ELSE 9
        END
)
-- Mit dieser CTE wird geprüft, ob es für den FK-Tabelle auch physikalisch eine Tabelle existiert.
-- Zudem wird ein JOIN_ALIAS_NAME generiert (um eine weitere CTE zu vermeiden)
,fields_with_fk_table_check AS
(
    SELECT 
        C.*, 
        twlt.LOG_TABLE_NAME, 
        CASE 
            WHEN twlt.LOG_TABLE_NAME IS NOT NULL THEN 1 
            ELSE 0 
        END AS FK_TABLE_NAME_HAS_LOG_TABLE,
        CASE
            WHEN FK_WITH_ALIAS=1 THEN CONCAT('"',FK_TABLE_NAME,'_',C.ORDINAL_POSITION::TEXT,'"')
            ELSE FK_TABLE_NAME_WITH_QUOTES
        END AS JOIN_ALIAS_NAME
    FROM fields C
    LEFT JOIN tables_with_log_tables twlt ON 1=1
       AND twlt.table_name = C.FK_TABLE_NAME
)
-- Hier werden Konstrukte vorbereitet, damit es später nur noch zusammengesetzt werden muss
,fields_with_left_join_and_uuid_field AS
(
    SELECT C.*,
    CASE
        WHEN FK_TABLE_NAME_HAS_LOG_TABLE=1 THEN CONCAT('LEFT JOIN ', FK_TABLE_NAME_WITH_QUOTES, ' ', JOIN_ALIAS_NAME, ' ON ', JOIN_ALIAS_NAME, '."id" = ', 'm', '.', COLUMN_NAME_WITH_QUOTES) ELSE NULL
    END AS LEFT_JOIN_FOR_FK_TABLE,
    CASE
        WHEN FK_TABLE_NAME_HAS_LOG_TABLE=1 AND FK_WITH_ALIAS=1  THEN CONCAT(JOIN_ALIAS_NAME, '."uuid" AS ', REPLACE(COLUMN_NAME_WITH_QUOTES, '_id_as_', '_uuid_as_'))
        WHEN FK_TABLE_NAME_HAS_LOG_TABLE=1 AND FK_WITH_ALIAS<>1 THEN CONCAT(JOIN_ALIAS_NAME, '."uuid" AS ', REPLACE(COLUMN_NAME_WITH_QUOTES, '_id', '_uuid'))
        ELSE NULL
    END AS UUID_FIELD_FOR_FK_TABLE,
    CASE
        WHEN FK_TABLE_NAME_HAS_LOG_TABLE=1 AND FK_WITH_ALIAS=1  THEN CONCAT(REPLACE(COLUMN_NAME_WITH_QUOTES, '_id_as_', '_uuid_as_'))
        WHEN FK_TABLE_NAME_HAS_LOG_TABLE=1 AND FK_WITH_ALIAS<>1 THEN CONCAT(REPLACE(COLUMN_NAME_WITH_QUOTES, '_id', '_uuid'))
        ELSE NULL
    END AS UUID_FIELD_FOR_FK_TABLE_FOR_INSERT
    FROM fields_with_fk_table_check C
)
-- Alle Bestandteile als einzelne Felder vorbereiten
,sql_prepare_01 AS
(
    SELECT
        TABLE_NAME,
        CONCAT('INSERT INTO ', '"', TABLE_NAME, '_log"', chr(13)||chr(10)) AS INSERT_INTO,
        STRING_AGG(CONCAT(COLUMN_NAME_WITH_QUOTES),', ') AS INSERT_FIELDS_1,
        ','||STRING_AGG(UUID_FIELD_FOR_FK_TABLE_FOR_INSERT,', ') AS INSERT_FIELDS_2,
        'log_action' AS INSERT_FIELDS_3,
        'SELECT' AS SQL01,
        STRING_AGG(CONCAT(chr(32)||'m.',COLUMN_NAME_WITH_QUOTES),', '||chr(13)||chr(10)) AS FIELDS_1,
        STRING_AGG(UUID_FIELD_FOR_FK_TABLE,', '||chr(13)||chr(10)) AS FIELDS_2,
        '''{{TG_OP}}'' AS log_action' AS FIELDS_3,
        'FROM' AS SQL02,
        '{{TABLENAME}}' AS TABLE_NAME_PLACEHOLDER,  ------------------- ohne Quotation:"NEW" wird nicht erkannt. Es muss NEW sein
        'm' SQL03,
        STRING_AGG(LEFT_JOIN_FOR_FK_TABLE,chr(13)||chr(10)) AS LEFT_JOINES
    FROM
        fields_with_left_join_and_uuid_field
    GROUP BY
        TABLE_SCHEMA,
        TABLE_NAME_WITH_QUOTES,
        TABLE_NAME
)
-- Je (Log-)Tabelle eine Zeile generieren
,sinlge_line_sql AS
(
    SELECT
        TABLE_NAME,
        CONCAT(
            INSERT_INTO, '(', INSERT_FIELDS_1, INSERT_FIELDS_2, ', ', INSERT_FIELDS_3, ')', chr(13), chr(10), 
            SQL01,chr(13),chr(10),FIELDS_1,CASE WHEN LEFT_JOINES IS NOT NULL THEN chr(13)||chr(10)||',' ELSE '' END,FIELDS_2,', ',chr(13),chr(10),' ',FIELDS_3,chr(13),chr(10),
            SQL02, ' ', TABLE_NAME_PLACEHOLDER, ' ', SQL03, chr(13),chr(10), 
            LEFT_JOINES
--            ,' LIMIT 0'  -- DEBUG
            ) 
        AS SQL_STATEMENT 
    FROM sql_prepare_01 
)
--SELECT TABLE_NAME, REPLACE(sql_statement, '{{TABLENAME}}', TABLE_NAME) FROM sinlge_line_sql 
SELECT * FROM sinlge_line_sql 
-- Alles Statements zusammen
--SELECT STRING_AGG(SQL_STATEMENT, ';'||chr(13)||chr(10)||chr(13)||chr(10)) FROM sinlge_line_sql
;