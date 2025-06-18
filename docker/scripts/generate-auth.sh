#!/bin/bash

# Set path to save auth.json outside docker/ folder
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(realpath "$SCRIPT_DIR/../..")"
AUTH_FILE="${PROJECT_ROOT}/auth.json"

# Get credentials from env or prompt if not set
MAGENTO_USERNAME="${MAGENTO_USERNAME:-}"
MAGENTO_PASSWORD="${MAGENTO_PASSWORD:-}"

# Prompt if not set
if [ -z "$MAGENTO_USERNAME" ]; then
    read -p "Enter your Magento Public Key (username): " MAGENTO_USERNAME
fi

if [ -z "$MAGENTO_PASSWORD" ]; then
    read -s -p "Enter your Magento Private Key (password): " MAGENTO_PASSWORD
    echo
fi

# Validate
if [ -z "$MAGENTO_USERNAME" ] || [ -z "$MAGENTO_PASSWORD" ]; then
    echo "❌ Error: Both username and password are required."
    exit 1
fi

# Write the auth.json
cat > "$AUTH_FILE" <<EOF
{
  "http-basic": {
    "repo.magento.com": {
      "username": "$MAGENTO_USERNAME",
      "password": "$MAGENTO_PASSWORD"
    }
  }
}
EOF

echo "✅ auth.json created at: $AUTH_FILE"
