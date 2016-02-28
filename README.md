# Project #ARZ-537-89248

# Version v0.1a

# System requirments:

* Ubuntu / Debian

# Installation:

##### 01. Install `Apache2`

---

```sh
$ sudo apt-get update
$ sudo apt-get install apache2
```

##### 02. Install `MySQL`

---

```sh
$ sudo apt-get install mysql-server libapache2-mod-auth-mysql
```

##### 03. Create database `ARZ-537-89248`

##### 04. Install `PHP`

---

```sh
$ sudo apt-get install php5 libapache2-mod-php5
$ sudo apt-get install php5-mcrypt
$ sudo apt-get install php5-mysql
$ sudo apt-get install php5-imap
```

##### 05. Enable mods

---

```sh
$ sudo a2enmod rewrite
$ sudo php5enmod imap
```

##### 06. Setup server

---

```sh
$ sudo nano /etc/apache2/sites-available/000-default.conf
```

```nano
<VirtualHost *:80>
    # Change to your server name
    ServerName 37.139.13.167
    DocumentRoot /var/www/ARZ-537-89248-A/
    DirectoryIndex index.php
    ErrorLog /var/www/ARZ-537-89248-A/log/error.log
    CustomLog /var/www/ARZ-537-89248-A/log/access.log combined
    <Directory "/var/www/ARZ-537-89248-A/">
        AllowOverride All
        Allow from All
    </Directory>
</VirtualHost>
```

##### 07. Set `date.timezone` in `php.ini`

##### 08. Install `git`

---

```sh
$ sudo apt-get install git
```

##### 09. Pull code

---

```sh
$ git config --global user.name "John Doe"
$ git config --global user.email "johndoe@gmail.com"
$ cd /var/www/
$ git clone https://gitlab.com/spam312sn/ARZ-537-89248-A.git
$ cd ARZ-537-89248-A
$ mkdir log
$ chmod -R 755 ./
```

##### 0A. Edit config

---

```sh
$ nano /var/www/ARZ-537-89248/application/config.php
```

##### 0B. Restart server

---

```sh
$ sudo service apache2 restart
```

##### 0C. Go to `http(s)://%your_server%/`

It will redirect you to `/install`. After tables installation remove installation files

```sh
$ rm -rf /var/www/ARZ-537-89248/application/controller/install.controller.php
$ rm -rf /var/www/ARZ-537-89248/sql
```

# Overview:

* View message in app = in GMail is now marked as seen

* Deleting in app = move to trash in GMail

* View letter in GMail = letter in app is now marked as seen

* Unseen letter in GMail = unseen letter in app

* Deleting in GMail = deleting in app

* Moving to trash in GMail = moving to trash in app

* Moving letter to spam folder in GMail = moving letters in spam folder in app

# Warning!

### Do not use your Google password - it's keep in database as plain text. Use only app password!

# Test

You can use the folowing data as test mailbox:

* email = jd5484025@gmail.com
* password = test
* app password = wuojgmggucoqetca