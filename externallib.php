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
     * Returns welcome message
     * @return string welcome message
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
     * Returns welcome message
     * @return string welcome message
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
        return new external_value(PARAM_RAW, 'return a count of messages');
    }
	
    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function count_unread_messages_returns() {
        return new external_value(PARAM_RAW, 'return a count of messages');
    }



}
