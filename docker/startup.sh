#!/bin/bash

# Maximum wait time (seconds)
MAX_WAIT=60

# Define Magento root directory
MAGENTO_ROOT="/var/www/html/src"

# Wait for MySQL
start_time=$(date +%s)
until mysqladmin ping -h "$MAGENTO_DATABASE_HOST" --silent --connect-timeout=2 -u"$DB_USERNAME" -p"$DB_PASSWORD"; do
    current_time=$(date +%s)
    elapsed=$((current_time - start_time))
    if [ $elapsed -ge $MAX_WAIT ]; then
        echo "Error: Timeout waiting for MySQL after ${MAX_WAIT} seconds."
        exit 1
    fi
    echo "Waiting for MySQL..."
    sleep 2
done
echo "MySQL is ready."

# Wait for Elasticsearch
start_time=$(date +%s)
until curl -s "$ELASTICSEARCH_HOST:$ELASTICSEARCH_PORT" > /dev/null; do
    current_time=$(date +%s)
    elapsed=$((current_time - start_time))
    if [ $elapsed -ge $MAX_WAIT ]; then
        echo "Error: Timeout waiting for Elasticsearch after ${MAX_WAIT} seconds."
        exit 1
    fi
    echo "Waiting for Elasticsearch..."
    sleep 2
done
echo "Elasticsearch is ready."

# Wait for Redis
start_time=$(date +%s)
until redis-cli -h "$REDIS_HOST" ping > /dev/null; do
    current_time=$(date +%s)
    elapsed=$((current_time - start_time))
    if [ $elapsed -ge $MAX_WAIT ]; then
        echo "Error: Timeout waiting for Redis after ${MAX_WAIT} seconds."
        exit 1
    fi
    echo "Waiting for Redis..."
    sleep 2
done
echo "Redis is ready."

# # Copy .env.magento to src/.env
# # "$MAGENTO_ROOT/.env"
# if [ -f "../docker/environment/.env.magento" ]; then
#     echo "Copying ../docker/environment/.env.magento to $MAGENTO_ROOT/.env..."
#     cp ../docker/environment/.env.magento "$MAGENTO_ROOT/.env"
#     chown www-data:www-data "$MAGENTO_ROOT/.env"
#     chmod 644 "$MAGENTO_ROOT/.env"
# else
#     echo "Warning: /docker/environment/.env.magento not found."
# fi

# # Check if Magento is already installed
# if [ ! -f "$MAGENTO_ROOT/app/etc/env.php" ]; then

#     # Check if composer.json exists to avoid reinstallation
#     if [ ! -f "$MAGENTO_ROOT/composer.json" ]; then
#         echo "Installing Magento via Composer in $MAGENTO_ROOT..."

#         mkdir -p "$MAGENTO_ROOT"

#         COMPOSER_DISABLE_NETWORK_TIMEOUTS=1 \
#         COMPOSER_CURL_RETRY=5 \
#         composer create-project --repository-url=https://repo.magento.com/ magento/project-community-edition:2.4.7 "$MAGENTO_ROOT" \
#             || { echo "Composer create-project failed"; exit 1; }

#         chown -R www-data:www-data "$MAGENTO_ROOT"
#         chmod -R 755 "$MAGENTO_ROOT"
#     fi

#     echo "Running Magento setup:install..."
#     cd "$MAGENTO_ROOT"
#     php bin/magento setup:install \
#         --base-url=http://$MAGENTO_HOST/ \
#         --db-host=$MAGENTO_DATABASE_HOST \
#         --db-name=$DB_DATABASE \
#         --db-user=$DB_USERNAME \
#         --db-password=$DB_PASSWORD \
#         --admin-firstname=Admin \
#         --admin-lastname=User \
#         --admin-email=$MAGENTO_ADMIN_EMAIL \
#         --admin-user=$MAGENTO_ADMIN_USER \
#         --admin-password=$MAGENTO_ADMIN_PASSWORD \
#         --language=en_US \
#         --currency=USD \
#         --timezone=America/Chicago \
#         --use-rewrites=1 \
#         --search-engine=elasticsearch7 \
#         --elasticsearch-host=$ELASTICSEARCH_HOST \
#         --elasticsearch-port=$ELASTICSEARCH_PORT \
#         --cache-backend=redis \
#         --cache-backend-redis-server=$REDIS_HOST \
#         --cache-backend-redis-port=6379 \
#         --session-save=redis \
#         --session-save-redis-host=$REDIS_HOST \
#         --session-save-redis-port=6379 \
#         || { echo "Magento setup:install failed"; exit 1; }

#     php bin/magento deploy:mode:set developer
#     php bin/magento cache:clean
# else
#     echo "Magento already installed in $MAGENTO_ROOT, skipping installation."
# fi

# # Set permissions
# chown -R www-data:www-data "$MAGENTO_ROOT"
# chmod -R 755 "$MAGENTO_ROOT"
# find "$MAGENTO_ROOT" -type d -exec chmod 755 {} \;
# find "$MAGENTO_ROOT" -type f -exec chmod 644 {} \;
# chmod -R 770 "$MAGENTO_ROOT/var" "$MAGENTO_ROOT/pub/media" "$MAGENTO_ROOT/pub/static"

# For deploying static content :
# php bin/magento setup:static-content:deploy -f

# Start Supervisord
exec /usr/bin/supervisord -c /etc/supervisord.conf