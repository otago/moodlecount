<?php
/**
 * Test script for count messages.
 *
 */

/// MOODLE ADMINISTRATION SETUP STEPS
// 1- Install the plugin
// 2- Enable web service advance feature (Admin > Advanced features)
// 3- Enable XMLRPC protocol (Admin > Plugins > Web services > Manage protocols)
// 4- Create a token for a specific user and for the service 'My service' (Admin > Plugins > Web services > Manage tokens)
// 5- Run this script directly from your browser. You should see a number of read/unread nodifications beloning to a user

/// SETUP - NEED TO BE CHANGED
$token = '965447f629632e4ca492c1cb3888f501';
$domainname = 'http://moodle';
$functionname = 'local_op_wstemplate_count_read_messages';
$functionname2 = 'local_op_wstemplate_count_unread_messages';
 
 
// REST RETURNED VALUES FORMAT
$restformat = 'json'; //Also possible in Moodle 2.2 and later: 'json'
                     //Setting it to 'json' will fail all calls on earlier Moodle version
 
$params = array(
    'userid' => '4'
);
/// REST CALL
header('Content-Type: text/plain');
require_once('./curl.php');
$restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';

/// REST CALL
echo "local_op_wstemplate_count_unread_messages\n";
$serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname2;
$curl = new curl;
$resp = $curl->post($serverurl . $restformat, $params);
print_r($resp);
echo "\n";

echo "local_op_wstemplate_count_read_messages\n";
$serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;
$resp = $curl->post($serverurl . $restformat, $params);
print_r($resp);
echo "\n";
