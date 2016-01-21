[WasabiLib](http://www.wasabilib.org) Skeleton Application
=======================
##Please Visit http://www.wasabilib.org for detailed information.

Introduction
------------
This is a starting point for using the WasabiLib Modules and ZF2.

#Following Modules are bundled
1. Zend Framework 2.5*
2. [WasabiLib](https://github.com/WasabiLib/wasabilib) (Basic ajax components, Server-side modals, Gritter messages, Suggest, Wizard) 
3. [WasabiMail](https://github.com/WasabiLib/Mail) (enhanced ZF2 based Email Module with a responsive template and file attachment support)
4. [Bootstrap](http://getbootstrap.com/)
5. [FontAwesome](https://fortawesome.github.io/Font-Awesome/) 
6. [JQuery 2.1.4](https://jquery.com/)



#Installation

##Get Composer

To get composer please follow the instructions below.

    curl -s https://getcomposer.org/installer | php --
    cd your/project/dir
    
##Clone Repository    
    git clone https://github.com/WasabiLib/wasabilib_zf2_skeleton_application.git
    cd wasabilib_zf2_skeleton_application
    composer install

##Setup Apache Virtual Host

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
            <IfModule mod_authz_core.c>
                Require all granted
            </IfModule>
        </Directory>
    </VirtualHost>

Make sure mod_rewrite is active