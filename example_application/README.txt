PHP MMS Decoder Example Application
===========================================================================
This example application was written as a complement to the PHP MMS Decoder
written by Jonatan Heyman in 2003 & 2004. It's only purpose is to demonstr-
ate how to use the MMS Decoder class, and therefore it is made to be simple.
It's also written in a rush, so everything isn't as good as I would like it
to be. 

Installation:
 - Upload all MMS Decoder's PHP-scripts to the webserver (the example_application expects to 
   find the mmsdecoder.php file in it's parent directory). 
 - Access the installation script over HTTP (install.php). Enter the correct information in
   the form, and run the script. Some webhosts only allow you to have one database, that is 
   why I have the "Use existing DB" option. 
 - Set the correct settings in the config.php file. 
 - You should now be ready to use the application.
 - It's highly recommended that you remove or rename the install.php script after you have 
   your database tables set up.


Current supports:
 - Recieve MMS, save them to a MySQL database, and send a m-send-conf, to
   confirm that the MMS was recieved succefully. 
 - List the MMS in the database. 
 - Download the MMS's parts. 

TODO:
 - Make the code nicer. 
 - Make the presentation nicer


Future versions are not planned. Fixes and contributions are accepted if I think they fit. 


Contact:
--------------------
http://heyman.info
jonatan [at] heyman.info
--------------------

===========================================================================