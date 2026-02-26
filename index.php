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
 * Plugin ZIP export user interface.
 *
 * @package    local_pluginzip
 * @author     Melvyn Gomez - OpenRanger (melvyng@openranger.com)
 * @copyright  2025 Melvyn Gomez (https://openranger.com/)
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');

require_login();
require_capability('local/pluginzip:export', context_system::instance());

$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/pluginzip/index.php');
$PAGE->set_title(get_string('pluginname', 'local_pluginzip'));
$PAGE->set_heading(get_string('pluginname', 'local_pluginzip'));

$pluginman = core_plugin_manager::instance();

$type   = optional_param('type', '', PARAM_ALPHANUMEXT);
$plugin = optional_param('plugin', '', PARAM_ALPHANUMEXT);
$action = optional_param('action', '', PARAM_ALPHA);

/*
 * Reset plugin selection if it does not belong to the selected type.
 */
if ($type && $plugin) {
    $pluginsbytype = $pluginman->get_plugins();

    if (!isset($pluginsbytype[$type][$plugin])) {
        $plugin = '';
    }
}

if ($action === 'download' && $type && $plugin) {
    require_sesskey();
    $zip = \local_pluginzip\exporter::export_plugin($type, $plugin);
    send_file($zip, "{$type}_{$plugin}.zip");
}

echo $OUTPUT->header();

echo html_writer::start_tag('form', ['method' => 'post']);

/* ---------- Plugin type ---------- */
echo html_writer::label(get_string('selecttype', 'local_pluginzip'), 'type');
echo html_writer::empty_tag('br');

$types = [];
$pluginsbytype = $pluginman->get_plugins();

foreach ($pluginsbytype as $plugintype => $plugins) {
    if (empty($plugins)) {
        continue;
    }

    // Use the first plugin to detect real directory.
    $plugininfo = reset($plugins);
    $plugindir  = $plugininfo->rootdir ?? null;

    if ($plugindir && is_dir($plugindir)) {
        $types[$plugintype] = $plugintype;
    }
}

ksort($types);

echo html_writer::select(
    $types,
    'type',
    $type,
    ['' => '---'],
    ['onchange' => 'this.form.submit()']
);

/* ---------- Plugin list ---------- */
if ($type && isset($pluginman->get_plugins()[$type])) {
    echo html_writer::empty_tag('br');
    echo html_writer::empty_tag('br');

    echo html_writer::label(get_string('selectplugin', 'local_pluginzip'), 'plugin');
    echo html_writer::empty_tag('br');

    $pluginlist = [];
    foreach ($pluginman->get_plugins()[$type] as $name => $p) {
        $pluginlist[$name] = $name;
    }

    ksort($pluginlist);

    echo html_writer::select(
        $pluginlist,
        'plugin',
        $plugin,
        ['' => '---'],
        ['onchange' => 'this.form.submit()']
    );
}

/* ---------- Download button ---------- */
if ($type && $plugin) {
    echo html_writer::empty_tag('br');
    echo html_writer::empty_tag('br');

    echo html_writer::empty_tag('input', [
        'type'  => 'hidden',
        'name'  => 'action',
        'value' => 'download',
    ]);

    echo html_writer::empty_tag('input', [
        'type'  => 'hidden',
        'name'  => 'sesskey',
        'value' => sesskey(),
    ]);

    echo html_writer::empty_tag('input', [
        'type'  => 'submit',
        'value' => get_string('download', 'local_pluginzip'),
    ]);
}

echo html_writer::end_tag('form');

echo $OUTPUT->footer();
