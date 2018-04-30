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
 * Sync checker class
 *
 * @package    tool_totara_sync_check
 * @copyright  2018 Dmitrii Metelkin <dmitriim@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_totara_sync_check;

defined('MOODLE_INTERNAL') || die;

class sync_checker {

    /**
     * SYnc log table name.
     */
    const SYNC_LOG_TABLE = 'totara_sync_log';

    /**
     * Hardcoded action value we'd like to search for.
     */
    const SYNC_NEEDLE_ACTION = 'unknown';

    /**
     * Hardcoded log type value we'd like to search for.
     */
    const SYNC_NEEDLE_LOG_TYPE = 'error';

    /**
     * Return sync run ID we need to check.
     *
     * @return int|mixed
     * @throws \dml_exception
     */
    protected function get_run_id() {
        global $DB;

        $runid = $DB->get_field(self::SYNC_LOG_TABLE, 'MAX(runid)', []);

        if (!empty($runid)) {
            return $runid;
        } else {
            return 0;
        }
    }

    /**
     * Get sync action we need to check.
     *
     * @return string
     */
    protected function get_action() {
        return self::SYNC_NEEDLE_ACTION;
    }

    /**
     * Return log type we need to check.
     *
     * @return string
     */
    protected function get_log_type() {
        return self::SYNC_NEEDLE_LOG_TYPE;
    }

    /**
     * Return a list of search parameters for SQL.
     *
     * @return array
     * @throws \dml_exception
     */
    protected function get_search_params() {
        return [
            'runid' => $this->get_run_id(),
            'action' => $this->get_action(),
            'logtype' => $this->get_log_type(),
        ];
    }

    /**
     * Return error string if any record is found.
     *
     * @return string
     * @throws \dml_exception
     */
    public function get_error() {
        global $DB;

        $info = '';

        $records = $DB->get_records(self::SYNC_LOG_TABLE, $this->get_search_params());

        if ($records) {
            $record = reset($records);
            $info = $record->info;
        }

        return $info;
    }

    /**
     * Get the last record date from log table.
     *
     * @return string
     * @throws \dml_exception
     */
    public function get_last_record_date() {
        global $DB;

        $time = $DB->get_field(self::SYNC_LOG_TABLE, 'MAX(time)', []);

        if (!empty($time)) {
            return $time;
        } else {
            return 0;
        }
    }

}
