import os
import sys
import string
from sys import platform as _platform
from xml.dom import minidom
# from macpath import split, join

print('%s %s' % (sys.executable or sys.platform, sys.version))

# Config
wwwsqldesignermodelfile = "../../../database_model/wwwsqldesigner/wwwsqldesigner_model.xml"
output = "liquibase" # sql or liquibase
replaceIfExists = True
folderForYii2ModelClasses = "../../../frontend/yii/basic/models"
filterOnSpecificDbObject = "mapping_qualifier" # Table
# filterOnSpecificDbObject = "" # Table

isLinuxOrDarwin = False
if _platform == "linux" or _platform == "linux2":
    isLinuxOrDarwin = True
elif _platform == "darwin":
    isLinuxOrDarwin = True

if isLinuxOrDarwin == True:
    wwwsqldesignermodelfile = wwwsqldesignermodelfile.replace("\\","/")

if (os.path.isfile(wwwsqldesignermodelfile)):
    print("")
else:
    print("File not found")
    exit

def GetNewViewName(tablename):
    return "v_" + tablename + "_SearchInterface"

def getText(nodelist):
    rc = []
    for node in nodelist:
        if node.nodeType == node.TEXT_NODE:
            rc.append(node.data)
    return ''.join(rc)

# Taken from BaseInflector.php/id2camel
# E. g. my_table_name -> MyTableName
def yii2_id2camel(identificator, separator = '-'):
    t = identificator
    s = t.split(separator)
    s_uc = []
    for w in s:
        s_uc.append(string.capwords(w))
    t_uc = "".join(s_uc)
    return t_uc
#     return string.capwords(split(' ', join(separator, identificator).replace(" ", "")

xmldoc = minidom.parse(wwwsqldesignermodelfile)
itemlist = xmldoc.getElementsByTagName('table')

tablename = ""
fieldname = ""
datatype  = ""
leadingComma = ""

rowcounter = -1

print("Anzahl Tabellen: %s" % (len(itemlist)))
for s in itemlist:
    tablename = s.attributes['name'].value
    if (filterOnSpecificDbObject != ""):
        if (filterOnSpecificDbObject != tablename):
            continue
    newViewName = GetNewViewName(tablename)
    if tablename=="app_config":
        continue
    if output=="sql":
        if replaceIfExists == True:
            print("DROP VIEW %s;" % newViewName)
        print("CREATE VIEW %s AS" % newViewName)
    elif output=="liquibase":
        if replaceIfExists == True:
            liquibaseReplaceIfExists="true"
        else:
            liquibaseReplaceIfExists="false"
        print("<!-- Those views are automatically generated with a python helper tool: /helper_tools/Python/database/create_wwwsqldesigner_model_additional_search_views.py -->")
        print("<createView replaceIfExists=\"%s\" viewName=\"%s\">" % (liquibaseReplaceIfExists, newViewName))
        print("<![CDATA[")
    print("SELECT")
    rowcounter = 0
    rows=s.getElementsByTagName('row')
    countRows = len(rows)
    field__fk_project_id__found = False
    for row in rows:
        fieldname = row.attributes['name'].value
        datatypeElement=row.getElementsByTagName('datatype')[0]
        datatype = getText(datatypeElement.childNodes)
        startPosition = datatype.find("TEXT", 0, len(datatype))
        leadingComma = " "
        if rowcounter>0:
            leadingComma = ","
        if (fieldname == "fk_project_id"):
            field__fk_project_id__found = True
            print("\t%s%s" % (",","project.fk_client_id"))
            print("\t%s%s" % (",","IFNULL(project.name, '') AS project_name"))
            print("\t%s%s" % (",","IFNULL(client.name, '') AS client_name"))
        if startPosition>=0:
            print("\t%sIFNULL(%s.%s, '') AS %s" % (leadingComma,tablename,fieldname,fieldname))
        else:
            print("\t%s%s.%s" % (leadingComma,tablename,fieldname))
        rowcounter = rowcounter +1
        if countRows==rowcounter:
            if (tablename == "db_table" or tablename == "db_table_field"):
                print("\t%sCASE WHEN (LENGTH(db_table.location) - LENGTH(REPLACE(db_table.location, '\".\"', ''))) / LENGTH('\".\"')>=2 THEN REPLACE(SUBSTR(db_table.location ,1, INSTR(db_table.location,'.')-1),'\"','') ELSE '' END AS databaseInfoFromLocation" % (leadingComma))
                print("\t%s-- schemaInfoFromLocation TODO" % (leadingComma))
            #print("\t%s%s_log.log_datetime" % (leadingComma,tablename))
            print("FROM " + tablename)
            #print("LEFT JOIN %s_log ON %s_log.uuid = %s.uuid" % (tablename,tablename,tablename))
            if (field__fk_project_id__found):
                print("LEFT JOIN project ON project.id = %s.fk_project_id" % (tablename))
                print("LEFT JOIN client ON client.id = project.fk_client_id")
            if (tablename == "db_table_field"):
                print("LEFT JOIN db_table ON db_table.id = %s.fk_db_table_id" % (tablename))
            if output=="sql":
                print(";")
            elif output=="liquibase":
                print("]]>")
                print("</createView>")
    print("")

if output=="liquibase":    
    print("<comment>")
    print("<![CDATA[")
    print("Used objects:")
    for s in itemlist:
        tablename = s.attributes['name'].value
        if (filterOnSpecificDbObject != ""):
            if (filterOnSpecificDbObject != tablename):
                continue
        newViewName = GetNewViewName(tablename)
        if tablename=="app_config":
            continue
        print("\t" + newViewName)
    print("Phabricator tasks:")
    print("\t" + "T64")
    print("]]>")
    print("</comment>")

# End of View Generation

# Start Model-Class Generation for yii2 for the the views created above

template ="""<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{{viewname}}}".
 * (Generated by meta#grid Python helper tools).
 */
class {{{modelclassnameFromView}}} extends \\app\\models\\{{{modelclassname}}}
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{{viewname}}}';
    }
    public static function primaryKey()
    {
        return array('id');
    }{{{dbtabledatabaseExceptionPart}}}
}
"""

dbtabledatabaseExceptionPart = """\r\n
    // { ... phabricator-task: T59
    public function attributeLabels() {
        
        $addionalLabels = array('databaseInfoFromLocation' => Yii::t('app', 'Database'));
        return array_merge(parent::attributeLabels(), $addionalLabels);
    }
    // ...}
"""

for s in itemlist:
    tablename = s.attributes['name'].value
    newViewName = GetNewViewName(tablename)
    if tablename=="app_config":
        continue
    if (filterOnSpecificDbObject != ""):
        if (filterOnSpecificDbObject != tablename):
            continue
    templateclass=template
    templateclass=templateclass.replace("{{{viewname}}}", newViewName)
    templateclass=templateclass.replace("{{{modelclassname}}}",yii2_id2camel(tablename, "_"))
    templateclass=templateclass.replace("{{{modelclassnameFromView}}}",yii2_id2camel(newViewName, "_"))
    if (tablename == "db_table" or tablename == "db_table_field"):
        templateclass=templateclass.replace("{{{dbtabledatabaseExceptionPart}}}",dbtabledatabaseExceptionPart)
    else:
        templateclass=templateclass.replace("{{{dbtabledatabaseExceptionPart}}}","")
#     print templateclass
    filenamePhpClass = os.path.join(folderForYii2ModelClasses, yii2_id2camel(newViewName, "_") + ".php")
    myFile = open(filenamePhpClass,"w")
    myFile.write(templateclass)
    myFile.close()