#! /bin/bash -eu

################################################################################
# Initialize the parameters
################################################################################
APP_DIR=/var/www/m/app
MYSQL="mysql -u mav -pmav -D mav "
SQL_FILE=$APP_DIR/migrations/schema.mysql.sql
RUBY_FILE=$APP_DIR/bin/update_tables.rb

################################################################################
# Main process
################################################################################
$MYSQL < $SQL_FILE
$RUBY_FILE
