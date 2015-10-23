#
# Cookbook Name:: php
# Recipe:: default
#
# Copyright 2015, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#
%w[
  php-posix
  php
  php-pdo
  php-mbstring
  php-mysqlnd
  php-xml
  php-pecl-apc
].each do |pkg|
  package "#{pkg}" do
    action :install
    options '--enablerepo=remi-php55'
  end
end

template "/etc/php.ini" do
  source "php.ini.timezone.erb"
  mode 0644
  owner "root"
  group "root"
end

