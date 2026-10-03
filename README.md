# Financial Web App



FinSimp is a financial web app for personal and small business use scenarios. The aim of this project is to make the process of accounting meaningfull, simple, and accurate. FinSimp is not a full featured all-in-one business solution. 

This app was hand-sliced, diced, and served from the [W3CSS](https://www.w3schools.com) Analytics template using PHP 8.1 and MariaDB 10 server side. [ChartJS](https://chartjs.org) renders the charts. The financial.sql file is complete but does not include example data. 

PHP SQL coding needs fine-tuning against injection vulnerability before production environments.

This is a fully functioning web app but also a work in progress. Improvements such as new features, cleaner code, and better file/dir organization will follow...

## Features

* Charts, graphs, and cell displays will not appear until table entries are recorded under the related accounts.
* Table entries are sortable, searchable, and editable
* Each budget is tracked against the associated expense account
* Users can register and log in to access their separate account
* Financial ratios and cell displays provide a quick overview of financial health

Future features
* More detailed bar charts
* Financial portfolio section and pie chart
* Summary sheets - balance sheet, cash flows, etc
* Month and Year picker 

Any number of Accounts can be created. When creating a new Account, select the appropriate category (i.e., Asset, Liability, Income, Expense, Equity).

## Requirements - May or may not work on earlier versions of apps
* Apache or Nginx
* PHP 7.4 +
* MariaDB 10 + 

## Installation

No fancy package managers are needed here. Just create the "finsmip" database and then import the finsmip.sql file into your database, then edit the config.php file for your database variables. 

Create a user and assign a password for the database. Be sure to change the user and password for your system.

(MySQL/MariaDB)

Login to your server and run the code below or use phpMyAdmin instead:

    sudo mysql
Then
    
    CREATE DATABASE finsimp; 
    GRANT ALL PRIVILEGES ON finsimp.* to 'user'@'localhost' IDENTIFIED BY 'password';
    FLUSH PRIVILEGES;
    EXIT;
Next, import the finsimp.sql file

    sudo mysql finsimp < finsimp.sql


## Usage

Transactions are recorded for each account as either a positive or negative number (100.56 or -100.56). A double-entry system was intentionally avoided to save time and sanity. All Expense transactions are recorded under Expense, and all Income transactions are recorded under Income, etc. Running balances, budgets, ratios, and financial reports are automatically calculated using charts and cell displays.



