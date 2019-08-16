@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../raven/raven/bin/raven
php "%BIN_TARGET%" %*
