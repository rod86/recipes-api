# Recipes API

API built with Lumen Framework.

As I development environment I used virtualbox and vagrant with the box https://box.scotch.io/.

## Install

* Install Vagrant and virtualbox.
* Install the vagrant box as it's explained in https://box.scotch.io/. In the step 3, use the vagranfile provided.
* In your hosts file, add this line

    ```
    192.168.33.10 recipes.scotchbox.local www.recipes.scotchbox.local
    ```
* Start the vagrant box and check that next to the vagrantfile file, you have a directory called recipes.scotchbox.local.
* Clone the below repository into the directory recipes.scotchbox.local.

    ```
    https://github.com/rod86/recipes-api.git
    ```

* Install composer dependencies
* Create .env file from.env.sample
* Open http://recipes.scotchbox.local/api/v1/recipes in the browser and you should see a json with the recipes list from the csv file.
