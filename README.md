# Looker Sync

The Looker Sync is tool which mocks a inheritance GIT structure. 
It only currently supports GitHub, but wouldn't require much modification to support any Git Repositories.

## Requirements
- PHP >= 5.6

## Table of Contents

1. Setting up your `sync.json`
2. Creating your Deployment Keys
3. Set-up your Webhook
4. Running Composer
5. HTTP Configuration
6. Cron Job Alternative
7. Placeholders
8. Hacky things


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
      "repopath": "PATH_TO_CLONE_PROJECT_TO",
      "replaces": {
        "from": "to"
      }
    }
    ...
    ...
  ]
}
```

** Note: There is an example configuration in the repository called `sync.json.example`

### 2. Creating your Deployment Keys

In a nix environment to create your deployment keys simply run:

```
$ ssh-keygen -f /my/file/path/key.rsa -t rsa -N ''
```

You will need to add these to your corresponding repositories as deployment keys and to your `sync.base` configuration file. 
The master project requires read permissions, but all the children require read/write.

### 3. Set-up your Webhook

Go to the Github repository you want to trigger the hook from and point it at the public folder which should be the only folder exposed by the HTTP server. Your keys will need to 
be bound to the user bound to the http client .e.g `apache`, `www-data`, etc

### 4. Running Composer

Once you have the project checked out you will need to run the following:

```
$ php -f composer.phar install
```

If there are any updates to this project simply either run or create a git hook with the following:

```
$ php -f composer.phar update
```

### 5. HTTP Configuration

This has only currently been tested with PHP-FPM and NGINX. It was tested with the default FPM settings and used the following NGINX vhost (server_name and root have changed):

NGINX
```
server {
    listen *:80;
    server_name   my.domain.com;
    # The server root should be pointing at the public folder
    root /my/server/path/looker-sync/public;

    location / {
        index  index.php;
    }

    location ~ \.php$ {
        fastcgi_param PATH_INFO $fastcgi_path_info;
        fastcgi_param PATH_TRANSLATED $document_root$fastcgi_path_info;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_intercept_errors on;
        include fastcgi_params;
    }
}
```

### 6. Cron Job Alternative

You may not want to use Webhooks so you can instead use the php client and the crontab. All you need to do is bind your 
keys to the crontab user and add the following to your crontab:

```
# This will run every 5 minutes
*/5 * * * * /my/project/source/cmd/run.php
```

### 7. Placeholders

If you want to have repository specific variables then in the children of your `sync.json` you can specify "replaces" which
will simply find and replace values. It's the basic principal of key is the find and value is the replace e.g.

```
"replaces": {
  "from": "to"
}
```

### 8. Hacky things

To use the webhook events in real-time you will either need to have a delegate to pass commands to the application,
have fpm running as a user with all the correct permissions or fudge the existing `apache` or `www-data` account by adding 
the folder `/var/www/.ssh` and granting permissions.
