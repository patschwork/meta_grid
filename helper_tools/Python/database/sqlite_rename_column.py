#!/usr/bin/python
# -*- coding: utf-8 -*-

# Eclipse-Workspace: C:\Users\patrick_schmitz\workspace\sqlite_rename_column
# 2016-09-29 - 10:42

import sqlite3
import uuid

endOfTableDefinition = ");"

databasefile = "/Users/patrick/Temp/dwh_meta_demo.sqlite"
table = "db_table"
old_column_def = "description TEXT(500)"
new_column_def = "description TEXT(4000)"

# add_column_def = "fk_deleted_status_id INTEGER NOT NULL  DEFAULT NULL REFERENCES deleted_status (id)"
add_column_def = ""

# Set connection for database
connection = sqlite3.connect(databasefile)

basesqltable = """
    SELECT name,type, sql FROM sqlite_master
    WHERE type='table' AND name IN ('[[[table]]]')
    ORDER BY name;
"""

# trick with the \n in the LIKE condition... ;-)
basesqltrigger = """
    SELECT name,type, sql FROM sqlite_master
    WHERE 
     type='trigger' AND 
    sql LIKE '%ON [[[table]]]\n%';
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

def generate_change_script(table, old_column_def, new_column_def, add_column_def):
    
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
        print("DROP TRIGGER " + r[0] + ";")
    
    print("")
    
    sql = basesqltable.replace("[[[table]]]", table)

    guid = uuid.uuid4()

    temptablename = ("[[[table]]]_" + str(guid)).replace("-","").replace("[[[table]]]", table)
    # temptablelogname = "[[[table]]]_log_" + str(guid).replace("[[[table]]]", table)

    # TABELLEN RENAME Script erstellen
    renamesql = "ALTER TABLE [[[table]]] RENAME TO " + temptablename + ";\n"
    renamesql = renamesql.replace("[[[table]]]", table)

    print(renamesql)
    
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
            if (l.find(old_column_def)>=0) & (old_column_def!=""):
#                 print "-- Old line: "
#                 print "-- " + l
#                 print "-- New line: " 
                print(l.replace(old_column_def, new_column_def))
            if (l.find(endOfTableDefinition)>=0) & (add_column_def!=""):
                print(l.replace(endOfTableDefinition, ", " + add_column_def + "\n" + endOfTableDefinition))
            else:
                print(l)
        print(";")

    # INSERT Scripte erstellen
    sql = basesqlinsert.replace("[[[table]]]", table)
    sql = sql.replace("[[[temptablename]]]", temptablename)
    sql = sql.replace("[[[insertfields]]]", insertfields)
    print(sql + "\n")
    
    # CREATE TRIGGER Script erstellen
    sql = basesqltrigger.replace("[[[table]]]", table)
    cursor.execute(sql)
    result = cursor.fetchall()
    for r in result:
        print(r[2] + ";\n")

    print("DROP TABLE " + temptablename + ";")

generate_change_script(table, old_column_def, new_column_def, add_column_def)
# generate_change_script(table + "_log", old_column_def, new_column_def, add_column_def)