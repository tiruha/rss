#
# Cookbook Name:: apache
# Recipe:: default
#
# Copyright 2015, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#
%w[

  httpd
  httpd-devel
  mod_ssl
].each do |pkg|
  package "#{pkg}" do
    action :install
  end
end

directory "/etc/httpd/conf" do
  mode 00755
  action :create
end

template "/etc/httpd/conf/httpd.conf" do
  source "httpd-2.2.conf.erb"
end

service 'httpd' do
  supports :status => true, :restart => true, :reload => true
  action [ :enable, :start ]
end

