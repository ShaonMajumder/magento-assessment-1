#!/bin/bash

set -e  # Exit immediately if any command fails

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
AUTH_FILE="$SCRIPT_DIR/auth.json"

if [ -f "$AUTH_FILE" ]; then
  echo "✅ auth.json already exists. Skipping generation."
else
  echo "🔑 Generating auth.json..."
  bash "$SCRIPT_DIR/docker/scripts/generate-auth.sh"
fi

echo "Copying the .env"
cp docker/environment/.env.magento .env
echo "🚀 Starting Docker containers with build..."
docker-compose --env-file docker/environment/.env.magento up -d --build