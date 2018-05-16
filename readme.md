# Homework

We are moving to the brand new series [How to Manage an Open Source Project](https://laracasts.com/series/how-to-manage-an-open-source-project)!

This is an open source forum that was built and maintained at Laracasts.com.

## Installation

### Step 1.

> To run this project, you must have PHP 7 installed as a prerequisite.

Begin by cloning this repository to your machine, and installing all Composer dependencies.

```bash
git clone git@github.com:yxj0312/Homework.git
cd homework && composer install
php artisan key:generate
mv .env.example .env
```

### Step 2.

Next, create a new database and reference its name and username/password within the project's `.env` file. In the example below, we've named the database, "homework."

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homework
DB_USERNAME=root
DB_PASSWORD=
```

ToDo


