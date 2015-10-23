#
# Cookbook Name:: yum_repo
# Recipe:: default
#
# Copyright 2015, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#
bash 'add_epel' do
  user 'root'
  code <<-EOC
    rpm -ivh http://ftp.riken.jp/Linux/fedora/epel/7/x86_64/e/epel-release-7-5.noarch.rpm
    sed -i -e "s/enabled *= *1/enabled=0/g" /etc/yum.repos.d/epel.repo
  EOC
  creates "/etc/yum.repos.d/epel.repo"
end

