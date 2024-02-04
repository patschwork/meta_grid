#!/usr/bin/env python
# coding: utf-8



# # Imports

from sys import platform as _platform
import sqlite3
import pandas as pd
import os

dir_path = os.path.dirname(os.path.realpath(__file__))

# # Database connections
database_filepath = os.getenv("METAGRID_SQLITE_PATH", "---")
if database_filepath=='---':
    exit(-1)

output_file = os.path.join(dir_path, 'create_everything_for_all_views.sql')


# # Methods

def create_connection(db_file):
    """ create a database connection to the SQLite database
        specified by the db_file
    :param db_file: database file
    :return: Connection object or None
    """
    conn = None
    try:
        conn = sqlite3.connect(db_file)
    except Exception as e:
        print(e)

    return conn


# - Because of references, the views must be sorted
def get_all_objects(conn, type, tbl_name=None, return_df = True):
    cur = conn.cursor()
    sql = f"""
        SELECT * FROM sqlite_master
        WHERE type='{type}'
        {"" if tbl_name is None else f"AND tbl_name='{tbl_name}'"}
        ORDER BY
            CASE 
               WHEN tbl_name='v_tag_2_object_list' THEN 999
               WHEN sql LIKE '%v_All_Mappings_Union%' AND tbl_name<>'v_All_Mappings_Union' THEN 998
               WHEN sql LIKE '%v_All_Objects_Union%' AND tbl_name<>'v_All_Objects_Union' THEN 997
               ELSE 0 
            END
    """
    if not return_df:
        cur.execute(sql)
        rows = cur.fetchall()

        # for row in rows:
        #     print(row)
        return rows
    else:
        df = pd.read_sql(sql=sql, con=conn)
        return df

def get_all_tables_columns(conn, tablename, return_df = True):
    cur = conn.cursor()
    sql = f"""
        PRAGMA TABLE_INFO({tablename});
    """
    if not return_df:
        cur.execute(sql)
        rows = cur.fetchall()
        return rows
    else:
        df = pd.read_sql(sql=sql, con=conn)
        return df

def ddl(conn, sql_stmt):
    try:
        c = conn.cursor()
        c.execute(sql_stmt)
    except Exception as e:
        print(e)


# # Doings

conn = create_connection(database_filepath)
df=get_all_objects(conn=conn, type='view')

function_instr = """
CREATE OR REPLACE FUNCTION instr(fullstring text, searchstring TEXT) RETURNS integer AS $$
        BEGIN
            RETURN POSITION(searchstring IN fullstring);
        END;
$$ LANGUAGE plpgsql;
"""
# The `mode` will control whether to produce SQLs for PostgreSQL or SQLite. Minor differences must be repsrected...
mode = "postgres" # sqlite
#mode = "sqlite"


# - Replace IFNULL to COALESCE (IFNULL is a SQLite function). COALESCE can be used in both databases
# - Set view names in quotation marks (")
# - use CREATE OR REPLACE instead of CREATE
# - References of v_All_Objects_Union or v_tagsOptGroup must be quoted with "
#   - but not for the CTE cte_v_All_Objects_Union
# 

f = open(output_file, "w")

if mode == "postgres":
    f.write(f"{function_instr}\n\n")

for index, row in df.iterrows():
    view = row['tbl_name']
    sql = row['sql']
    if (view=='v_Project_Filter'):
        continue
    if True:
        new_sql = sql.replace("IFNULL", "COALESCE")
        if not 'CREATE VIEW "' in new_sql:
            create_view_stmt_old = new_sql.split(" ")[0] + " " + new_sql.split(" ")[1] + " " + new_sql.split(" ")[2]
            create_view_stmt_new = new_sql.split(" ")[0] + " " + new_sql.split(" ")[1] + ' "' + new_sql.split(' ')[2] + '"'
            new_sql = new_sql.replace(create_view_stmt_old, create_view_stmt_new)
        if mode == "postgres":
            new_sql = new_sql.replace('CREATE VIEW', 'CREATE OR REPLACE VIEW')
        else:
            new_sql = new_sql.replace('CREATE VIEW', f'DROP VIEW {view}; CREATE VIEW')
        if view!='v_All_Objects_Union':
            if not '"v_All_Objects_Union"' in new_sql:
                new_sql = new_sql.replace('v_All_Objects_Union', '"v_All_Objects_Union"')
            new_sql = new_sql.replace('cte_"v_All_Objects_Union"', 'cte_v_All_Objects_Union') # correct fauly replace
        if view!='v_tagsOptGroup':
            if not '"v_tagsOptGroup"' in new_sql:
                new_sql = new_sql.replace('v_tagsOptGroup', '"v_tagsOptGroup"')
        f.write(f"{new_sql}\n;\n\n")

f.close()