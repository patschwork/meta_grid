@echo off
REM patrick_schmitz
REM v0.1
REM Set default parameter
SET liquibasePathExe="C:\Tools\LiquiBase\liquibase.bat"
SET liquibaseDriver="org.sqlite.JDBC"
SET liquibaseChangeLogFile="db.changelog-master.xml"
SET liquibaseAction="migrate"