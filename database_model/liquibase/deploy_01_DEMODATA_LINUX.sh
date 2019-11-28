#!/bin/sh
# patrick_schmitz
# v0.1
# Starts the LiquiBase Deployment with a helper Python Script
export cd=$(pwd)
mkdir -p logs
export pythonpathExe=python
export ENVKEY_DEMO=DEMODATA_LINUX
export ENVKEY_CLEAN=DEMODATA_LINUX_CLEAN
export CONFIGPATH=$cd
# echo $CONFIGPATH
cd ../../helper_tools/Python/liquibase/
$pythonpathExe deploy.py $ENVKEY_DEMO $CONFIGPATH
$pythonpathExe deploy.py $ENVKEY_CLEAN $CONFIGPATH
cd $cd
rm -R logs