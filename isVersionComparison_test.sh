#!/usr/bin/env bash
. common_function.sh
echo "test begin"
if test `isVersionComparison 1.8.3 1.8.3` != "true" ; then echo "test$((LINENO-3)) NG"; fi
if test `isVersionComparison 1.8.2 1.8.3` != "false"; then echo "test$((LINENO-3)) NG"; fi
if test `isVersionComparison 1.8.4 1.8.3` != "true" ; then echo "test$((LINENO-3)) NG"; fi
if test `isVersionComparison 1.7.3 1.8.3` != "false"; then echo "test$((LINENO-3)) NG"; fi
if test `isVersionComparison 1.9.3 1.8.3` != "true" ; then echo "test$((LINENO-3)) NG"; fi
if test `isVersionComparison 0.8.3 1.8.3` != "false"; then echo "test$((LINENO-3)) NG"; fi
if test `isVersionComparison 2.8.3 1.8.3` != "true" ; then echo "test$((LINENO-3)) NG"; fi
if test `isVersionComparison 0.7.2 1.8.3` != "false"; then echo "test$((LINENO-3)) NG"; fi
if test `isVersionComparison 0.9.4 1.8.3` != "false"; then echo "test$((LINENO-3)) NG"; fi
if test `isVersionComparison 2.7.2 1.8.3` != "true" ; then echo "test$((LINENO-3)) NG"; fi
if test `isVersionComparison 2.9.4 1.8.3` != "true" ; then echo "test$((LINENO-3)) NG"; fi
if test `isVersionComparison 1.7.2 1.8.3` != "false"; then echo "test$((LINENO-3)) NG"; fi
if test `isVersionComparison 1.7.4 1.8.3` != "false"; then echo "test$((LINENO-3)) NG"; fi
if test `isVersionComparison 1.9.2 1.8.3` != "true" ; then echo "test$((LINENO-3)) NG"; fi
if test `isVersionComparison 1.9.4 1.8.3` != "true" ; then echo "test$((LINENO-3)) NG"; fi
echo "test finish"
