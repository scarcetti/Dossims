## Server setup configs

Change from laradock's `.env` file; `PHP_FPM_INSTALL_EXIF=true`


## Data merging guide

from server: customers products product_categories

from local:
	-- truncate table customers restart identity cascade
	-- truncate table products restart identity cascade
	-- truncate table transactions restart identity cascade

	-- drop table customers
	-- drop table products
	-- drop table product_categories

export local