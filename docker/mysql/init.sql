-- docker/mysql/init.sql
ALTER USER 'magento'@'%' IDENTIFIED WITH caching_sha2_password BY 'magento';
FLUSH PRIVILEGES;