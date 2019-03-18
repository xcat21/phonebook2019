# phonebook2019

This is a lightweigh REST API for Phonebook made on PHP Phalcon framework

## Project features

* Phonebook REST API small PHP project on Phalcon with some composer packages
* Basic MySQL database with migrations control by typical phantom migration tool
* Table MySQL indexes for name fields 
* Service layer pattern and Micro application type with DI usage 
* HTTP connection to 3rd party API via Guzzle with asynchronous requests
* CRUD connected ORM
* Exception chain handling including custom service exceptions
* Pagination with total result, LIMIT and OFFSET parameters
* Searching parts of the names including spaces
* Proper OOP structure  
* Phantom Logger support with INFO and ERROR message types
* Location tag support in response header on create item
* Security input values sanitization
* Container validation approach – all validation errors come with response, not only the first one
* PSR auto checker with custom rules config file
* Full API suite TEST coverage (DB results included)
* Cache of external API requests via REDIS
* Vagrant ready-to-use box with all the provisioning scripts inside

## API description (version 01)

Also available in PDF format in /docs folder

All the requests and responses use JSON format. API version is included into URL (v1) as an example in this exercise. In reality, combined method is the best practice: Major version of API in URL and Minor version in HTTP header. Without version control, it is complicated to upgrade API version, as it is impossible to force clients to upgrade their API version in one moment. According to app structure, it is easy to add other CRUD entity to API instead of “phonebook”, for example “user” or “blog” etc.

## Requirements

Tested on: 
#### VirtualBox 5.2.22
#### Vagrant 2.2.1 
#### Vagrant plugins: vagrant-hostmanager 1.8.9 and vagrant-vbguest 0.16.0
#### GitHub token

## How to use

* Check virtual box and vagrant with plugins are available.
* Clone repository from https://github.com/xcat21/phonebook2019 to any folder on your host.
* Set up your correct GitHub token in **/vagrant/config/vagrant-local** (create config from *.example* files) or prepare your token to be pasted to console during provision script implementation.
* Run **_vagrant up_** command from the root of the folder.
* Relax until Vagrant arranges all the stuff with box download, libs install and project configure.
* On successful implementation of the vagrant scripts switch to **/app** folder (just **_app_** or **_cd /app_** command in console) and check it contains your host folder mirror with **_ls_** command.
* Run **_phalcon migration run_** from the **/app** root to set up database structure.
* **Enjoy :)**
* PSR code checker is available as **_pcf_** alias. Run **_pcf --diff --dry-run -v fix_** to see any PSR problems. To avoid getting data from cache clean the file **.psr/psr_cs.cache**. Remove **_--dry-run_** to make real changes in the files.
* Tests are available under **_codecept_** alias. Run **_codecept api run_** to perform all the API tests. Use **_--report_** options to generate separate tests reports in **tests/_output** folder.
* Check nginx logs in **/vagrant/nginx/logs** in case of any app problem on WEB server level.

Use container as a usuall dev web-server on Vagrant env.



## Project structure

- .phalcon/ - special Phalcon folder, contains migration history for example
- .psr/ - special PHP CS fixer folder, contains file cache of fix results
- .vagrant/ - special vagrant folder to keep VN settings
- app/ - sources of application – main structure
- public/ - public end-point for application as index.php
- tests/ - tests folder
- vagrant/ - vagrant settings and logs folder
- vendor/ - external bundles folder used by Composer 

Root contains following files along with composer.json, .gitignore etc.:
- .php_cs – file of PSR rules pattern to fix code. I used Symphony profile with some extra options. See file.
- codeception.yml -   main codeception configuration file.

App structure is:
- config/ - contains files with configuration and DI, loaders, routes etc. (config, DI, loader, routes)
- controllers/ - contains controllers based of Phalcon Micro pattern which I used and Exception controllers
- logs/ - folder for logging
- migrations/ - folder for Phalcon migrations
- models/ - contains models of the app
- services/- contains Services on special service layer of application

App tests are placed in /tests/api suite.

### Methods description

#### Get item info by ID

##### GET /v1/phonebook/:id
Retrieves phonebook item by Primary Key (integer: id) 

Response:
```json
{
    "id": "4",
    "fName": "Moff Kohl",
    "lName": "Seerdon",
    "phone": "+44 333 265786344",
    "countryCode": "SC",
    "timeZone": "America/Denver",
    "insertedOn": "2019-03-11 10:43:00",
    "updatedOn": "2019-03-15 15:20:00"
}
```
Possible results codes:

