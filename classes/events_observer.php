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
 * Events observer.
 *
 * @package    tool_totara_sync_check
 * @copyright  2018 Dmitrii Metelkin <dmitriim@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_totara_sync_check;

use tool_totara_sync\event\sync_completed;
use totara_core\totara_user;

defined('MOODLE_INTERNAL') || die;

class events_observer {

    /**
     * Listen to HR sync to complete and notify users if required.
     *
     * @param \tool_totara_sync\event\sync_completed $event
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public static function sync_completed(sync_completed $event) {
        global $CFG;

        $notify = get_config('tool_totara_sync_check', 'notify');
        $notifylist = get_config('tool_totara_sync_check', 'notifylist');

        if (!empty($notify) && !empty($notifylist)) {
            $checker = new sync_checker();

            if (!empty($checker->get_error())) {
                $subject = get_string('subject', 'tool_totara_sync_check');
                $message = userdate($checker->get_last_record_date(), '%b %d %H:%M:%S') . ' - ' . $checker->get_error();
                $message .= "\n\n" . get_string('viewsyncloghere', 'tool_totara_sync',
                        $CFG->wwwroot . '/admin/tool/totara_sync/admin/synclog.php');

                $notifyemails = explode(',', $notifylist);
                $supportuser = \core_user::get_support_user();

                foreach ($notifyemails as $emailaddress) {
                    $userto = totara_user::get_external_user(trim($emailaddress));
                    email_to_user($userto, $supportuser, $subject, $message);
                }
            }
        }
    }
}