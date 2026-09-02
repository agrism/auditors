#!/usr/bin/env bash

mysql --user=root --password="$MYSQL_ROOT_PASSWORD" <<-EOSQL
    CREATE DATABASE IF NOT EXISTS auditors;
    CREATE DATABASE IF NOT EXISTS laravel;
    CREATE DATABASE IF NOT EXISTS testing;
    GRANT ALL PRIVILEGES ON \`auditors%\`.* TO '$MYSQL_USER'@'%';
    GRANT ALL PRIVILEGES ON \`laravel%\`.* TO '$MYSQL_USER'@'%';
    GRANT ALL PRIVILEGES ON \`testing%\`.* TO '$MYSQL_USER'@'%';
    FLUSH PRIVILEGES;
EOSQL
