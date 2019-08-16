#!/usr/bin/python
# -*- coding: utf-8 -*-

# Eclipse-Workspace: C:\Users\patrick_schmitz\workspace\sqlite_rename_column
# 2016-09-29 - 10:42

import sqlite3
import uuid

databasefile = "D:\\Entwicklung\\_Privat\\meta_grid\\DWH_Meta_wrkCpy_DEV\\dwh_meta_v2\\dwh_meta.sqlite"
table = "map_object_2_object_log"
old_column_def = "log_action TEXT"
new_column_def = "log_action TEXT"

# Set connection for database
connection = sqlite3.connect(databasefile)

basesqltable = """
    SELECT name,type, sql FROM sqlite_master
    WHERE type='table' AND name IN ('[[[table]]]')
    ORDER BY name;
"""

basesqltrigger = """
    SELECT name,type, sql FROM sqlite_master
    WHERE 
     type='trigger' AND 
    sql LIKE '%ON [[[table]]]%';
"""

basesqlinsert = """
INSERT INTO [[[table]]]
(
    [[[insertfields]]]
)
SELECT * FROM [[[temptablename]]];
"""

# basesqltable = """
#     SELECT name,type, sql FROM sqlite_master
#     WHERE type='table' AND name IN ('[[[table]]]', '[[[table]]]_log')
#     ORDER BY name;
# """

def generate_change_script(table, old_column_def, new_column_def):
    
    global connection
    global basesqltable
    global basesqltrigger
    global basesqlinsert

    cursor = connection.cursor()
    
    insertfields = ""

    # DROP TRIGGER Script erstellen
    sql = basesqltrigger.replace("[[[table]]]", table)
    cursor.execute(sql)
    result = cursor.fetchall()
    for r in result:
        print "DROP TRIGGER " + r[0] + ";"
    
    print ""
    
    sql = basesqltable.replace("[[[table]]]", table)

    guid = uuid.uuid4()

    temptablename = ("[[[table]]]_" + str(guid)).replace("-","").replace("[[[table]]]", table)
    # temptablelogname = "[[[table]]]_log_" + str(guid).replace("[[[table]]]", table)

    # TABELLEN RENAME Script erstellen
    renamesql = "ALTER TABLE [[[table]]] RENAME TO " + temptablename + ";\n"
    renamesql = renamesql.replace("[[[table]]]", table)

    print renamesql
    
    # CREATE TABLE Script erstellen
    cursor.execute(sql)
    result = cursor.fetchall() 
    for r in result:
        for l in r[2].split("\n"):
#             print l
    #         print l.find(old_column_name)
            if (l.find("CREATE TABLE")<0):
                if (l.find(")")!=0):
                    insertfields += l.strip().split(" ")[0]
                    if (l.find(",")>0):
                        insertfields += ","
            if (l.find(old_column_def)>=0):
#                 print "-- Old line: "
#                 print "-- " + l
#                 print "-- New line: " 
                print l.replace(old_column_def, new_column_def)
            else:
                print l
        print ";"

    # INSERT Scripte erstellen
    sql = basesqlinsert.replace("[[[table]]]", table)
    sql = sql.replace("[[[temptablename]]]", temptablename)
    sql = sql.replace("[[[insertfields]]]", insertfields)
    print sql + "\n"
    
    # CREATE TRIGGER Script erstellen
    sql = basesqltrigger.replace("[[[table]]]", table)
    cursor.execute(sql)
    result = cursor.fetchall()
    for r in result:
        print r[2] + ";\n"

    print "DROP TABLE " + temptablename + ";"

generate_change_script(table, old_column_def, new_column_def)
# generate_change_script(table + "_log", old_column_def, new_column_def)