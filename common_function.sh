#!/usr/bin/env bash

hasInstallCommand(){
    CMDNAME=`basename $0`
    N=1
    if [ $# -ne $N ]; then
        echo "usage: $CMDNAME [rpm name] : rpmが存在しているかをチェック"
        return 1
    fi
    ret=`rpm -qa | grep ${1}`
    if [ -n ${ret} ]; then
        echo "true"
    else
        echo "false"
    fi
}

isVersionComparison(){
    CMDNAME=`basename $0`
    N=2
    if [ $# -ne $N ]; then
        echo "usage: $CMDNAME [チェックするバージョン x.x.x] [比較元 x.x.x] : 比較元以上のバージョンかをチェック"
        return 1
    fi
    check_version=$1
    comparison_version=$2

    for i in 1 3 5; do
        check=`echo ${check_version} | awk -v i=${i} '{print substr($0, i, 1)}'`
        comparison=`echo ${comparison_version} | awk -v i=${i} '{print substr($0, i, 1)}'`
        if [ ${check} -gt ${comparison} ]; then
            echo "true"
            return 0
        elif [ ${check} -lt ${comparison} ]; then
            echo "false"
            return 0
        fi
    done
    echo "true"
}

