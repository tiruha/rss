#
# Cookbook Name:: apache
# Recipe:: default
#
# Copyright 2015, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#
service 'httpd' do
  supports :status => true, :restart => true, :reload => true
  action [ :enable, :restart ]
end

