@echo off
REM patrick_schmitz
REM v0.3
REM Starts the LiquiBase Deployment with a helper Python Script
pushd %cd%
SET pythonpathExe=C:\Python27\python.exe
SET ENVKEY=DEMODATA
SET CONFIGPATH=%cd%
REM echo %CONFIGPATH%
cd ..\..\helper_tools\Python\liquibase\
%pythonpathExe% deploy.py %ENVKEY% %CONFIGPATH%
popd