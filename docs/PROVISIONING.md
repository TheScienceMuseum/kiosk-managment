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

### Staging / Production

You'll find a script under `deployment/aws/provision.sh` that will configure
an aws ec2 instance running any version of the Ubuntu ami to run this 
application.

Just scp the `provision.sh` file to the remote server, ssh to it, then run the 
provisioning script.
