# -*- mode: ruby -*-
# vi: set ft=ruby :

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  # vmbox
  config.vm.box = "centos6"
  config.vm.box_url = "https://github.com/2creatives/vagrant-centos/releases/download/v6.5.3/centos65-x86_64-20140116.box"
  # vagrant hosts local hosts
  # Please enter the local password
  config.vm.network "private_network", ip: "192.168.33.11"
  config.vm.hostname = "vmcentos"
  config.hostsupdater.aliases = ["vmcentos"]
  # vagrant config
  config.ssh.forward_agent = true
  config.vbguest.auto_update = false # Guest Additions自動更新無効
  # mount directory
  config.vm.synced_folder "./src", "/var/www/src", :create => true, :owner => 'vagrant', :group => 'vagrant', :mount_options => ['dmode=777', 'fmode=777']
  # chef/site-cookbooks/
  config.vm.provision "chef_solo" do |chef|
    chef.cookbooks_path = "./chef/site-cookbooks"
    chef.run_list = %w[
      recipe[yum_repo]
      recipe[localedef]
      recipe[remi]
      recipe[apache]
      recipe[apache::phpms]
      recipe[mysql]
      recipe[mysql::createdb_phpms]
      recipe[php]
      recipe[apache::restart]
    ]
  end
  # chef install
  config.omnibus.chef_version = :latest
  # vm config
  config.vm.provider "virtualbox" do |vb|
    vb.name = "centos6_vagrant"
    vb.memory = "2048"
    vb.customize ["modifyvm", :id, "--natdnsproxy1", "off"]
    vb.customize ["modifyvm", :id, "--natdnshostresolver1", "off"]
  end

end

