#! /bin/bash -eu

################################################################################
# Initialize the parameters
################################################################################
APP_DIR=/var/www/m/app
MYSQL="mysql -u mav -pmav -D mav "
SONG_TABLE_FILE=$APP_DIR/migrations/song_tables.sql
USER_TABLE_FILE=$APP_DIR/migrations/users.sql
RUBY_FILE=$APP_DIR/bin/update_tables.rb
CREATE_THUMBNAIL=$APP_DIR/bin/create_thumbnail.sh

################################################################################
# Main process
################################################################################
cat $SONG_TABLE_FILE | $MYSQL
$RUBY_FILE
$CREATE_THUMBNAIL
