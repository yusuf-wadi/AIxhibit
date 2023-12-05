# AIxhibit

This is a project for my 4347 Database Systems class at UTD.

## Description

The goal for this project was to create a database system that emulates an art exhibit, displaying an artists name and artwork upon request.

The twist here is that there are no invalid queries, as the code will generate the artists description and artwork if it does not exist in the database, then it will insert it into the database.

## Setup
          

Steps to follow are:

1.      Download and setup MySQL Server

2.      Download and setup MySQL Workbench

![A screenshot of a computer
Description automatically generated](file:///C:/Users/thewa/AppData/Local/Temp/msohtmlclip1/01/clip_image002.png)

3.      When in workbench, ensure that it sees your running SQL server (it should work by default)

![A screenshot of a computer
Description automatically generated](file:///C:/Users/thewa/AppData/Local/Temp/msohtmlclip1/01/clip_image004.png)

![A screenshot of a computer
Description automatically generated](file:///C:/Users/thewa/AppData/Local/Temp/msohtmlclip1/01/clip_image005.png)

4.      Create a database schema.

![](file:///C:/Users/thewa/AppData/Local/Temp/msohtmlclip1/01/clip_image006.png)

![](file:///C:/Users/thewa/AppData/Local/Temp/msohtmlclip1/01/clip_image007.png)

5.      Run the appropriate SQL commands in the query dialog of the workbench to instantiate the tables in the database.

Ø  See create_db_tabels.sql

6.      Right click on each table and import the appropriate ‘.csv’ data file.

![A screenshot of a computer
Description automatically generated](C:/Users/thewa/AppData/Local/Temp/msohtmlclip1/01/clip_image008.png)

7.      Connect to PHP using MySQLi import and connection syntax.

![A screenshot of a computer code
Description automatically generated](file:///C:/Users/thewa/AppData/Local/Temp/msohtmlclip1/01/clip_image009.png)

8.      Use provided logic when interacting with search mechanics of the project to ensure proper functionality for the project. Run `php -S localhost:8080` (with php installed on your system) in the project code directory to then serve the html and php files to localhost:8080.

![A screenshot of a computer program
Description automatically generated](file:///C:/Users/thewa/AppData/Local/Temp/msohtmlclip1/01/clip_image011.png)

9.      Now you should be up and running!