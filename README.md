Unnamed Simple File Browser
============
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/SnoUweR/PHP-File-Browser/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/SnoUweR/PHP-File-Browser/?branch=master)

Unnamed Simple File Browser (what an incredible name, isn't it?) is a file browser written in PHP, JavaScript and CSS. It displays the list of files and folders in a folder.

* Sorting by name, size and editing time
* Able to move in folders
* Thumbnails for images.
* Admin panel with file uploading
* Responsive interface (Bootstrap, yeah)

The main problem is the quality of the code. I know it's very bad to mixing logic and interface,  VanillaJS and jQuery.
I will try to correct quality code with future commits.

[Demo](http://stuff.snouwer.ru)

Installation
------------
1. Clone this repo to web site folder
2. Execute init.sql from sql folder
3. Edit config.php with your MySQL database settings
4. Edit default files folder in admin (example.com/admin/)
5. That's it!

Additional Information
----------------------

Unnamed Simple File Browser is still in beta, so there may be some errors.

Licensed under GPLv2.

Author: Vladislav Kovalev.

Some ideas was taken from Encode Explorer.

Used libraries: 

* simpleDB by DeusModus
* Bootstrap
* Sortable by Stuart Langridge 
* jQuery
* jQuery Tagsinput plugin by XOXCO
* Bootbox