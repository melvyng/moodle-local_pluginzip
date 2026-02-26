# Plugin ZIP Exporter

**Component:** local_pluginzip  
**Type:** Local plugin  
**Moodle versions:** 4.5.x  
**License:** GNU GPL v3 or later  

## Overview

Plugin ZIP Exporter allows administrators to download installed Moodle plugins as ZIP files directly from the Moodle interface.

The plugin provides a simple interface to select a plugin type (mod, local, block, etc.), choose an installed plugin, and export its source code as a ZIP archive. This is useful for backup, migration, auditing, or development purposes.
The plugin is fully self-contained and suitable for installations where the hosting provider maintains private, non-shareable custom modifications and does not expose the Moodle codebase to the site owner.

---

## Features

- List installed plugins by type
- Select any installed plugin
- Export plugin source code as ZIP
- Uses Moodle core ZIP utilities
- Admin-only access
- No database modifications

---

## Installation

To install this plugin, you must be an administrator of your Moodle site.

 1. Downlod an appropriate version from [here](https://moodle.org/plugins/pluginversions.php?plugin=local_pluginzip) based on your installed Moodle version.
 2. Go to Moodle `Site administration` > `Plugins` > `Install plugins`
 3. Upload the downloaded zip file to the provided box.
 4. Click `Show more...` and select `Local plugin (local)` under plugin type.
 5. Click `Install plugin from ZIP file`
 5. Provide your reminders settings once asked.
 6. That's it!

Or
1. Copy the plugin folder to your ~moodle/local/ and unzip the file in that location.
2. Visit: `Site administration` > `Notifications`
3. Complete installation.

---

## Usage

1. Go to: `Site administration` > `Tools` > `Plugin Zip Exporter`
2. Select a plugin type (local, mod, block, etc.).
3. Choose an installed plugin.
4. Click **Download ZIP**.
5. The plugin source code will be downloaded as a ZIP file.

---

## Security

- Access restricted to administrators.
- Only installed plugins can be exported.
- Files are read directly from Moodle code directories.
- No write operations are performed.

---

## Limitations

- Does not export Moodle core components.
- Does not include database data.
- Intended for trusted administrative environments only.

---

## Author

Melvyn Gomez
melvyng@openranger.com
OpenRanger S. A. de C.V.  
https://openranger.com/

---

## License

This plugin is licensed under the GNU General Public License v3 or later.
