#!/bin/bash

set -e

# Pull latest changes from git
echo -e "\033[0;34mPulling latest changes from git...\033[0m"
if ! git pull origin; then
    echo -e "\033[0;31mError: Failed to pull latest changes from git.\033[0m"
    exit 1
fi

# Rebuild and start containers
echo -e "\033[0;34mRebuilding and starting Docker containers...\033[0m"
if ! docker compose -f 'docker-compose.yml' up -d --build; then
    echo -e "\033[0;31mError: Failed to rebuild and start Docker containers.\033[0m"
    exit 1
fi

echo -e "\033[0;32mDeployment successful!\033[0m"