PHP MMS Decoder Example Application
===========================================================================
This example application was written as a complement to the PHP MMS Decoder
written by Jonatan Heyman in 2003 & 2004. It's only purpose is to demonstr-
ate how to use the MMS Decoder class, and therefore it is made to be simple.
It's also written in a rush, so everything isn't as good as I would like it
to be. 

Installation:
 - Upload all PHP-scripts in hte example_application dir to the webserver. 
 - Access the installation script over HTTP. Enter the correct information in the form, 
   and runt the script. Some webhotells only allow you to have one database, that is why 
   I have the "Use existing DB" option. 
 - Set the correct settings in the config.php file. 
 - You should now be ready to use the application. Feel free to e-mail me with any questings.


Current supports:
 - Recieve MMS, save them to a MySQL database, and send a m-send-conf, to
   confirm that the MMS was recieved succefully. 
 - List the MMS in the database. 
 - Download the MMS's parts. 

TODO:
 - Make the code nicer. 
 - Make the presentation nicer


Future versions are planned. Fixes and contributions are accepted if I
think they fit. 


Contact:
--------------------
http://heyman.info
jonatan@heyman.info
--------------------

===========================================================================