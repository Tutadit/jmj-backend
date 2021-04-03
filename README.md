# Juan Mo' Journals Backend

For the backend implementation of juan mo journals, we are going to use [Laravel Framework](https://laravel.com/docs/8.x), backed with a MySQL service.

In order to facilitate the development we are going to use the Sail package provided by the Laravel Framework. [Sail](https://laravel.com/docs/8.x/sail) uses the [docker](https://docs.docker.com/get-started/) engine to start up a few containers with a web server and mysql service.

---

## Installation Guide (Windows)
### Install the Prerequisites

1. Check if your BIOS settings disable `virtualization`, if it do enable it.

2. Install [Docker](https://desktop.docker.com/win/stable/Docker%20Desktop%20Installer.exe)

3. Follow the steps [here](https://docs.microsoft.com/en-us/windows/wsl/install-win10#manual-installation-steps) to set up Windows Subsytems for Linux,
when you get to the part of choosing a linux distribution I recommend Ubuntu 20.04

4. Install [Windows Terminal](https://www.microsoft.com/en-ca/p/windows-terminal/9n0dx20hk701?rtc=1&activetab=pivot:overviewtab) 

5. Install [Visual Studio Code](https://code.visualstudio.com/)

6. Install [Remote Development](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.vscode-remote-extensionpack) VScode extension

### Setting up your linux machine (Ubuntu 20.04)

You can run the Ubuntu system from Windows Terminal by typing `ubuntu2004` on the command prompt. Once you are in the system follow this steps to set it up

1. Update the system 

    ```
    sudo apt update
    ```
2. Install dependencies
    ```
    sudo apt -y install php7.4

    sudo apt-get install -y php7.4-cli php7.4-json php7.4-common php7.4-mysql php7.4-zip php7.4-gd php7.4-mbstring php7.4-curl php7.4-xml php7.4-bcmath

    ```
3. Install composer
    ```
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"

    php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"

    php composer-setup.php

    php -r "unlink('composer-setup.php');"

    sudo mv composer.phar /usr/local/bin/composer

    ```
4. (Optional) Generate a ssh key and add it to your github account, by following [this guide](https://docs.github.com/en/github/authenticating-to-github/adding-a-new-ssh-key-to-your-github-account)

5. Add an alias for sail
    ```
    echo "alias sail='./vendor/bin/sail'" >> ~/.bashrc
    ```

### Into the project

1. Clone this repo into the ubuntu machine

    If you linked an SSH key:
    ```
    git clone git@github.com:Tutadit/jmj-backend.git
    ```
    If you are a looser
    ```
    git clone https://github.com/Tutadit/jmj-backend.git
    ```
2. cd into the porject directory
    ```
    cd jmj-backend
    ```
3. Install dependencies via composer
    ```
    composer update
    ```
4. Copy env file to proper env file
    ```
    cp .env.example .env
    ```
5. Run sail in detached mode ( remove the -d to have it linger on your terminal )
    ```
    sail up -d
    ```
6. Run database migrations and seedings
    ```
    sail artisian migrate --seed
    ```

All Done! We are ready to develop.

## What to develop?

All the necessary files have been created but remain to be implemented according to our design spec. 

### Migrations

Migrations are php classes that laravel uses to build tables in our database. This are located under the `database/migrations` folder, The migrations for the users and degrees tables have already been implemented. You can see them in the files `2014_10_12_000000_create_users_table.php` and `2021_04_02_181358_create_degrees_table.php`.

The migrations for the following tables need to be implemented:


Table | File under `database/migrations`
---------|----------|-------|
 Assigned  | `2021_04_03_154331_create_assigneds_table.php`
 Authors | `2021_04_03_154329_create_authors_table.php` 
 EvaluationMetric | `2021_04_03_154048_create_evaluation_metrics_table.php ` 
 Evaluation | `2021_04_03_154434_create_evaluations_table.php` 
 Journals | `2021_04_03_154036_create_journals_table.php` 
 MeasuredBy | `2021_04_03_154448_create_measured_bies_table.php` 
 Metric | `2021_04_03_154057_create_metrics_table.php`
 NominatedReviewers | `2021_04_03_154323_create_nominated_reviewers_table.php`
 PaperJournal | `2021_04_03_154458_create_paper_journals_table.php` 
 Paper | `2021_04_03_154414_create_papers_table.php` 
 Review |` 2021_04_03_154428_create_reviews_table.php`


Refer to the [laravel migrations documentation](https://laravel.com/docs/8.x/migrations) to learn how to make a migration. Speciallt the [Foreign Key Constraints](https://laravel.com/docs/8.x/migrations#foreign-key-constraints) documentation.


### Controllers 

Controllers are php classes that laravel can use to respond to requests, they are located in the `app/Http/Controllers`. The funtions described in each controller class represent an action over the table. For eaxmple the user controller has a methos called authenticate that is called when the user access /api/login via a post method. Each file in the `controllers` folder represents a table.

### Routes

The `routes` folder specifies routes available and their respective actions. One can use a controller to define anaction talen by specific routes, one can also define routes for various HTTP Methods.