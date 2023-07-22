## Docker Setup
## Requirements
*Git
*Docker [>=19.03.0]

## Installation

## Setup for Single Project - Already have a PHP project:

1 - Clone laradock on your project root directory:

    git submodule add https://github.com/Laradock/laradock.git

Note: If you are not using Git yet for your project, you can use git clone instead of git submodule.

To keep track of your Laradock changes, between your projects and also keep Laradock updated check these docs

2 - Make sure your folder structure should look like this:

* project-a
* laradock-a
* project-b
* laradock-b

3 - Go to the Usage section.

## Setup for Single Project - Don’t have a PHP project yet:

1 - Clone this repository anywhere on your machine:

    git clone https://github.com/laradock/laradock.git

Your folder structure should look like this:

* laradock
* project-z

2 - Edit your web server sites configuration.

We’ll need to do step 1 of the Usage section now to make this happen.

cp .env.example .env
At the top, change the APP_CODE_PATH_HOST variable to your project path.

    APP_CODE_PATH_HOST=../project-z/
Make sure to replace project-z with your project folder name.

3 - Go to the Usage section.

## Usage

Read Before starting:

If you are using Docker Toolbox (VM), do one of the following:

>Upgrade to Docker Desktop for Mac/Windows (Recommended). 
>Use Laradock v3.*. Visit the Laradock-ToolBox branch. (outdated)

## Server setup configs

Change from laradock's `.env` file; `PHP_FPM_INSTALL_EXIF=true`


## Data merging guide

from server: 

	export:
	-- customers
	-- products
	-- product_categories

from local:

	-- truncate table customers restart identity cascade
	-- truncate table transactions restart identity cascade
	-- truncate table products restart identity cascade
	-- truncate table branch_employees restart identity cascade
	-- truncate table employees restart identity cascade

	-- drop table customers
	-- drop table branch_employees
	-- drop table employees
	-- drop table products
	-- drop table product_categories

export local

## Some deeper modifications are noted by `// --- C U S T O M ---`
