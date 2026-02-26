<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Handles exporting installed plugins as ZIP archives.
 *
 * @package    local_pluginzip
 * @author     Melvyn Gomez - OpenRanger (melvyng@openranger.com)
 * @copyright  2025 Melvyn Gomez (https://openranger.com/)
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_pluginzip;

/**
 * Plugin ZIP exporter.
 *
 * @package local_pluginzip
 */
class exporter {
    /**
     * Export an installed plugin as a ZIP archive.
     *
     * @param string $type Plugin type (local, mod, block, etc.).
     * @param string $plugin Plugin name.
     * @return string Path to the generated ZIP file.
     */
    public static function export_plugin(string $type, string $plugin): string {
        global $CFG;

        require_once($CFG->libdir . '/filelib.php');

        $pluginman = \core_plugin_manager::instance();
        $pluginsbytype = $pluginman->get_plugins();

        if (
            empty($pluginsbytype[$type][$plugin]) ||
            empty($pluginsbytype[$type][$plugin]->rootdir) ||
            !is_dir($pluginsbytype[$type][$plugin]->rootdir)
        ) {
            throw new \moodle_exception(
                'invalidpluginpath',
                'local_pluginzip'
            );
        }

        $pluginpath = $pluginsbytype[$type][$plugin]->rootdir;

        $tempdir = make_temp_directory('pluginzip');
        $zipfile = $tempdir . '/' . $type . '_' . $plugin . '.zip';

        $filesforzip = [];

        $directory = new \RecursiveDirectoryIterator(
            $pluginpath,
            \FilesystemIterator::SKIP_DOTS
        );

        $iterator = new \RecursiveIteratorIterator($directory);

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $fullpath = $file->getPathname();

                // Path inside ZIP.
                $relativepath = substr($fullpath, strlen($pluginpath) + 1);
                $zipinternalpath = $plugin . '/' . $relativepath;

                $filesforzip[$zipinternalpath] = $fullpath;
            }
        }

        if (empty($filesforzip)) {
            throw new \moodle_exception('No files found to archive');
        }

        $zipper = new \zip_packer();
        $zipper->archive_to_pathname($filesforzip, $zipfile);

        return $zipfile;
    }
}
