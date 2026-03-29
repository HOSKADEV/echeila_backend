<?php

/**
 * Laravel Queue Worker Cron Job Script
 * 
 * This script is designed to be run as a cron job to start the Laravel queue worker.
 * It ensures the queue worker is running and handles basic error logging.
 * 
 * Usage in crontab:
 * * * * * * cd /path/to/your/project && php queue-worker.php >> /dev/null 2>&1
 * 
 * Or run every minute to check if worker is still running:
 * * * * * * /usr/bin/php /path/to/your/project/queue-worker.php
 */

// Set the project root directory
$projectRoot = __DIR__;

// Change to the project directory
chdir($projectRoot);

// Function to check if queue worker is already running
function isQueueWorkerRunning() {
    $output = shell_exec('ps aux | grep "queue:work" | grep -v grep');
    return !empty(trim($output ?? ''));
}

// Function to log messages
function logMessage($message) {
    $timestamp = date('Y-m-d H:i:s');
    $logFile = __DIR__ . '/storage/logs/queue-worker.log';
    
    // Ensure logs directory exists
    if (!is_dir(dirname($logFile))) {
        mkdir(dirname($logFile), 0755, true);
    }
    
    file_put_contents($logFile, "[{$timestamp}] {$message}" . PHP_EOL, FILE_APPEND | LOCK_EX);
}

// Function to start the queue worker
function startQueueWorker() {
    logMessage("Starting Laravel queue worker...");
    
    // Build the command
    $phpBinary = PHP_BINARY ?: 'php';
    $command = "{$phpBinary} artisan queue:work --sleep=3 --tries=3 --max-time=3600 --memory=512 > /dev/null 2>&1 &";
    
    // Execute the command
    exec($command, $output, $returnCode);
    
    if ($returnCode === 0) {
        logMessage("Queue worker started successfully");
        return true;
    } else {
        logMessage("Failed to start queue worker. Return code: {$returnCode}");
        return false;
    }
}

// Main execution
try {
    // Check if worker is already running
    if (isQueueWorkerRunning()) {
        // Worker is running, optionally log this (uncomment if needed)
        // logMessage("Queue worker is already running");
        exit(0);
    }
    
    // Worker is not running, start it
    logMessage("No queue worker found running. Attempting to start...");
    
    // Verify Laravel installation
    if (!file_exists($projectRoot . '/artisan')) {
        logMessage("Error: artisan file not found. Make sure this script is in the Laravel root directory.");
        exit(1);
    }
    
    // Start the queue worker
    if (startQueueWorker()) {
        logMessage("Queue worker startup completed");
        exit(0);
    } else {
        logMessage("Failed to start queue worker");
        exit(1);
    }
    
} catch (Exception $e) {
    logMessage("Exception occurred: " . $e->getMessage());
    exit(1);
}