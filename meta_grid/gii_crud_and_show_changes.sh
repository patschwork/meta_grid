#!/bin/sh

./gii_crud.sh | tee out.txt
cat out.txt | grep "over"