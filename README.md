api_tests
=========

The purpose of this project was to get more familiar with various web APIs.
Currently used APIs:
- flickr
- dropbox
- bf3stats
This project also contains some basics implementation of OAuth 2.0 token flow that is required to use the dropbox API.

In order to use this project You need to create file 'keys.php' to be placed in your project's root directory with the following structure:
"""
// keys.php
$flickr_key = "a1a1a1a1a1a1a1a1a1a1a1a1a1a1a1a";
$dropbox_key = "as12as12as12a";
$dropbox_secret = "21sa21sa21sa21s";
$app_root = ((!empty($_SERVER["HTTPS"])) ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . "/my_project";
"""
where flickr_key, dropbox_key and dropbox_secret are valid application keys.

