FROM debian:stable-slim

RUN apt update && apt install -y apache2 wget nano software-properties-common ca-certificates lsb-release apt-transport-https \
  && sh -c 'echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list' \
  && wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg \
  && apt update && apt install php5.6 php5.6-mysql -y \
  && apt-get clean \
  && echo "ServerName localhost" >> /etc/apache2/apache2.conf && rm /var/www/html/index.html
WORKDIR /var/www/html
EXPOSE 80
COPY /server_configuration/php.ini /etc/php/5.6/apache2/php.ini
COPY . /var/www/html
CMD ["/usr/sbin/apachectl", "-D", "FOREGROUND"]
