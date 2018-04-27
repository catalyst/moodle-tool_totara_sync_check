<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Settings.
 *
 * @package    tool_totara_sync_check
 * @copyright  2018 Dmitrii Metelkin <dmitriim@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {

    $settings = new admin_settingpage('tool_totara_sync_check', get_string('pluginname', 'tool_totara_sync_check'));

    $ADMIN->add('tools', $settings);

    if (!during_initial_install()) {

        $settings->add(new admin_setting_configcheckbox('tool_totara_sync_check/notify',
                new lang_string('notify', 'tool_totara_sync_check'),
                new lang_string('notify_desc', 'tool_totara_sync_check'),
                0)
        );

        $settings->add(new admin_setting_configtext('tool_totara_sync_check/notifylist',
            new lang_string('notifylist', 'tool_totara_sync_check'),
            new lang_string('notifylist_desc', 'tool_totara_sync_check'),
            '')
        );
    }
}
