#!/bin/bash
# run_locally.sh
# Simple script to run the PHP built-in server

echo "Starting SmartCampus Web Server..."
echo "Open your Brave browser and go to: http://localhost:8000"
echo "Press Ctrl+C to stop the server."

# PHP built-in server
# -S localhost:8000 : Start server on port 8000
# -t webb           : Serve files from the 'webb' directory
php -S localhost:8000 -t webb
