GANTRY v1.0
=====================

support website: not available

GANTRY is a PHP web-powered purpose-specific file management system, It is designed to help web-masters detect changed files given the last modified date of the project

- User interface designed using Bootstrap
- Certain functions presently depend on Ajax calls, This implies
  that javascript must be available in your browser while using Gantry
	- such functions as "copy" (the most essential)
		==copy allows you to copy files of interest along side the folder structures. Only selected files will be copied, be sure to select appropriately. The copied folders and accompnying files will be stored in Gantry's storage folder.


PROBLEM DISCOVERY
==============================
We were working on a large project, we were using Laravel. The team had made several commits. I needed to transfer all files to an online server to allow the UAT department assess and report to the project director.

I have done this over and over without issues. Simply schedule all files for copying check back a few times attend to failed uploads.
But on this fateful day, i was so late for launch. I asked myself if there was a way to ease this process. And that was how i decided to develop this tool. Its a sparetime project, so i convinced myself to do it later.

After using it a few times, i realised what else it could do for me. I would like to hear your views.

summary: i can copy only changed parts of a specific project, which means the upload size is smaller. many would argue that its the worst way. well, its been good for me and all teams aren't the same.



GLOSSARY
============================
project herein and after refers to a specific folder we wish to audit using Gantry.

To audit means to check files that have changed after a specific date and time (date and time hereafter referred to as modification benchmark).

while specifying modification benchmark via the form, time is optional but date is required. whenever time is not specified it defaults to 00:00:00 (i.e midnight of date benchmark);



USAGE
=======================================
Gantry can run as an independent web project as long as it has access to file system of your project.
take for example the file system on a local webserver.
	- C:/ (root)
		-laragon
	 		-www
		  		-gantry(as a standalone project)
		  		-project(another standalone project)
		  		-wordpress

By default Gantry would store all copied projects in
	-gantry/gantry
You can change this location in the gantry.config file on line 7
line 7 defines a constant called _GANTRY.
Note: Gantry must be able to access this location via file system.


Gantry can also run as a sub project within a project of interest.
line 6 defines a constant called _APP_ALONE.
set this to false if you run Gantry as a sub folder of any project.
for now this constant does not affect anything whether set to true or false. But for future compatibility, use the rule.

take for example the file structure of a localserver
	- C:/ (root)
		-laragon
	 		-www
		  		-project(another standalone project)
		  			-gantry(as a standalone project)
		  				-gantry(this is where copied projects will be stored)
		  		-wordpress

in this case you want to run Gantry as a sub-project. Everything works as stated before.



RUNNING GANTRY
=================================
when you access Gantry via
	url: localhost:8080/gantry (as a standalone)
	url: localhost:8080/project/gantry (as a sub project)

You will be welcomed with a form and a empty table.
- The form has a required field called folder. enter into it the path to the directory of the project of interest.
- The form also accepts Exception patterns which is optional. exception patterns is a set of comma seperated values. each value represents a path to a file or a directory you would like to exempt in the displayed results.
- The form also contains a datetime field which defaults to 1 hour ago. The field is called Modification benchmark. the format is (d-m-Y H:i:s).time is optional, time defaults to midnight of specified date.

After filling this data, you can click the submit button.
the table gets populated if the project exists and files meeting the constraints also exists.

Notice the form uses a GET request for now, You can change to POST but this may alter _GANTRY's behaviour badly. You can use the _GANTRY link on to to revert to the plain index.

WHAT NEXT?
==============================
Now that the files have been listed what next?
You can copy selected files using the button below the list.

The files copied can be pasted into a previous version of your project as an update. that explains why we copy all the folder structres too.


Thanks for viewing
send wishlist and complaints to 
mailto: adetunjiconnect@gmail.com
github: kidstell

with all the love in the world.