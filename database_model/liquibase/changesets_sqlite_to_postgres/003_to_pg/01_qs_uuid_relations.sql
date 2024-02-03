CREATE OR REPLACE FUNCTION qs_uuid_relations (
  tbl_to_examine varchar,
  point_in_time varchar
) RETURNS VOID
AS
$$
    DECLARE 
        tbl_name_for_persisting TEXT;
        tbl_to_examine ALIAS FOR $1;
        point_in_time ALIAS FOR $2;
        tbl_log TEXT;
        l_sql TEXT;
BEGIN
    tbl_name_for_persisting := 'qs_uuid_relations_before_and_after';
--    tbl_to_examine := 'db_table';
--    point_in_time := 'after';
    tbl_log := tbl_to_examine || '_log';
    EXECUTE format(
    '
    CREATE TABLE IF NOT EXISTS %s (
        src text NULL,
        point_in_time TEXT NULL,
        sum_l_cnt_uuid numeric NULL,
        sum_log_table_item_found int8 NULL,
        dt TIMESTAMP DEFAULT now()
    );
    '
    ,quote_ident(tbl_name_for_persisting)
    ) USING 'dummy_USING';

    IF EXISTS(SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = tbl_log::text) THEN
        l_sql := 
          format(
            '
            WITH _log_table AS (
                SELECT
                    "uuid"::TEXT AS "uuid",
                    COUNT("uuid") AS cnt_uuid
                FROM
                    %s_log
                GROUP BY 
                    "uuid"
            )
            ,
            _main_table AS (
                SELECT
                    "uuid"::TEXT AS "uuid"
                FROM
                    %s
            )
            ,_joined AS (
                SELECT l."uuid" AS l_uuid, l.cnt_uuid AS l_cnt_uuid, m."uuid" AS m_uuid, CASE WHEN  m."uuid" = l."uuid" THEN 1 ELSE 0 END AS log_table_item_found
                FROM _log_table l
                LEFT JOIN _main_table m ON m."uuid" = l."uuid" 
            )
            INSERT INTO %s (src, point_in_time, sum_l_cnt_uuid, sum_log_table_item_found)
            SELECT ''%s'' AS src, ''%s'' AS point_in_time, SUM(l_cnt_uuid) AS sum_l_cnt_uuid, SUM(log_table_item_found) AS sum_log_table_item_found FROM _joined', quote_ident(tbl_to_examine), quote_ident(tbl_to_examine), quote_ident(tbl_name_for_persisting), quote_ident(tbl_to_examine), quote_ident(point_in_time));
        RAISE NOTICE 'execute sql: %', l_sql;
        EXECUTE l_sql;
    END IF;
end;
$$
LANGUAGE 'plpgsql'
;