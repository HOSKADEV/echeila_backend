#!/bin/bash

# Laravel Queue Worker Cron Job Script (Shell Version)
# Alternative to the PHP version for users who prefer shell scripts
#
# Usage in crontab:
# * * * * * /path/to/your/project/queue-worker.sh >> /dev/null 2>&1

# Get the directory where this script is located
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

# Configuration
LOG_FILE="$SCRIPT_DIR/storage/logs/queue-worker.log"
PHP_BIN="php"

# Function to log messages
log_message() {
    local timestamp=$(date '+%Y-%m-%d %H:%M:%S')
    echo "[$timestamp] $1" >> "$LOG_FILE"
}

# Function to check if queue worker is running
is_queue_worker_running() {
    pgrep -f "queue:work" > /dev/null 2>&1
}

# Ensure logs directory exists
mkdir -p "$(dirname "$LOG_FILE")"

# Check if worker is already running
if is_queue_worker_running; then
    # Worker is running, exit silently (uncomment the line below to log this)
    # log_message "Queue worker is already running"
    exit 0
fi

# Worker is not running, start it
log_message "No queue worker found running. Attempting to start..."

# Verify Laravel installation
if [ ! -f "$SCRIPT_DIR/artisan" ]; then
    log_message "Error: artisan file not found. Make sure this script is in the Laravel root directory."
    exit 1
fi

# Start the queue worker
log_message "Starting Laravel queue worker..."
nohup $PHP_BIN artisan queue:work --sleep=3 --tries=3 --max-time=3600 --memory=512 > /dev/null 2>&1 &

if [ $? -eq 0 ]; then
    log_message "Queue worker started successfully"
    exit 0
else
    log_message "Failed to start queue worker"
    exit 1
fi