CREATE OR REPLACE FUNCTION tbl_alter_uuid_default (
  tbl_to_alter varchar
) RETURNS VOID
AS
$$
    DECLARE 
        tbl_to_alter ALIAS FOR $1;
BEGIN
    EXECUTE format(
    '
    ALTER TABLE %s ALTER COLUMN "uuid" SET DEFAULT (md5(random()::text || clock_timestamp()::text)::uuid);
    '
    ,quote_ident(tbl_to_alter)
    ) USING 'dummy_USING';
end;
$$
LANGUAGE 'plpgsql'
;