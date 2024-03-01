CREATE OR REPLACE FUNCTION public.trig_func_log_upd_uuid()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
BEGIN
    IF NEW <> OLD THEN
        PERFORM SET_CONFIG('FLAGS.'||TG_TABLE_NAME, 'DO_UPDATE', FALSE);
        NEW.UUID := (MD5(RANDOM()::TEXT || CLOCK_TIMESTAMP()::TEXT)::UUID);
    END IF;
    RETURN NEW;
END $function$
;
