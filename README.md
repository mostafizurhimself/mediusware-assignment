## Getting started

Clone the repository

    git clone git@github.com:MdMostaFizurRahaman/mediusware-assignment.git

Switch to the repo folder

    cd mediusware-assignment

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate


Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Or import the database from root folder [med_question.sql](https://github.com/MdMostaFizurRahaman/mediusware-assignment/blob/master/med_question.sql).

    php artisan serve

You can now access the server at http://localhost:8000



**Make sure you set the correct database connection information before running the migrations** 

    php artisan migrate
    php artisan serve
