# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
  config.vm.box = "ubuntu/xenial64"

  config.vm.hostname = "kiosk-manager.test"
  config.vm.network "private_network", ip: "192.168.230.120"
  config.hostmanager.enabled = true
  config.hostmanager.manage_host = true
  config.hostmanager.manage_guest = true

  config.vm.synced_folder "./", "/var/www/kiosk_manager"

  config.vm.provider "virtualbox" do |v|
    v.memory = 2048
    v.cpus = 2
  end

  config.vm.provision "ansible" do |ansible|
    ansible.playbook = "./deployment/local/provision-python.yaml"
    ansible.compatibility_mode = "2.0"
  end

  config.vm.provision "ansible" do |ansible|
    ansible.playbook = "./deployment/local/provision-local.yaml"
    ansible.compatibility_mode = "2.0"
  end
end