Code| Description
--- | ---
200 | (Ок)	
204 | (No content)	Resource is good but item with ID does not exist
404 | (Not Found)	Resource is not found or request is incorrect

#### Get item list with pagination

##### GET /v1/phonebook?limit=:limit&offset=:offset

Retrieves phonebook items. Number of records is controlled by LIMIT (positive integer: limit) value and the start record to get is controlled by OFFSET (positive integer with zero: offset) parameter. OFFSET returns human-readable count approach, meaning that OFFSET=0, empty OFFSET and OFFSET=1 will return records starting from the first one in dataset. OFFSET=2 means that response starts with the second record.

Response:
```json
[{
        "id": "3",
        "fName": "Han",
        "lName": "Solo",
        "phone": "+02 144 265555890",
        "countryCode": "JM",
        "timeZone": "Europe/Bucharest",
        "insertedOn": "2019-03-15 12:43:00",
        "updatedOn": "2019-03-15 18:40:00"
}, {}, {}]
```

Possible results codes:

Code| Description
--- | ---
200 |(Ок)	
204 |(No content)	Empty set
404 |(Not found)	Resource is not found or request is incorrect
400 |(Bad request)	Incorrect limit or offset values

#### Get item list by search in parts of the name

##### GET /v1/phonebook/search/?name=:key

Retrieves phonebook items searched by key NAME (string: key). First name and Last name fields are involved.

Response:
```json
[{
        "id": "3",
        "fName": "Han",
        "lName": "Solo",
        "phone": "+02 144 265555890",
        "countryCode": "JM",
        "timeZone": "Europe/Bucharest",
        "insertedOn": "2019-03-15 12:43:00",
        "updatedOn": "2019-03-15 18:40:00"
}, {}, {}]
```
Possible results codes:

Code| Description
--- | ---
200 |(Ок)	
204 |(No content)	Empty set
404 |(Not found)	Resource is not found or request is incorrect

#### Create new item

##### POST /v1/phonebook

Creates new item in phonebook based on values provided: firstName (string : 1-60 chars), lastName (string: 1-60 chars), phoneNumber (format: +XX XXX XXXXXXXXX digits), countryCode (string: 2 chars format, external validation), timeZone (string: 3-40 chars long, external validation). 


Request body:

```json
{
"firstName": "Padme", 
"lastName": "Amidala", 
"phoneNumber": "+20 123 456777888",
"countryCode": "GU",
"timeZone" : "Moscow/Europe",
}
```

Validation:

* Phone number is required + mask validation with +XX XXX XXXXXXXXX
* Country code validation with external API
* Time Zone validation with external API
* First name is required + length 1-60 chars
* Last name length 1-60 chars

Response:

"Location: http ://api.phonebook.loc:8000/v1/phonebook/:id" in Response header where :id is a new record ID.

Possible results code:

Code| Description
--- | ---
201 |(Created)	Created successfully
400 |(Bad Request)	Wrong parameters or validation error
404 |(Not found)	Resource is not found or request incorrect

#### Update item by ID
 
##### PUT /v1/phonebook/:id

Updates phonebook item by ID based on values provided: firstName (string: 1-60 chars), lastName (string: 1-60 chars), phoneNumber (format: +XX XXX XXXXXXXXX digits), countryCode (string: 2 chars format, external validation), timeZone (string: 3-40 chars long, external validation).  The fields not to be updated can be skipped. Missed values are taken from previous values on update.

Request:
```json
{
"firstName": "Padme", 
"lastName": "Amidala", 
}
```

Validation:

* Phone number mask validation with +XX XXX XXXXXXXXX
* Country code validation with external API
* Time Zone validation with external API
* First name length 1-60 chars
* Last name length 1-60 chars


Response:
```json
{
}
```

Possible results codes:

Code| Description
--- | ---
204 |(No content)	Record is successfully updated
400 |(Bad Request)	Wrong parameters or validation error
422 |(Unprocessable entity)	Record is not found


#### Delete item by ID 

##### DELETE /v1/phonebook/:id

Delete phonebook by ID provided. (integer: id)

Response:

```json
{
}
```
Possible results codes:

Code| Description
--- | ---
200 | (Ок)	Record is successfully deleted
422 |(Unprocessable entity)	Record is not found
