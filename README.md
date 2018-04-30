# Totara HR Import Monitoring
A very simple plugin which just performs a quick check of the last Totara HR Import for the fatal errors and return OK or ERROR. Also it can notify to configured list of emails.

**Note:** the plugin does not care about broken individual sync records, but focuses on global failures like missing file or wrong format. 

## Installation
To install this plugin in your LMS.
1. Get the code and copy it to: `<lmsdir>/admin/tool/totara_sync_check`
2. Run the upgrade using admin interface or command line, like `sudo -u www-data php admin/cli/upgrade.php`


## Configuration (optional)
1. Log into your LMS site as an administrator.
2. Navigate to  `Site administration ► Plugins ► Admin tools ► HR Import Monitoring`.
3. If required enable notification and set a list of emails.

**Note:** 
the notification will be triggered every time after HR sync has completed, but only if a critical error occurred. 


## Usage
After the plugin is installed, you can navigate to https://yourlms.example.com/admin/tool/totara_sync_check/index.php 

It will return a page with either a 200 or 500 response code and some additional useful information.

### Examples

```
(HTTP 200)
HR Import - OK (finished Apr 30 11:40:06) (Checked Apr 30 11:40:06)
```

```
(HTTP 500)
HR Import - ERROR: Error description from "totara_sync_log" table (finished Apr 30 11:24:47)  (Checked Apr 30 11:24:47)
```

# Crafted by Catalyst IT

This plugin was developed by Catalyst IT Australia:

https://www.catalyst-au.net/

# Contributing and Support

Issues, and pull requests using github are welcome and encouraged!

https://github.com/catalyst/moodle-tool_totara_sync_check/issues

If you would like commercial support or would like to sponsor additional improvements
to this plugin please contact us:

https://www.catalyst-au.net/contact-us
