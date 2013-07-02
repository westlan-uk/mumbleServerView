mumbleServerView
================

A very small PHP/JSON app that shows a list of users connected to a Mumble server, based on MumPI.

The MumPI project intends to be a fully featured admin tool, with viewers included. It is maintained
on GitHub: https://github.com/Kissaki/MumPI . This project is based on MumPI and will follow its updates,
but the code is intended to be much smaller, a read only viewer and an API for people to easily build
their own viewers out of JavaScript, CSS and HTML5.

Homepage: https://github.com/jamesread/mumbleServerView

| Screenshots
|:------------
| ![An example of mumbleServerView being embedded in to a web page](https://github.com/jamesread/mumbleServerView/raw/master/resources/images/screenshot.png "mumbleServerView") |  An example of mumbleServerView being embedded in to a web page 

Downloading
===
You can download mumbleServerView as a .ZIP file from GitHub.com.

https://github.com/jamesread/mumbleServerView/archive/master.zip

Issues
===
Issues (bugs, security issues, feature requests) are also very welcome. Please
use the issue tracker on GitHub.com.

If you need help installing, configuring or with further development, please 
raise an issue and label it with "question". You will need a GitHub.com user 
account for this. 


Contributing
===
The repository is open. Patches welcome, please fork and raise a pull request.

Installation
===
Simply create a settings.php file, using settings.default.php as a template. 

Your server needs the PHP Ice extension installed and loaded. Fedora and Debian 
both include this package in the most recent versions. On Fedora the package is 
called `php-ice`. 
