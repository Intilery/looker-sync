# Looker Sync

The Looker Sync is tool which mocks a inheritance GIT structure. 
It only currently supports GitHub, but wouldn't require much modification to support any Git Repositories.

## Requirements
- PHP >= 5.6

## Table of Contents

1. Setting up your `sync.json`
2. Creating your Deployment Keys
3. Set-up your Webhook
4. Cron Job Alternative
5. Placeholders
6. Hacky things


### 1. Setting up your `sync.json`

The first thing you'll need to do is set-up your configuration file. The configuration file contains a master (or base) record
and an array of children records. The base record should be the repository that triggers the webhook events and files from the base 
are passed down to the children. Both master and children have a git username and repository, path to their deployment key
and a path to a local version of the repository, the only difference is that the master has the webhook secret value for request validation.

Create a `sync.json` containing your project configuration which should look something like:
```
{
  "master": {
    "username":      "GITHUB_USERNAME",
    "project":       "GITHUB_PROJECT",
    "keypath":       "PATH_TO_SSH_KEY",
    "repopath":      "PATH_TO_CLONE_PROJECT_TO",
    "webhooksecret": "WEBHOOK_SECRET"
  },
  "children": [
    {
      "username": "GITHUB_USERNAME",
      "project":  "GITHUB_PROJECT",
      "keypath":  "PATH_TO_SSH_KEY",
      "repopath": "PATH_TO_CLONE_PROJECT_TO"
    }
    ...
    ...
  ]
}
```

** Note: There is an example configuration in the repository called `sync.json.example`

### 2. Creating your Deployment Keys

Under unix, to create your deployment keys simply run:

```
$ ssh-keygen -f /my/file/path/key.rsa -t rsa -N ''
```

You will need to add these to your corresponding repositories as deployment keys and to your `sync.base` configuration file. 
The master project requires read permissions, but all the children require read/write.

### 3. Set-up your Webhook

Goto the Github repository you want to trigger the hook from and point it at the public directory. Your keys will need to 
be bound to the user bound to the http client .e.g `apache`, `www-data`, etc

### 4. Cron Job Alternative

You may not want to use Webhooks so you can instead use the php client and the crontab. All you need to do is bind your 
keys to the crontab user and add the following to your crontab:

```
# This will run every 5 minutes
*/5 * * * * /my/project/source/cmd/run.php
```

### 5. Placeholders

If you want to have repository specific variables then in the children of your `sync.json` you can specify "replaces" which
will simply find and replace values.

### 6. Hacky things

To use the webhook events in real-time you will either need to have a delegate to pass commands to the application,
have fpm running as a user with all the correct permissions or fudge the existing `apache` or `www-data` account by adding 
the folder `/var/www/.ssh` and granting permissions.