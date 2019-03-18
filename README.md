# phonebook2019

## API description (version 01)

All the requests and responses use JSON format. API version is included into URL (v1) as an example in this exercise. In reality, combined method is the best practice: Major version of API in URL and Minor version in HTTP header. Without version control, it is complicated to upgrade API version, as it is impossible to force clients to upgrade their API version in one moment. According to app structure, it is easy to add other CRUD entity to API instead of “phonebook”, for example “user” or “blog” etc.

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
