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
 * Sync checker tests class
 *
 * @package    tool_totara_sync_check
 * @copyright  2018 Dmitrii Metelkin <dmitriim@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;


class tool_totara_sync_check_sync_checker_testcase extends advanced_testcase {
    /**
     * Test checker class.
     * @var \tool_totara_sync_check\sync_checker
     */
    protected $checker;

    /**
     * Default record object to be inserted to the log table.
     * @var
     */
    protected $record;

    /**
     * Initial set up.
     */
    protected function setUp() {
        $this->resetAfterTest();
        $this->checker = new \tool_totara_sync_check\sync_checker();
        $this->record = new stdClass();
        $this->record->time = time();
        $this->record->element = 'Test Element';
        $this->record->logtype = '';
        $this->record->action = 'unknown';
        $this->record->info = '';
        $this->record->runid = 0;
    }

    /**
     * Add test log records.
     *
     * @param int $runids Number of run ids.
     * @param int $infos Number of log records with "info" logtype.
     * @param int $errors Number of log records with "error" logtype and "test" action.
     * @param int $unknowns Number of log records with "error" logtype and "unknown" action.
     *
     * @throws \dml_exception
     */
    protected function add_test_records($runids, $infos, $errors, $unknowns) {
        global $DB;

        for ($id = 1; $id <= $runids; $id++) {
            $this->record->runid = $id;

            for ($inf = 1; $inf <= $infos; $inf++) {
                $this->record->logtype = 'info';
                $this->record->info = 'Info ' . $id . $inf;
                $DB->insert_record('totara_sync_log', $this->record);
            }

            for ($error = 1; $error <= $errors; $error++) {
                $this->record->action = 'test_action';
                $this->record->info = 'Error test ' . $id . $error;
                $this->record->logtype = 'error';
                $DB->insert_record('totara_sync_log', $this->record);
            }

            for ($unknown = 1; $unknown <= $unknowns; $unknown++) {
                $this->record->action = 'unknown';
                $this->record->info = 'Error unknown ' . $id . $unknown;
                $this->record->logtype = 'error';
                $DB->insert_record('totara_sync_log', $this->record);
            }
        }
    }

    public function test_sync_log_table_constants() {
        $this->assertEquals('totara_sync_log', \tool_totara_sync_check\sync_checker::SYNC_LOG_TABLE);
        $this->assertEquals('unknown', \tool_totara_sync_check\sync_checker::SYNC_NEEDLE_ACTION);
        $this->assertEquals('error', \tool_totara_sync_check\sync_checker::SYNC_NEEDLE_LOG_TYPE);
    }

    public function test_return_empty_error_if_no_records() {
        $this->assertEmpty($this->checker->get_error());
    }

    public function test_return_error() {
        $this->add_test_records(3, 3, 3, 3);
        $this->assertEquals('Error unknown 31', $this->checker->get_error());
    }

    public function test_return_empty_error_if_no_error() {
        $this->add_test_records(3, 3, 3, 0);
        $this->assertEquals('', $this->checker->get_error());
    }

    public function test_return_empty_last_record_date_if_no_records() {
        $this->assertEmpty($this->checker->get_last_record_date());
    }

    public function test_return_correct_last_record_date_if_no_records() {
        global $DB;

        $this->record->time = 12345676;
        $DB->insert_record('totara_sync_log', $this->record);
        $this->record->time = 12345678;
        $DB->insert_record('totara_sync_log', $this->record);
        $this->record->time = 12345679;
        $DB->insert_record('totara_sync_log', $this->record);
        $this->record->time = 12345677;
        $DB->insert_record('totara_sync_log', $this->record);

        $this->assertEquals(12345679, $this->checker->get_last_record_date());
    }

}
