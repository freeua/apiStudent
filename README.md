TEST STUDENT REST API 
==========

Requirements
----------
1. Build a simple REST API endpoint for creating/listing record for the students
2. Create a student record endpoint which accepts Name, Email and Phone Number. All 3 fields are required , need basic
validation for email and phone number.
3. List students endpoint will return all registered students details in JSON format.
4. List student endpoint will return single user detail in JSON format.
5. You are allowed to use any framework or library, to store data you can choose your favourite database or simple file based
storage.
6. Nice to have PHP 7+
7. No need to build HTML form or any other browser side.
8. Please upload all work in to GitHub with all instructions and send us GitHub url to review code.
9. Tested code is a huge plus point

Installation
-----------
1. Clone this repository
        
        $ git clone https://github.com/freeua/apiStudent
        $ cd apiStudent
        
2. Install package

        $ composer install
        
3. Enter parameters

        database_host: 127.0.0.1
        database_port: null
        database_name: db_name
        database_user: db_user
        database_password: db_password
        mailer_transport: smtp
        mailer_host: 127.0.0.1
        mailer_user: null
        mailer_password: null
        secret: 7495c691ff7f6ebb8a64d535ce0f10b637ac58a1
 
 4. Create DB
 
        $ php bin/console doctrine:database:create
        
 5. Create DB Schema
    
        $ php bin/console doctrine:schema:update --force
        
 6. Run server. Just execute this command to run the built-in web server and access the application in your browser at [http://localhost:8000](http://localhost:8000):
        
        $ php bin/console server:run   
        
 Documentation
 --------
 See methods after starting the web server. [http://localhost:8000/doc](http://localhost:8000/doc)                                