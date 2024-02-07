#!/usr/bin/env python
# coding: utf-8

# # Warum?
# 
# - Dieses Notebook ist für die Vorarbeit zur Übernahme nach PostgreSQL mit pgloader erstellt worden
# - Es korrigiert Tabellenstrukturen in SQLite, sodass anschließend pgloader (mit wenigen Fehlern) ausgeführt werden kann
#   - Es gibt in SQLite einige Tabellen, welche einen einen doppelten Default werden haben -> `ERROR Database error 42601: multiple default values specified for column "id" of table "object_type"`
#   - Dieses Script korrigiert diese Fehler

# # Imports
from sys import platform as _platform
import sqlite3
import pandas as pd
import os

# # Database connections
database_filepath = os.getenv("METAGRID_SQLITE_PATH", "---")
if database_filepath=='---':
    exit(-1)

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


def get_all_objects(conn, type, tbl_name=None, return_df = True):
    cur = conn.cursor()
    sql = f"""
        SELECT * FROM sqlite_master
        WHERE type='{type}'
        {"" if tbl_name is None else f"AND tbl_name='{tbl_name}'"}
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
df=get_all_objects(conn=conn, type='table')

for index, row in df.iterrows():
    table = row['tbl_name']
    sql = row['sql']
    if not sql.startswith(f"CREATE TABLE {table} (\n"):
        sql = sql.replace(f"CREATE TABLE {table} (", f"CREATE TABLE {table} (\n")
    if not sql.endswith(";"):
        sql = f"{sql};"
    sql_with_correction = sql.replace("NOT NULL  DEFAULT NULL", "NOT NULL ").replace("DEFAULT NULL PRIMARY KEY", "NOT NULL PRIMARY KEY").replace("TEXT(", "VARCHAR(")
    sql_with_correction_copy = sql_with_correction.replace(" (\n", "_copy (\n")
    df_table_columns = get_all_tables_columns(conn=conn, tablename=table)
    argh = False
    for index_columns, row_columns in df_table_columns.iterrows():
        if (row_columns['notnull'] == 1 and row_columns['dflt_value'] == 'NULL') or (row_columns['notnull'] == 0 and row_columns['dflt_value'] == 'NULL' and row_columns['pk'] == 1) or (row_columns['type'].startswith("TEXT(")):
            # print(table)
            argh = True
            continue
    if not argh:
        # print(table) # they seems to be ok
        pass
    if argh:
        # print(table) # they seems to be not ok
        df_trigger = get_all_objects(conn=conn, type="trigger", tbl_name=table)
        df_index = get_all_objects(conn=conn, type="index", tbl_name=table)

        droptablecopy = f"\nDROP TABLE IF EXISTS {table}_copy;"
        ddl(conn=conn, sql_stmt=droptablecopy)
        # print(droptablecopy)
        
        # print(f"\n{sql_with_correction_copy}")
        ddl(conn=conn, sql_stmt=sql_with_correction_copy)
        
        insertintocopytable = f"\nINSERT INTO {table}_copy SELECT * FROM {table};"
        # print(insertintocopytable)
        ddl(conn=conn, sql_stmt=insertintocopytable)
        
        droporigtable = f"\nDROP TABLE {table};"
        # print(droporigtable)
        ddl(conn=conn, sql_stmt=droporigtable)
        
        # print(f"\n{sql_with_correction}")
        ddl(conn=conn, sql_stmt=sql_with_correction)

        insertbacktoorig = f"\nINSERT INTO {table} SELECT * FROM {table}_copy;"
        # print(insertbacktoorig)
        ddl(conn=conn, sql_stmt=insertbacktoorig)

        for index_trigger, row_trigger in df_trigger.iterrows():
            recreatetrigger = f"\n{row_trigger['sql']};"
            # print(f"\n{row_trigger['sql']};")
            ddl(conn=conn, sql_stmt=recreatetrigger)

        for index_trigger, row_index in df_index.iterrows():
            recreateindex = f"\n{row_index['sql']}"
            recreateindex.replace("None", "")
            # print(recreateindex)
            if recreateindex != "None":
                ddl(conn=conn, sql_stmt=recreateindex)
        dropcopytable = f"\nDROP TABLE IF EXISTS {table}_copy;"
        # print(dropcopytable)
        ddl(conn=conn, sql_stmt=dropcopytable)

conn.commit()
conn.close()