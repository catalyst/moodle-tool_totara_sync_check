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
 * Output error.
 *
 * @package    tool_totara_sync_check
 * @copyright  2018 Dmitrii Metelkin <dmitriim@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('NO_MOODLE_COOKIES', true);
define('NO_UPGRADE_CHECK', true);

require('../../../config.php');

header("Content-Type: text/plain");
header('Pragma: no-cache');
header('Cache-Control: private, no-cache, no-store, max-age=0, must-revalidate, proxy-revalidate');
header('Expires: Tue, 04 Sep 2012 05:32:29 GMT');

$checker = new \tool_totara_sync_check\sync_checker();
$error = $checker->get_error();
$lastrecord = $checker->get_last_record_date();

$now = userdate(time(), '%b %d %H:%M:%S');
$lastrecordstring = empty($lastrecord) ? '(never ran)' : '(finished ' . userdate($lastrecord, '%b %d %H:%M:%S') . ')';

if ($error) {
    header("HTTP/1.0 500 HR Import failed: $error");
    print "HR Import - ERROR: $error $lastrecordstring  (Checked $now)\n";
} else {
    print "HR Import - OK $lastrecordstring (Checked $now)\n";
}
