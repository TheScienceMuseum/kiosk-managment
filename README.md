# Kiosk Management System

## Useful Links

[Monitoring (live and staging)](https://sentry.io/joi-polloi/science-museum-kiosk-management/) /
[Testing and Deployment](https://bitbucket.org/rckt/sciencemuseum-kiosk-management/addon/pipelines/home#!/) / 
[Pull requests](https://bitbucket.org/rckt/sciencemuseum-kiosk-management/pull-requests/) / 
[Application in Staging](https://kms.scimus.clients.joipolloi.com/)

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

## Deployment

### Staging

Deployment to staging environment is done via bitbucket pipelines and monitored
by sentry.

* code is merged to the develop branch
* develop is then put through Unit, Feature, and Browser testing
* assuming all tests pass the codebase is packaged up
* the packaged version of the codebase is uploaded to S3
* a deployment task is sent to CodeDeploy
* the deployment is run using the lifecycle scripts under `./deployment/aws/lifecycle`

## Working on the codebase

### New Feature

When working on a new feature it is important that you follow this process:

* create a new branch from develop with the prefix `feature/` (`git flow feature start multi-factor-auth` if you're so inclined)
* work on this branch committing changes often
* push the feature branch to the remote (`git flow feature publish multi-factor-auth`)
* raise a pull request to merge this new feature into develop (do this from bitbucket)
* wait for the requisite checks to pass and then merge your feature
* the new version of the develop branch will now be tested and deployed to staging if it passes
