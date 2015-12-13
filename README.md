ZF2 Skeleton with integrated WasabiLib (Test)
=======================
##Please Visit http://www.wasabilib.org for detailed information.
Introduction
------------
This is a starting point for using the WasabiLib within a Zendframework skeleton application.

Installation
------------
    cd your/project/dir
    git clone https://github.com/WasabiLib/wasabilib_zf2_skeleton_application.git
    cd wasabilib_zf2_skeleton_application
    php composer.phar self-update
    php composer.phar install

Get Composer
----------------------------
To get composer please follow the instructions below.

    curl -s https://getcomposer.org/installer | php --

### Apache Setup

To setup apache, setup a virtual host to point to the public/ directory of the
project and you should be ready to go! It should look something like below:

    <VirtualHost *:80>
        ServerName yourproject.localhost
        DocumentRoot /path/to/your/project/public
        <Directory /path/to/your/project/public>
            DirectoryIndex index.php
            AllowOverride All
            Order allow,deny
            Allow from all
        </Directory>
    </VirtualHost>
