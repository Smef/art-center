#!/bin/bash

# remove existing custom log file configuration
rm /opt/elasticbeanstalk/tasks/taillogs.d/custom-logs.conf
rm /opt/elasticbeanstalk/tasks/bundlelogs.d/custom-logs.conf


# make an array of logs to add
files=(
"/var/app/current/storage/logs/*.log"
"/root/.npm/_logs/*.log"
"/var/log/php-fpm/*.log"
"/var/log/php-fpm/*.log-*"
)

# loop through the logs and add them to the tail and bundle logs
for value in "${files[@]}"
do
    # append each path to a custom log file configuration for tail and bundle logs
    echo $value >> /opt/elasticbeanstalk/tasks/taillogs.d/custom-logs.conf
    echo $value >> /opt/elasticbeanstalk/tasks/bundlelogs.d/custom-logs.conf
done

# set the owner and permissions for the custom log file configuration
chown root:root /opt/elasticbeanstalk/tasks/taillogs.d/custom-logs.conf
chown root:root /opt/elasticbeanstalk/tasks/bundlelogs.d/custom-logs.conf
chmod 644 /opt/elasticbeanstalk/tasks/taillogs.d/custom-logs.conf
chmod 644 /opt/elasticbeanstalk/tasks/bundlelogs.d/custom-logs.conf
