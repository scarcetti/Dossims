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