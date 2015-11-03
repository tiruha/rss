#!/usr/bin/env bash
. ./common_function.sh

BASEDIR=$(cd $(dirname $0) && pwd)

os_version=el6
os_bit=x86_64

### install general ###
# install wget
install_command=wget
if [ `hasInstallCommand ${install_command}` = "false" ]; then
    sudo yum -y install ${install_command}
fi

### install VirtualBox ###
# install dksm
install_version=0.5.3-1
install_command=dkms
if [ `hasInstallCommand ${install_command}` = "false" ]; then
    wget http://pkgs.repoforge.org/rpmforge-release/rpmforge-release-${instal_version}.${os_version}.rf.${os_bit}.rpm
    sudo rpm -ivh rpmforge-release-${install_version}.${os_version}.rf.${os_bit}.rpm
    sudo yum -y install ${install_command}
fi

# install VirtualBox
install_version=4.3
install_command=VirtualBox
if [ `hasInstallCommand ${install_command}` = "false" ]; then
    cd /etc/yum.repos.d/
    sudo wget http://download.virtualbox.org/virtualbox/rpm/rhel/virtualbox.repo
    sudo yum -y list | grep ${install_command}
    sudo yum -y install ${install_command}-${install_version}.${os_bit}
    sudo usermod -a -G vboxusers ${USER}
    cd ${BASEDIR}
fi

### install vagrant ###
# install vagrant
install_version=1.7.2
install_command=vagrant
if [ `hasInstallCommand ${install_command}` = "false" ]; then
    sudo rpm -Uvh https://dl.bintray.com/mitchellh/vagrant/vagrant_${install_version}_${os_bit}.rpm
    vagrant plugin install vagrant-vbguest
    vagrant plugin install vagrant-omnibus
    vagrant plugin install vagrant-hostsupdater
fi

### install chef ###
# install ruby
install_command=ruby
install_version=2.2.3

rubyInstall(){
    git clone git://github.com/sstephenson/rbenv.git
    mkdir -p rbenv/plugins
    cd rbenv/plugins
    git clone git://github.com/sstephenson/ruby-build.git
    cd ${BASEDIR}
    echo '#rbenv' >> ~/.bash_profile
    echo "export RBENV_ROOT=\"${BASEDIR}/rbenv\"" >> ~/.bash_profile
    echo 'export PATH="${RBENV_ROOT}/bin:${PATH}"' >> ~/.bash_profile
    echo 'eval "$(rbenv init -)"' >> ~/.bash_profile
    . ~/.bash_profile
    sudo yum -y install gcc make openssl-devel libffi-devel readline_devel
    rbenv install ${install_version}
    #現在のインストールバージョンを上書き
    rbenv global
}

if [ `hasInstallCommand ${install_command}` = "false" ]; then
    rubyInstall
else
    ruby_version=`ruby -v | awk '{print substr($0, 6, 5)}'`
    comparison_version=1.9.3
    if [ `isVersionComparison ${ruby_version} ${comparison_version}` = "false" ]; then
        rubyInstall
    fi
fi

# install chef
install_command=chef
if [ `hasInstallCommand ${install_command}` = "false" ]; then
    gem install chef
    gem install knife-solo 
fi

### start vagrant ###
vagrant up

# return success
return 0

