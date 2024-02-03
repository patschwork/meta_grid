DROP TRIGGER IF EXISTS TRIG_cleanup_queue_process_INSERT ON cleanup_queue;
CREATE TRIGGER TRIG_cleanup_queue_process_INSERT
  AFTER INSERT
  ON cleanup_queue
  FOR EACH ROW
  EXECUTE PROCEDURE TRIG_cleanup_queue_process_INSERT_func();