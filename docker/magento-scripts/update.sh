docker exec -it magento-app rm -rf generated/*
docker exec -it magento-app rm -rf pub/static/adminhtml/*
docker exec -it magento-app rm -rf var/cache/*
docker exec -it magento-app rm -rf var/page_cache/*
docker exec -it magento-app rm -rf var/view_preprocessed/*

docker exec -it magento-app php bin/magento module:enable Strativ_ProductTags
# updating db schema
docker exec -it magento-app php bin/magento setup:upgrade
docker exec -it magento-app php bin/magento setup:di:compile
docker exec -it magento-app php bin/magento setup:static-content:deploy -f
# cache clean
docker exec -it magento-app php bin/magento cache:clean
docker exec -it magento-app php bin/magento cache:flush
# fixing permissions
docker exec -it magento-app chown -R www-data:www-data var pub generated
docker exec -it magento-app chmod -R 775 var pub generated