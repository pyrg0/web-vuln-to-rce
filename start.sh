#!/bin/bash

# Set permissions
chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html

sudo chown www-data:www-data challenges/file_upload/uploads
sudo chmod 755 challenges/file_upload/uploads
