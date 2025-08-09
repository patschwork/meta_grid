#!/bin/sh
# patrick_schmitz
# v0.1
# Starts the LiquiBase Deployment with a helper Python Script
export cd=$(pwd)
mkdir -p logs
export pythonpathExe=python3
export ENVKEY_DEMO=DEMODATA_MAC
export ENVKEY_CLEAN=DEMODATA_MAC_CLEAN
export CONFIGPATH=$cd
# echo $CONFIGPATH
cd ../../helper_tools/Python/liquibase/
$pythonpathExe deploy.py $ENVKEY_DEMO $CONFIGPATH
$pythonpathExe deploy.py $ENVKEY_CLEAN $CONFIGPATH
cd $cd
rm -R logs