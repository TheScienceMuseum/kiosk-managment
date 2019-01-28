Feature\\KioskClient\\HealthCheck
---------------------------------


✓ Health check from unregistered kiosk returns entity not found

✓ Health check from registered kiosk returns required information

✓ Health check from a registered kiosk with logs attaches logs to the kiosk

Feature\\KioskClient\\PackageDownload
-------------------------------------


✓ Assigning a package to a registered kiosk shows on the kiosks next health check

✓ Downloading a package assigned to a registered kiosk gives a valid tar archive

Feature\\KioskClient\\Registration
----------------------------------


✓ Registration from registered kiosk returns unprocessable entity

✓ Registration from unregistered kiosk returns registration confirmation

Feature\\KioskManagement\\SearchForKiosks
-----------------------------------------


✓ Searching for a registered kiosk

✓ Searching for an unregistered kiosk

Feature\\PackageManagement\\CreateNewPackage
--------------------------------------------


✓ Creating a package without information fails with appropriate messages

✓ Creating a package with an existing name fails with appropriate messages

✓ Creating a package as a developer succeeds

✓ Creating a package as an admin succeeds

✓ Creating a package as a tech admin fails

✓ Creating a package as a content author succeeds

✓ Creating a package as a content editor succeeds

Feature\\PackageManagement\\CreateNewPackageVersion
---------------------------------------------------


✓ Creating a new version of a non existent package fails

✓ Creating a new version of an existing package

Feature\\PackageManagement\\DeletingPackages
--------------------------------------------


✓ Deleting a package as a developer succeeds

✓ Deleting a package as an admin succeeds

✓ Deleting a package as a tech admin fails

✓ Deleting a package as a content editor succeeds

✓ Deleting a package as a content author fails

Feature\\PackageManagement\\PackageDetails
------------------------------------------


✓ Getting package information as a developer succeeds

✓ Getting package information as an admin succeeds

✓ Getting package information as a tech admin succeeds

✓ Getting package information as a content editor succeeds

✓ Getting package information as a content author succeeds

Feature\\PackageManagement\\PackageVersionApproveForDeployment
--------------------------------------------------------------


✓ Approving a package version as a developer succeeds

✓ Approving a package version as an admin succeeds

✓ Approving a package version as a tech admin fails

✓ Approving a package version as a content editor succeeds

✓ Approving a package version as a content author fails

Feature\\PackageManagement\\PackageVersionBuild
-----------------------------------------------


✓ Triggering a package build from the command line succeeds

✓ Triggering a package build from the command line with an invalid package version fails

✓ Building a valid package version succeeds

✓ Rebuilding a valid package version succeeds

Feature\\PackageManagement\\PackageVersionDetails
-------------------------------------------------


✓ Getting package version information as a developer succeeds

✓ Getting package version information as an admin succeeds

✓ Getting package version information as a tech admin succeeds

✓ Getting package version information as a content editor succeeds

✓ Getting package version information as a content author succeeds

Feature\\PackageManagement\\PackageVersionSubmitForApproval
-----------------------------------------------------------


✓ Submitting a package version for approval as a developer succeeds

✓ Submitting a package version for approval as an admin succeeds

✓ Submitting a package version for approval as a tech admin fails

✓ Submitting a package version for approval as a content editor succeeds

✓ Submitting a package version for approval as a content author succeeds

Feature\\PackageManagement\\SearchForPackages
---------------------------------------------


✓ Searching for a package by name

Feature\\PackageManagement\\UpdatingPackage
-------------------------------------------


✓ Updating a package name as a developer fails

✓ Updating a package name as an admin fails

✓ Updating a package name as a tech admin fails

✓ Updating a package name as a content editor fails

✓ Updating a package name as a content author fails

Feature\\UserManagement\\CreateUsers
------------------------------------


✓ Creating a new user without all fields fails with appropriate messages

✓ Creating a new user sends an onboarding email to that user

✓ Creating a new user acting as a developer succeeds

✓ Creating a new user acting as an admin succeeds

✓ Creating a new user acting as a tech admin fails

✓ Creating a new user acting as a content author fails

✓ Creating a new user acting as a content editor fails

Feature\\UserManagement\\SearchForUser
--------------------------------------


✓ Searching for a user by name

✓ Searching for a user by email

✓ Searching for a user by role

Feature\\UserManagement\\ShowUserInformation
--------------------------------------------


✓ Showing information about a user as a developer succeeds

✓ Showing information about a user as an admin succeeds

✓ Showing information about a user as a tech admin fails

✓ Showing information about a user as a content editor fails

✓ Showing information about a user as a content author fails

Feature\\UserManagement\\SuspendUsers
-------------------------------------


✓ Suspending a user acting as a developer succeeds

✓ Suspending a user acting as an admin succeeds

✓ Suspending a user acting as a tech admin fails

✓ Suspending a user acting as a content editor fails

✓ Suspending a user acting as a content author fails

Feature\\UserManagement\\UpdateUsers
------------------------------------


✓ Updating a user without all fields fails with appropriate messages

✓ Updating a user acting as a developer succeeds

✓ Updating a user acting as an admin succeeds

✓ Updating a user acting as a tech admin fails

✓ Updating a user acting as a content editor fails

✓ Updating a user acting as a content author fails

Feature\\UserManagement\\UserRoles
----------------------------------


✓ Getting a list of all valid user roles