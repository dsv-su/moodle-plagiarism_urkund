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
 *
 * urkundlib class.
 *
 * @package   plagiarism_urkund
 * @copyright 2024 Department of Computer and System Sciences,
 *         Stockholm University {@link http://dsv.su.se}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace plagiarism_urkund\local;

use moodle_url;
use html_writer;

defined('MOODLE_INTERNAL') || die();
require_once(__DIR__ . '/../../lib.php');


/**
 *
 * urkundlib class.
 *
 * @package   plagiarism_urkund
 * @copyright 2024 Department of Computer and System Sciences,
 *         Stockholm University {@link http://dsv.su.se}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class urkundlib {

    /**
     * Add resubmit button to overall grading pages.
     *
     */
    public static function add_resubmit_button() {
        global $PAGE, $OUTPUT, $DB;
        if (!$PAGE->context instanceof \context_module) {
            return;
        }
        if (!has_capability('plagiarism/urkund:resubmitallfiles', $PAGE->context)) {
            return;
        }
        if ($PAGE->url->compare(new  moodle_url('/mod/quiz/report.php'), URL_MATCH_BASE)) {
            $module = 'quiz';
        } else if ($PAGE->url->compare(new moodle_url('/mod/assign/view.php'), URL_MATCH_BASE)) {
            $module = 'assign';
        } else {
            return;
        }
        if (empty(get_config('plagiarism_urkund', 'enable_mod_' . $module))) {
            return;
        }

        $useurkund = $DB->get_field(
            'plagiarism_urkund_config',
            'value',
            ['cm' => $PAGE->context->instanceid, 'name' => 'use_urkund']
        );
        if (empty($useurkund)) {
            return;
        }

        $url = new moodle_url('/plagiarism/urkund/reset.php', ['cmid' => $PAGE->context->instanceid, 'resetall' => 1]);
        $button = $OUTPUT->single_button($url, get_string('resubmittourkund', 'plagiarism_urkund'));
        $PAGE->set_button($PAGE->button . html_writer::div($button), 'urkundresubmit');
    }
}
