#!/usr/bin/env bash

#== Import script args ==

timezone=$(echo "$1")

#== Bash helpers ==

function info {
  echo " "
  echo "--> $1"
  echo " "
}

#== Provision script ==

info "Provision-script user: `whoami`"

export DEBIAN_FRONTEND=noninteractive

info "Configure timezone"
timedatectl set-timezone ${timezone} --no-ask-password

info "Configure locales"
apt-get install locales


info "Prepare root password for MySQL"
debconf-set-selections <<< "mysql-community-server mysql-community-server/root-pass password \"''\""
debconf-set-selections <<< "mysql-community-server mysql-community-server/re-root-pass password \"''\""
echo "Done!"

info "Add PHp 7.1 repository"
add-apt-repository ppa:ondrej/php -y

#== info "Add Oracle JDK repository"
#== add-apt-repository ppa:webupd8team/java -y

#== info "Add ElasticSearch sources"
#== wget -qO - https://artifacts.elastic.co/GPG-KEY-elasticsearch | apt-key add -
#== echo "deb https://artifacts.elastic.co/packages/5.x/apt stable main" | tee -a /etc/apt/sources.list.d/elastic-5.x.list

info "Update OS software"
apt-get update
apt-get upgrade -y

info "Configure locales"
apt-get install locales
localedef ru_RU.UTF-8 -i ru_RU -f UTF-8

info "Install additional software"
#==  apt-get install -y mc php7.1-curl php7.1-cli php7.1-intl php7.1-mysqlnd php7.1-gd php7.1-fpm php7.1-mbstring php7.1-xml php7.1-zip unzip nginx mysql-server-5.7
apt-get install -y php7.1-curl php7.1-cli php7.1-intl php7.1-mysqlnd php7.1-gd php7.1-fpm php7.1-mbstring php7.1-xml php7.1-zip php7.1-bcmath php-amqp php7.1-zmq php7.1-soap unzip nginx
apt-get install -y libpcre3-dev
apt-get install -y mariadb-server mariadb-client

#== apt-get install -y php7.1-bcmath
#== apt-get install -y php-amqp

#== info "Install Oracle JDK"
#== debconf-set-selections <<< "oracle-java8-installer shared/accepted-oracle-license-v1-1 select true"
#== debconf-set-selections <<< "oracle-java8-installer shared/accepted-oracle-license-v1-1 seen true"
#== apt-get install -y oracle-java8-installer

info "Install Phalcon framework"
curl -s https://packagecloud.io/install/repositories/phalcon/stable/script.deb.sh | sudo bash
apt-get install php-phalcon

info "Install Midnight Commander"
apt-get install -y mc

# info "Install ElasticSearch"
# apt-get install -y elasticsearch
# sed -i 's/-Xms2g/-Xms64m/' /etc/elasticsearch/jvm.options
# sed -i 's/-Xmx2g/-Xmx64m/' /etc/elasticsearch/jvm.options
# service elasticsearch restart

# info "Install Redis"
apt-get install -y redis-server

info "Install Supervisor"
apt-get install -y supervisor

info "Configure MySQL"
#== sed -i "s/.*bind-address.*/bind-address = 0.0.0.0/" /etc/mysql/mysql.conf.d/mysqld.cnf
mysql -uroot <<< "CREATE USER 'root'@'%' IDENTIFIED BY ''"
mysql -uroot <<< "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%'"
mysql -uroot <<< "FLUSH PRIVILEGES"

mysql -uroot <<< "CREATE DATABASE phonebook_db"
mysql -uroot <<< "CREATE USER 'phonebook_dbu'@'localhost' IDENTIFIED BY 'superpass'"
mysql -uroot <<< "GRANT ALL PRIVILEGES ON phonebook_db.* TO 'phonebook_dbu'@'localhost'"

mysql -uroot <<< "DROP USER 'root'@'localhost'"
mysql -uroot <<< "FLUSH PRIVILEGES"
echo "Done!"

info "Configure PHP-FPM"
sed -i 's/user = www-data/user = vagrant/g' /etc/php/7.1/fpm/pool.d/www.conf
sed -i 's/group = www-data/group = vagrant/g' /etc/php/7.1/fpm/pool.d/www.conf
sed -i 's/owner = www-data/owner = vagrant/g' /etc/php/7.1/fpm/pool.d/www.conf
echo "Done!"

info "Configure NGINX"
sed -i 's/user www-data/user vagrant/g' /etc/nginx/nginx.conf
echo "Done!"

info "Enabling site configuration"
ln -s /app/vagrant/nginx/app.conf /etc/nginx/sites-enabled/app.conf
ln -s /app/vagrant/nginx/app-test.conf /etc/nginx/sites-enabled/app-test.conf
echo "Done!"

info "Enabling supervisor processes"
ln -s /app/vagrant/supervisor/queue.conf /etc/supervisor/conf.d/queue.conf
echo "Done!"

info "Install composer"
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

