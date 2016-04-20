# create database
db_name = 'phpms'
create_db_file = 'create_db.sql'
execute "create_db" do
  command "/usr/bin/mysql -u root < #{Chef::Config[:file_cache_path]}/#{create_db_file}"
  action :nothing
  not_if "/usr/bin/mysql -u root -D #{db_name}"
end

template "#{Chef::Config[:file_cache_path]}/#{create_db_file}" do
  owner 'root'
  group 'root'
  mode 644
  source "#{create_db_file}.erb"
  variables({
    :db_name => db_name,
    })
  notifies :run, 'execute[create_db]', :immediately
end

# create database user
user_name = 'phpms'
user_password = 'phpms'
create_user_file = 'create_user.sql'
execute 'create_user' do
  command "/usr/bin/mysql -u root < #{Chef::Config[:file_cache_path]}/#{create_user_file}"
  action :nothing
  not_if "/usr/bin/mysql -u #{user_name} -p#{user_password} -D #{db_name}"
end

template "#{Chef::Config[:file_cache_path]}/#{create_user_file}" do
  owner 'root'
  group 'root'
  mode 644
  source "#{create_user_file}.erb"
  variables({
      :db_name => db_name,
      :username => user_name,
      :password => user_password,
    })
  notifies :run, 'execute[create_user]', :immediately
end

# create schema
schema_file = 'create_schema_phpms.sql'
execute "create_schema_phpms" do
  command "/usr/bin/mysql -u root #{db_name} < #{Chef::Config[:file_cache_path]}/#{schema_file}"
  action :nothing
  not_if "/usr/bin/mysql -u #{user_name} -p#{user_password} -D #{db_name}  -e 'show tables' | wc -l | xargs expr 1 /"
end

template "#{Chef::Config[:file_cache_path]}/#{schema_file}" do
  owner 'root'
  group 'root'
  mode 644
  source "#{schema_file}.erb"
  notifies :run, 'execute[create_schema_phpms]', :immediately
end

# create table
table_file = 'create_table_login_user.sql'
execute "create_table_login_user" do
  command "/usr/bin/mysql -u root #{db_name} < #{Chef::Config[:file_cache_path]}/#{table_file}"
  action :nothing
  not_if "/usr/bin/mysql -u #{user_name} -p#{user_password} -D #{db_name}  -e 'show tables' | wc -l | xargs expr 1 /"
end

template "#{Chef::Config[:file_cache_path]}/#{table_file}" do
  owner 'root'
  group 'root'
  mode 644
  source "#{table_file}.erb"
  notifies :run, 'execute[create_table_login_user]', :immediately
end

table_file = 'create_table_url.sql'
execute "create_table_url" do
  command "/usr/bin/mysql -u root #{db_name} < #{Chef::Config[:file_cache_path]}/#{table_file}"
  action :nothing
  not_if "/usr/bin/mysql -u #{user_name} -p#{user_password} -D #{db_name}  -e 'show tables' | wc -l | xargs expr 1 /"
end

template "#{Chef::Config[:file_cache_path]}/#{table_file}" do
  owner 'root'
  group 'root'
  mode 644
  source "#{table_file}.erb"
  notifies :run, 'execute[create_table_url]', :immediately
end

table_file = 'create_table_synonym.sql'
execute "create_table_synonym" do
  command "/usr/bin/mysql -u root #{db_name} < #{Chef::Config[:file_cache_path]}/#{table_file}"
  action :nothing
  not_if "/usr/bin/mysql -u #{user_name} -p#{user_password} -D #{db_name}  -e 'show tables' | wc -l | xargs expr 1 /"
end

template "#{Chef::Config[:file_cache_path]}/#{table_file}" do
  owner 'root'
  group 'root'
  mode 644
  source "#{table_file}.erb"
  notifies :run, 'execute[create_table_synonym]', :immediately
end

table_file = 'create_table_url_group.sql'
execute "create_table_url_group" do
  command "/usr/bin/mysql -u root #{db_name} < #{Chef::Config[:file_cache_path]}/#{table_file}"
  action :nothing
  not_if "/usr/bin/mysql -u #{user_name} -p#{user_password} -D #{db_name}  -e 'show tables' | wc -l | xargs expr 1 /"
end

template "#{Chef::Config[:file_cache_path]}/#{table_file}" do
  owner 'root'
  group 'root'
  mode 644
  source "#{table_file}.erb"
  notifies :run, 'execute[create_table_url_group]', :immediately
end

table_file = 'create_table_similar_url.sql'
execute "create_table_similar_url" do
  command "/usr/bin/mysql -u root #{db_name} < #{Chef::Config[:file_cache_path]}/#{table_file}"
  action :nothing
  not_if "/usr/bin/mysql -u #{user_name} -p#{user_password} -D #{db_name}  -e 'show tables' | wc -l | xargs expr 1 /"
end

template "#{Chef::Config[:file_cache_path]}/#{table_file}" do
  owner 'root'
  group 'root'
  mode 644
  source "#{table_file}.erb"
  notifies :run, 'execute[create_table_similar_url]', :immediately
end

table_file = 'create_table_parameters.sql'
execute "create_table_parameters" do
  command "/usr/bin/mysql -u root #{db_name} < #{Chef::Config[:file_cache_path]}/#{table_file}"
  action :nothing
  not_if "/usr/bin/mysql -u #{user_name} -p#{user_password} -D #{db_name}  -e 'show tables' | wc -l | xargs expr 1 /"
end

template "#{Chef::Config[:file_cache_path]}/#{table_file}" do
  owner 'root'
  group 'root'
  mode 644
  source "#{table_file}.erb"
  notifies :run, 'execute[create_table_parameters]', :immediately
end

table_file = 'create_table_rss_url.sql'
execute "create_table_rss_url" do
  command "/usr/bin/mysql -u root #{db_name} < #{Chef::Config[:file_cache_path]}/#{table_file}"
  action :nothing
  not_if "/usr/bin/mysql -u #{user_name} -p#{user_password} -D #{db_name}  -e 'show tables' | wc -l | xargs expr 1 /"
end

template "#{Chef::Config[:file_cache_path]}/#{table_file}" do
  owner 'root'
  group 'root'
  mode 644
  source "#{table_file}.erb"
  notifies :run, 'execute[create_table_rss_url]', :immediately
end

