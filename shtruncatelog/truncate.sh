#!/bin/bash

# Define log paths
AUDIT_LOG="/var/log/modsec_audit.log"
DEBUG_LOG="/var/log/modsecurity-debug.log"
LOG_DIR="/var/log/" # Directory containing logs

# Infinite loop
while true; do
    # Truncate the modsec_audit.log file
    if [ -f "$AUDIT_LOG" ]; then
        truncate -s 0 "$AUDIT_LOG"
        echo "$(date): Truncated $AUDIT_LOG"
    else
        echo "$(date): $AUDIT_LOG not found"
    fi

    # Truncate the modsecurity-debug.log file
    if [ -f "$DEBUG_LOG" ]; then
        truncate -s 0 "$DEBUG_LOG"
        echo "$(date): Truncated $DEBUG_LOG"
    else
        echo "$(date): $DEBUG_LOG not found"
    fi

    # Cleanup logs older than 90 days in the specified directory
    find "$LOG_DIR" -name "*.log" -type f -mtime +90 -exec rm -f {} \;
    echo "$(date): Removed logs older than 90 days in $LOG_DIR"

    # Wait for 10 seconds before the next iteration
    sleep 90d
done
