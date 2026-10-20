webENGINE: A High-Performance MVC/S Web Server / Web Engine / 'Framework' for Web Applications
------------
*Copyright (c) 2024 - 2027 Kent Thompson, All rights reserved.*

***Features***
+ Convention Over Configuration
+ MVC/S Architecture
+ Object Oriented, High Performance, Efficient Algorithms
+ Automatic Responsive Web Design. Same code base works, from a phone to a desktop
+ Automatic Engine Level Class Auto-Loader, Incredibly Fast, Only Loads What's Needed for Each Request
+ Automatic Auto-Routing!
+ Automatic Inversion of Control
+ Automatic Authentication, Compliant through Unbroken PHP Encryption / Passwords
+ Automatic Authorization, Compliant through JsonWebTokens
+ Automatic Working Design and Code Upon Installation
+ Separation of Concerns is Emphasized
+ Views - Section / Page Composition is Encouraged, But Not Mandatory. Emphasizes DRY Principle
+ Core Engine handles all Authorization / Authentication, File Loading, Routing and Invoking of correct code
+ Need only code 'Industry Standard' Models / Views / Controllers. Use industry standard API Endpoint paths
+ Service layer supported; business logic, validation, error handling, et cetera
+ Pages and API Endpoints can be Authorized, or Not, depending on developer needs
+ All Modern Object-Oriented Code and Design
+ Easy to use Efficient Generalized Error Handling Design
+ Multiple databases can be supported
+ Easily Extensible
+ JsonWebTokens Provide Excellent, Distributed Security Without the Need for Sessions, Cookies and Mulitple Database Lookups
+ Easy examples provided. More...  
------------

Some pages in this example are placeholders to show above behavior.  
**Notice:** "*Partial Views*" and logic are used to accommodate real-time UI Composition following DRY principles. Thus, the online github code viewer will occasionally show html tags in red, indicating an error? THERE IS NO ERROR. Upon loading the full code at run-time, all tags are correctly matched.

The "Engine" is located at /app/core/Application.  
**Engine Performance**  
Care was taken to ensure the current instance request / response "execution path" goes through Exactly ONE Path each time. IOW, each variable is set or read, exactly ONCE and no more. It is the most effcient possible. 

**Userlist** shows "mini-SPA" design and typical CRUD API behavior.

Endpoint URL Path Design
--
Server-side Rendered Pages URL End-Point Convention:  
**Website name / controller / action**;

Example:  
kentthompson.org/user/getUser

+ Actions are mapped to a method / function in the controller class.
+ Automaticaly supports a Default, Home Controller Design when used with only a website name and / or just a page name request.

------------

REST / RESTful AJAX based API path scheme.  API (api) indicates use of the API controllers and JSON return type.  
**Website name  / api / controller / action**;

Example:  
kentthompson.org/api/user/getUser

Actions are mapped to a method / function in the controller class.  

Requirements
----
+ PHP 8.x

+ Optional:
SQL or NoSQL Database  
  PDO Interface is provided, any supporting DB will work, setup in app/core/Database and app/model

+ Recommended:
Datatables.js, jQuery.js, Bootstrap, "HTML Tag Error Checker" Browser Extension

Authentication and Authorization
----
Built in behavior is located in a PHP Trait called ***Authorize.php***. Controllers, and any class needing it should add the following line:
 >use \App\traits\Authorize;

Authentication: Handled by the PHP Password Hashing Functions

Authorization (as opposed to Authentication)  
JsonWebTokens are used for these features.  
It is not needlessly "enforced."  
If Authorization is desired, in Server side controllers, use '**$this->AuthUI();**' for webpages and '**$this->AuthApi()**' for API calls. They return a boolean.
 See /app/controller/home and app/api/user for examples.

Authentication Before Login:
+ GET and POST do not use Authentication or Authorization becuase a JsonWebToken (JWT) has not been issued. This is by design. Static pages, for example, usually do not need to be authorized as well, but can be if desired, with one line of code.

Login: (Authentication)
+ One has to register as a user first, of course.
+ Once Authenticated (logged in) a JsonWebToken is issued and installed in the client browser.
+ Logging in (/app/api/user/login) shows how JWTs are initially sent and placed in  client side SessionStorage, (/app/views/login). This has to be done.

After Login:
+ The presence of the JWT indicates to the engine that the user is "logged in."
+ A JasonWebToken (JWT) is passed to the server for Authrorization for each request, as required.
+ GET and POST requests should send the client side stored JWT, and the Engine expects, a JWT, if authorization is desired.

Request (Get / Post) based "Routing"
----
No Need For Seperate Methods for Each Http / REST Verb. Employs the DRY principle.  
Inside a controller mapped method use this technique:
```c++
    if( $this->requestType === GET ) {
        $id = $_GET['id'];
        $uname = $_GET['uname'];
    }

    if( $this->requestType === POST ) {
        $id = $_POST['id'];
        $uname = $_POST['uname'];
    }
```
----

**Database:**  See sql directory for database schemas. See /app/core/Database to set up database connection, et cetera.

.htaccess
----

Options -Indexes  
RewriteEngine On  
RewriteCond %{HTTP_USER_AGENT} ^pycurl [NC,OR]  
RewriteCond %{HTTP_USER_AGENT} ^Wget [NC]  
RewriteCond %{HTTPS} off  
RewriteRule ^(.*)$ https://YOUR_WEB_ADDRESS.COM/$1 [L, R=301]  

RewriteBase /  
RewriteCond %{REQUEST_FILENAME} !-f  
RewriteCond %{REQUEST_FILENAME} !-d  
RewriteCond $1 !^(index\.php|public|css|js|robots\.txt)  
RewriteRule ^(.*)$ index.php/params=$1 [L,QSA]  

Added
----

+ Greatly Improved Error Handling and created a "entry point" in the Service Layer. ErrorHandler.php.  
This file represents an "entry point" to gracefully handle and report errors and exceptions.  
Could go on to LOG errors, display various page types 404, 500 et cetra, and make them end-user 'pretty.'  
Whatever the Problem Domains dictates. It can spring from here.

Known Issues
----

Finish full coverage of new error handler code

---

Copyright (c) 2024 - 2027 Kent Thompson, All rights reserved.
https://github.com/kent-thompson/webMVC.git
