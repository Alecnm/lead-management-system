#!/bin/bash

echo "Waiting for the database to be ready..."
echo "Waiting for MySQL database connection..."
sleep 5
echo "Running migrations..."
vendor/bin/phinx migrate
