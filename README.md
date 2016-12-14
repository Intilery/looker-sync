# Looker Sync

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

Make sure the apache users has read/write access to `/var/www/.ssh`
