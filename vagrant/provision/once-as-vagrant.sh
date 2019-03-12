#!/usr/bin/env bash

#== Import script args ==

github_token=$(echo "$1")

#== Bash helpers ==

function info {
  echo " "
  echo "--> $1"
  echo " "
}

#== Provision script ==

info "Provision-script user: `whoami`"

info "Configure composer"
composer config --global github-oauth.github.com ${github_token}
echo "Done!"

info "Install project dependencies"
composer require "codeception/codeception:*"
composer require  "phalcon/devtools:*"
composer --no-progress --prefer-dist install

info "Init project"
#== php yii  app/setup --interactive=0
info "TEMP: Project init here"

info "Apply migrations"
#== php yii migrate --interactive=0
#== ./yii_test migrate --interactive=0
info "TEMP: Migrations here"

info "Create bash-aliases for vagrant user"
echo 'alias app="cd /app"' | tee /home/vagrant/.bash_aliases
echo 'alias phalcon="/app/vendor/bin/phalcon.php"' | tee /home/vagrant/.bash_aliases

info "Enabling colorized prompt for guest console"
sed -i "s/#force_color_prompt=yes/force_color_prompt=yes/" /home/vagrant/.bashrc
