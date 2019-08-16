@echo off
SET wgetPath="C:\Tools\wget\wget.exe"
SET baseURL="http://localhost:81/dwh_meta_v2_DEV_user/frontend/yii/basic/web/index.php?r="

SET controller=client
%wgetPath% %baseURL%%controller% -O NUL

SET controller=project
%wgetPath% %baseURL%%controller% -O NUL

SET controller=keyfigure
%wgetPath% %baseURL%%controller% -O NUL

SET controller=attribute
%wgetPath% %baseURL%%controller% -O NUL

SET controller=contact
%wgetPath% %baseURL%%controller% -O NUL

SET controller=contact-group
%wgetPath% %baseURL%%controller% -O NUL

SET controller=sourcesystem
%wgetPath% %baseURL%%controller% -O NUL

SET controller=database
%wgetPath% %baseURL%%controller% -O NUL

SET controller=dbtable
%wgetPath% %baseURL%%controller% -O NUL

SET controller=dbtablefield
%wgetPath% %baseURL%%controller% -O NUL

SET controller=dbtablecontext
%wgetPath% %baseURL%%controller% -O NUL

SET controller=dbtabletype
%wgetPath% %baseURL%%controller% -O NUL

SET controller=tool
%wgetPath% %baseURL%%controller% -O NUL

SET controller=tooltype
%wgetPath% %baseURL%%controller% -O NUL

SET controller=datatransferprocess
%wgetPath% %baseURL%%controller% -O NUL

SET controller=datatransfertype
%wgetPath% %baseURL%%controller% -O NUL

SET controller=datadeliveryobject
%wgetPath% %baseURL%%controller% -O NUL

SET controller=datadeliverytype
%wgetPath% %baseURL%%controller% -O NUL

SET controller=scheduling
%wgetPath% %baseURL%%controller% -O NUL

SET controller=mapper
%wgetPath% %baseURL%%controller% -O NUL

SET controller=objectdependson
%wgetPath% %baseURL%%controller% -O NUL

SET controller=glossary
%wgetPath% %baseURL%%controller% -O NUL

SET controller=bracket
%wgetPath% %baseURL%%controller% -O NUL

SET controller=global-search
%wgetPath% %baseURL%%controller% -O NUL

SET controller=documentation
%wgetPath% %baseURL%%controller% -O NUL

SET controller=objectcomment
%wgetPath% %baseURL%%controller% -O NUL