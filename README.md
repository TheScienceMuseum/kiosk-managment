# Kiosk Management System

## Provisioning

Provisioning and deploying the codebase both in staging, 
and in production requires only `ansible`. Working with
the codebase locally requires:

* ansible
* vagrant
* vagrant plugins:
  * vagrant-hostmanager
  * vagrant-vbguest
* virtualbox

### Local

Setting up your development environment can be done in a single step
from the root project directory:

```bash
vagrant up
```

### Troubleshooting

#### `/usr/bin/python: not found`

If you get output like the following when running the provisioning 
playbook against any host, it can be easily fixed.

```bash
fatal: [kiosk-staging]: FAILED! => {"changed": false, "module_stderr": "Shared connection to 13.58.202.202 closed.\r\n", "module_stdout": "/bin/sh: 1: /usr/bin/python: not found\r\n", "msg": "MODULE FAILURE", "rc": 127}
```

Run: `ansible-playbook deployment/provision-python.yaml --inventory <ssh hostname>,`
