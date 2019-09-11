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

### NOTE

* `sudo service php7.3-fpm restart`
* `sudo supervisorctl restart all`