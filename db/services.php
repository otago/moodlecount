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

/**
 * Web service local plugin template external functions and service definitions.
 *
 * @package    localwstemplate
 * @copyright  2014 Torleif West
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// We defined the web service functions to install.
$functions = array(
        'local_op_wstemplate_count_read_messages' => array(
                'classname'   => 'local_op_wstemplate_external',
                'methodname'  => 'count_read_messages',
                'classpath'   => 'local/moodlecount/externallib.php',
                'description' => 'Returns a count of read messages belonging to a user',
                'type'        => 'read',
        ),
        'local_op_wstemplate_count_unread_messages' => array(
                'classname'   => 'local_op_wstemplate_external',
                'methodname'  => 'count_unread_messages',
                'classpath'   => 'local/moodlecount/externallib.php',
                'description' => 'Returns a count of unread belonging to a user',
                'type'        => 'read',
        ),
        'local_op_wstemplate_count_unread_messages_and_courses' => array(
                'classname'   => 'local_op_wstemplate_external',
                'methodname'  => 'count_unread_and_courses_messages',
                'classpath'   => 'local/moodlecount/externallib.php',
                'description' => 'Returns courses and a count of unread messages belonging to a user',
                'type'        => 'read',
        )
);

// We define the services to install as pre-build services. A pre-build service is not editable by administrator.
$services = array(
        'OP Service' => array(
                'functions' => array (
					'local_op_wstemplate_count_read_messages', 
					'local_op_wstemplate_count_unread_messages', 
					'local_op_wstemplate_count_unread_messages_and_courses'),
                'restrictedusers' => 0,
                'enabled'=>1,
        )
);
