## Working on the codebase

### New Feature

When working on a new feature it is important that you follow this process:

* create a new branch from develop with the prefix `feature/` (`git flow feature start multi-factor-auth` if you're so inclined)
* work on this branch committing changes often
* push the feature branch to the remote (`git flow feature publish multi-factor-auth`)
* raise a pull request to merge this new feature into develop (do this from bitbucket)
* wait for the requisite checks to pass and then merge your feature
* the new version of the develop branch will now be tested and deployed to staging if it passes
