# Tabletka Test project
Hello there!

It's my first commertial project from univercity years for various testing purposes.
That project exist only for demonstration of my past experience in PHP programming and i decide to use that project for my personal local tests and demonstrations.
It does not represent my current skills in programming and it does not give me any profits. I dont have any plans to improve or support this old code (its totaly obsolete), but i think it will be pretty useful to test some old legacy solutions and give me some idea how to build CI/CD process and work with GIT for example.

## What is about?
It is a simple website, that contains several modules, like:
1. Online shop (some medical products, that does not exists) with bucket and some products in it.
2. Questionnaire module (a little test, that shows which medicine you can use from that shop).
3. Formulas creation module (big page, that allows you to make some calculations about chemical components, even i dont remember well what its doing :D )
4. Admin panel with several options, like users and products management.
5. Some SMS and captcha integration, that does not work right now.

## Can you show it?
Yes, sure. My project available via this link:
https://tabletka.124fps.online/

## Requirements
Apache/PHP 5.6
MySQL v8.0.30

## Usage
Just install all obsolete packages from upper block.
Example of installation on Debian 11 down below.

```
apt update && apt install -y apache2 wget nano software-properties-common ca-certificates lsb-release apt-transport-https
sh -c 'echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list'
wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
apt update && apt install php5.6 php5.6-mysql -y

```

Put in /system/core.php creditentials for your database.
Database dump exists in /database/tabletka.sql
Edit php.ini, if you see some errors or use ready one from /server_configuration/ folder.

## Support
I dont give any support for this code, because it does not make any sence :D

## Copyrigths
All images and all front-end work is not mine completly, so you definetly should not use any materials here for bussines purposes (but who can stop you anyway?).
Other stuff, like code is totaly mine.
As i said before, its only for testing and education purposes.
