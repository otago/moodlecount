<?php

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

require_once($CFG->libdir . "/externallib.php");

class local_op_wstemplate_external extends external_api {

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function count_read_messages_parameters() {
        return new external_function_parameters(
                array(
					'userid' => new external_value(PARAM_TEXT, 'Will return a count of messages', VALUE_DEFAULT, '')
				)
        );
    }
	
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function count_unread_messages_parameters() {
        return new external_function_parameters(
                array(
					'userid' => new external_value(PARAM_TEXT, 'Will return a count of messages', VALUE_DEFAULT, '')
				)
        );
    }

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function count_unread_and_courses_messages_parameters() {
        return new external_function_parameters(
                array(
					'userid' => new external_value(PARAM_TEXT, 'Will return a count of unread messages and courses', VALUE_DEFAULT, '')
				)
        );
    }
	
    /**
     * Returns unread messages count and courses
     * @return array
     */
    public static function count_unread_and_courses_messages($userid = '0') {
        global $DB, $USER;
		
        //Parameter validation
        //REQUIRED
        $params = self::validate_parameters(self::count_unread_messages_parameters(),
                array('userid' => $userid));

        //Context validation
        //OPTIONAL but in most web service it should present
        $context = get_context_instance(CONTEXT_USER, $USER->id);
        self::validate_context($context);

        //Capability checking
        //OPTIONAL but in most web service it should present
        if (!has_capability('moodle/user:viewdetails', $context)) {
            throw new moodle_exception('cannotviewprofile');
        }		
		$messagecount = $DB->count_records_select('message','useridto='.(int)$userid);
		$courses = enrol_get_users_courses($params['userid'], true, 'id, shortname, fullname, idnumber, visible');
		$result = array(
			'messagecount' => $messagecount,
			'courses' => array()
		);
		
		foreach ($courses as $course) {
		$context = context_course::instance($course->id, IGNORE_MISSING);
		try {
			self::validate_context($context);
		} catch (Exception $e) {
			// current user can not access this course, sorry we can not disclose who is enrolled in this course!
			continue;
		}
		if ($userid != $USER->id and !has_capability('moodle/course:viewparticipants', $context)) {
			// we need capability to view participants
			continue;
		}
		list($enrolledsqlselect, $enrolledparams) = get_enrolled_sql($context);
			$enrolledsql = "SELECT COUNT('x') FROM ($enrolledsqlselect) enrolleduserids";
			$enrolledusercount = $DB->count_records_sql($enrolledsql, $enrolledparams);
			$result['courses'][] = array('id'=>$course->id, 'shortname'=>$course->shortname, 'fullname'=>$course->fullname, 'idnumber'=>$course->idnumber,'visible'=>$course->visible, 'enrolledusercount'=>$enrolledusercount);
		}
		return $result;
    }
	
    /**
     * Returns unread messages count
     * @return array
     */
    public static function count_unread_messages($userid = '0') {
        global $DB, $USER;
		
        //Parameter validation
        //REQUIRED
        $params = self::validate_parameters(self::count_unread_messages_parameters(),
                array('userid' => $userid));

        //Context validation
        //OPTIONAL but in most web service it should present
        $context = get_context_instance(CONTEXT_USER, $USER->id);
        self::validate_context($context);

        //Capability checking
        //OPTIONAL but in most web service it should present
        if (!has_capability('moodle/user:viewdetails', $context)) {
            throw new moodle_exception('cannotviewprofile');
        }
		
		$messagecount = $DB->count_records_select('message','useridto='.(int)$userid);
		
		return json_encode($messagecount);
    }
	
    /**
     * Returns read messages count
     * @return array
     */
    public static function count_read_messages($userid = '0') {
        global $DB, $USER;
		
        //Parameter validation
        //REQUIRED
        $params = self::validate_parameters(self::count_read_messages_parameters(),
                array('userid' => $userid));

        //Context validation
        //OPTIONAL but in most web service it should present
        $context = get_context_instance(CONTEXT_USER, $USER->id);
        self::validate_context($context);

        //Capability checking
        //OPTIONAL but in most web service it should present
        if (!has_capability('moodle/user:viewdetails', $context)) {
            throw new moodle_exception('cannotviewprofile');
        }
		
		$messagecount = $DB->count_records_select('message_read','useridto='.(int)$userid);
		
		return json_encode($messagecount);
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function count_read_messages_returns() {
        return new external_value(PARAM_INT, 'return a count of messages');
    }
	
    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function count_unread_messages_returns() {
        return new external_value(PARAM_INT, 'return a count of messages');
    }
	
    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function count_unread_and_courses_messages_returns() {
		return new external_function_parameters(
			array(
				'messagecount' => new external_value(PARAM_INT, 'message count'),
				'courses' => new external_multiple_structure(
					new external_single_structure(
						array(
							'id' => new external_value(PARAM_INT, 'id of course'),
							'shortname' => new external_value(PARAM_RAW, 'short name of course'),
							'fullname' => new external_value(PARAM_RAW, 'long name of course'),
							'idnumber' => new external_value(PARAM_RAW, 'id number of course'),
							'visible' => new external_value(PARAM_INT, '1 means visible, 0 means hidden course'),
						)
					), 'returns the courses and unread messages', VALUE_DEFAULT, array()
				),
			)
		);
    }
}
