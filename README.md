# cloudsync #

CloudSync is a Moodle plugin that integrates the big public cloud providers with Moodle. It makes creating virtual machines and provisioning them to students very easy. 

Through this web module, students can submit requests for virtual machines by filling out a form that includes details regarding the reasons for needing a virtual instance (the name of the supervising teacher who directed the student to make such a request, as well as a brief summary of the scenario the student wishes to run on the requested instance) and details about the requested virtual machine (name, operating system, RAM capacity, number of processor cores, number of disks, and storage space for each disk). Teachers with sufficient permissions on the Moodle platform then review these requests and decide whether to approve or reject them. If the request is approved, the specifications of the provided resources can be adjusted before they are created through the cloud providers. The entire workflow takes place on the Moodle site, eliminating the need for students or teachers to directly use other websites or applications.

As a module dedicated to the Moodle platform, CloudSync is built entirely using the PHP programming language, just like Moodle itself. The business logic is fully implemented using this programming language, and the module's interface uses Mustache templates rendered to the user via PHP files. For database access, the module utilizes the management system configured by the site administrator along with the entire platform.

# Installing the plugin

## Install Moodle

These instructions are meant to help you install Moodle on Ubuntu 22.04. Following these instructions will guide you to install Moodle 4.1.5 (build 20230915) with php 7.4.

1. Install Moodle requirements:

```bash
# Install php
sudo add-apt-repository ppa:ondrej/php
sudo apt-get update
sudo apt install apache2 mysql-client mysql-server php7.4 libapache2-mod-php
sudo a2dismod php8.3
sudo a2enmod php7.4
sudo rm /etc/alternatives/php
sudo ln -s /usr/bin/php7.4 /etc/alternatives/php
sudo apt install php7.4-fpm
sudo mysql_secure_installation
# Press enter to skip everything
sudo apt install graphviz aspell ghostscript clamav php7.4-pspell php7.4-curl php7.4-gd php7.4-intl php7.4-mysql php7.4-xml php7.4-xmlrpc php7.4-ldap php7.4-zip php7.4-soap php7.4-mbstring git
```

2. Prepare Moodle repository and move it to the web server directory:
```bash
sudo service apache2 restart
cd /opt
sudo git clone git://git.moodle.org/moodle.git
cd moodle/
sudo git branch -a
sudo git branch --track MOODLE_401_STABLE origin/MOODLE_401_STABLE
sudo git checkout MOODLE_401_STABLE
sudo git checkout 0a09e48
sudo git checkout -b MOODLE_415+_20230915

# Move it to web server directory
sudo cp -R /opt/moodle/ /var/www/html/
sudo mkdir /var/moodledata
sudo chown -R www-data /var/moodledata
sudo chmod -R 777 /var/moodledata
sudo chmod -R 0755 /var/www/html/moodle
sudo chmod -R 777 /var/www/html/moodle
# Edit root to point to moodle directory
sudo nano /etc/apache2/sites-available/000-default.conf
sudo service apache2 restart
```

3. Initiate Moodle Database:
```bash
sudo mysql -u root

# Inside mysql
CREATE DATABASE moodle DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
create user 'moodledude'@'localhost' IDENTIFIED BY 'passwordformoodledude';
GRANT SELECT,INSERT,UPDATE,DELETE,CREATE,CREATE TEMPORARY TABLES,DROP,INDEX,ALTER ON moodle.* TO 'moodledude'@'localhost';
```

4. Open a web browser to http://<YOUR-IP> to finish Moodle config.

## Install CloudSync

### Installing via uploaded ZIP file

1. Log in to your Moodle site as an admin and go to _Site administration >
   Plugins > Install plugins_.
2. Upload the ZIP file with the plugin code. You should only be prompted to add
   extra details if your plugin type is not automatically detected.
3. Check the plugin validation report and finish the installation.

### Installing manually

The plugin can be also installed by putting the contents of this directory to

    {your/moodle/dirroot}/local/cloudsync

Afterwards, log in to your Moodle site as an admin and go to _Site administration >
Notifications_ to complete the installation.

Alternatively, you can run

    $ php admin/cli/upgrade.php

to complete the installation from the command line.