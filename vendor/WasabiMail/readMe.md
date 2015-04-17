wasabiMail Module Configuration
=======================================================
The Mail Module is configured as a service and registered to the service manager.
You have 3 types for configuration: local, develop and production.
It is supposed that you do not have a mail server on your local machine. So the mails will be saved as text 
in wasabiMail/localMails. You can change the folder in the config.php

In development mode it is supposed that you have access to a mail server. 
In production mode Sendmail will be used.
You can change this behavior in the Module.php if necessary.

 "env" => array(
        "type" => "local"
    )


