## Requirements
-Git
-Docker [>=19.03.0]

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
