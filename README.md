## Kumu Backend Assesstment

This project is developed using Laravel Framework 8.5. The goal of this project to create an API end point that will request list of usernames from github and retrieved the users data.

For Laravel framework [documentation](https://laravel.com/docs).
## System Requirement 
```
REDIS
MySql
PHP : "^7.3|^8.0",
```

## Installation 
```
# Clone Project from Git Repository
- git clone git@github.com:jarabino/kumu-php-api.git

# Install Package using composer
- composer install

# Make a copy of .env.example to .env
- cp  .env.example .env

# Create Database
CREATE DATABASE {Database_name};

# Add database and user to env

# Migrate database
- php artisan migrate

```

## API Endpoints
| Request Type | URI | Description|
| ------ | ------ | ------ |
| POST | /api/login | User authentication - retrieve API token |
| POST | /api/register | User registration|
| POST | /api/github/users | username(s) can be separated by comma 

## Curl
Login Endpoint
```bash
curl --request POST \
  --url {BASE_URL}/api/login \
  --header 'Content-Type: application/json' \
  --data '{
    "email": "test@mail.com",
    "password": "password"
}'
```

Register User
```bash
curl --request POST \
  --url {BASE_URL}/api/register \
  --header 'Content-Type: application/json' \
  --data '{
    "name" : "name",
		"email": "test@mail.com",
    "password": "password"
}'
```
Github Users
```bash
curl --request POST \
  --url {BASE_URL}/api/github/users \
  --header 'Accept: application/json' \
  --header 'Authorization: Bearer {TOKEN}' \
  --header 'Content-Type: application/json' \
  --data '{
    "names": "jarabino,mojombo,defunkt"
}'
```
### Premium Partners

