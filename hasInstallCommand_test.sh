#!/usr/bin/env bash
. common_function.sh
echo "test begin"
if test `hasInstallCommand ls` != "true" ; then echo "test$((LINENO-3)) NG"; fi
if test `hasInstallCommand aa` != "false"; then echo "test$((LINENO-3)) NG"; fi
echo "test finish"
