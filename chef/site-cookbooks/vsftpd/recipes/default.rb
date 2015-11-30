#
# Cookbook Name:: vsftpd
# Recipe:: default
#
# Copyright 2015, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#
%w(vsftpd).each do |pkg|
  package pkg do
    action :install
  end
end

directory '/var/vsftpd' do
  owner 'root'
  group 'root'
  mode '0755'
  action :create
end

template "vsftpd.conf" do
  path "/etc/vsftpd/vsftpd.conf"
  source "vsftpd.conf.erb"
  owner "root"
  group "root"
  mode "600"
end

service "vsftpd" do
  action [:enable, :start]
end

