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

require_once '../../config.php';
//DB allows us to use Moodle's database apis
global $USER, $DB, $CFG;

$PAGE->set_url('/local/coursecatalogue/index.php');
$PAGE->set_context(context_system::instance());
$PAGE->requires->js('/local/coursecatalogue/assets/coursecatalogue.js');

require_login();

//Everything underneath require_login is only viewable by users who are logged in
/** 
 * Incase we ever need to add a dynamic title, this is how we would go about creating a dynmic title
 *$obj = new stdClass();
 *$obj->coursename = 'test';

 *add $obj to the echo function below, then update the searchbar mustache accordingly
 *i.e <h2> {{object in context}} <h2>
 */

$strpagetitle = get_string('coursecatalogue', 'local_coursecatalogue');
$strpageheading = get_string('coursecatalogue', 'local_coursecatalogue');

$PAGE->set_title($strpagetitle);
$PAGE->set_heading($strpageheading);

//Database
$results = new stdClass();

$sql = "SELECT DISTINCT(gg.usermodified) as graderid
FROM {grade_grades} AS gg
LEFT JOIN {user} AS grader ON grader.id = gg.usermodified
WHERE gg.usermodified <> ''" . $end;
$courseusers = $DB->get_records_sql($sql);

foreach ($courseusers as $key => $value) {
    $courseusers[$key] = $DB->get_record('user', 'firstname, lastname, id,email');
}

$results->data = array_values($courseusers);
print_r($results);

echo $OUTPUT->header();
//the open brackets at the end is there to add a data object
echo $OUTPUT->render_from_template('local_coursecatalogue/searchbars', []);
echo $OUTPUT->render_from_template('local_coursecatalogue/searchresults', [$results]);
echo $OUTPUT->footer();
