@echo off
REM patrick_schmitz
REM v0.1
REM Starts the LiquiBase Deployment db.changelog-master.xml
call deploy_00_param.bat

SET liquibaseDriverUrl="jdbc:sqlite:C:\Users\patrick_schmitz\Desktop\Patrick\DWH_Meta_wrkCpy_DEV\dwh_meta_v2\dwh_meta.sqlite"

echo liquibasePathExe: 		%liquibasePathExe%
echo liquibaseDriver: 		%liquibaseDriver%
echo liquibaseChangeLogFile: 	%liquibaseChangeLogFile%
echo liquibaseAction: 		%liquibaseAction%
echo liquibaseDriverUrl:		%liquibaseDriverUrl%

echo Execute: 			%liquibasePathExe% --driver=%liquibaseDriver% --changeLogFile=%liquibaseChangeLogFile% --url=%liquibaseDriverUrl% %liquibaseAction%

%liquibasePathExe% --driver=%liquibaseDriver% --changeLogFile=%liquibaseChangeLogFile% --url=%liquibaseDriverUrl% %liquibaseAction%
