Web service plugin for Moodle 2.X
------------------------------------------

Returns the count of unread/read messages in moodle

To test:
1- install the plugin in moodle/local/moodlecount/
2- Read, setup and run moodle/local/moodlecount/client/client.php

## Installation

You can install via composer or copy and paste the files. The module
lives in the **/local/** folder. 

### Install via composer

First navigate to your moodle directory:

```cd /var/www/moodle-folder```.

Add the module to your composer list:
```composer require otago/moodlecount:dev-master --no-update```

Update composer: 
```composer update --no-dev```

Note if you're installing on a dev environment you'll omit the --no-dev

### Usage

After installation, you'll need to update the moodle database with your 
module. Simply login as an admin and moodle should prompt you.

Next you'll need to add the functions to the list of functions to your web service.
Navigate to *Site administration > Plugins > Web services > External services*. 
Select your web service you want (if you have not created a role, user and service 
for web services, google it) to add your functions to, and click 'Add functions'. 

Then select **local_op_wstemplate_count_read_messages** or **local_op_wstemplate_count_unread_messages** 
from the list.
