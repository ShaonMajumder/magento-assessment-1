## The Environment Setup patience

### The ideology behind the customized approach
You suggested using the Magento Docker setup from Mark Shust:
 🔗 https://github.com/markshust/docker-magento

But, I found there is lot of overhead scripts, I have no idea. So I tried to taste the magento in my own style. So that, I can in long run embrace it rather than, using it once for an assessment. So, this will make things faster in long run, for sure.

### Challenges
#### view is broken still

Problem :
```txt
Static folder is present - E:\Projects\docker-template_magento-2-php-8.1\src\pub\static\frontend\Magento\luma\en_US
GET http://localhost/static/version1750412393/frontend/Magento/luma/en_US/css/print.css net::ERR_ABORTED 404 (Not Found)
```

Solution in nginx :
```txt
location ~* ^/static/version\d+/(.+)$ {
    try_files /static/$1 /static.php?resource=$1;
}
```

Now recompile :
For deploying static content -
```bash
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy -f
php bin/magento cache:flush
```

The result :

admin url - http://localhost/admin_q2macny/



Furture NB for lookups:
```txt
Nothing to import.
Media files stored outside of 'Media Gallery Allowed' folders will not be availa
ble to the media gallery.
Please refer to Developer Guide for more details.
```

#### Stopping 2 factor auth in development
Disable via CLI (Recommended for Local Dev)
Run this in your magento-app container:

```bash
docker exec -it magento-app php bin/magento module:disable Magento_TwoFactorAuth Magento_AdminAdobeImsTwoFactorAuth
docker exec -it magento-app php bin/magento cache:flush
```


regenerate the code and clear caches:
```bash
docker exec -it magento-app php bin/magento setup:upgrade
docker exec -it magento-app php bin/magento setup:di:compile
docker exec -it magento-app php bin/magento cache:flush # optional
```

🔐 Optional: Re-enable 2FA Later (in production)
```bash
php bin/magento module:enable Magento_TwoFactorAuth
php bin/magento setup:upgrade
php bin/magento cache:flush
```

#### Error to check in future
launch-177bc126c8e6.min.js:4 
            
GET https://assets.adobedtm.com/a7d65461e54e/37baabec1b6e/dd384d3e4fd2/RC8d77dca52e4444d687982450237e307c-source.min.js net::ERR_BLOCKED_BY_CLIENT

#### Challenge in loading
commadns other commands - docker exec -it magento-app php bin/magento setup:di:compile , even loading taking much time, how to reduce time, caching or any other approach


Developer mode skips many optimizations and is best for local development.

Set developer mode:

```bash
docker exec -it magento-app php bin/magento deploy:mode:set developer
php bin/magento cache:enable

```

Also thinking of enabling opcache in php.ini :
```ini
opcache.enable=1
opcache.memory_consumption=512
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.validate_timestamps=1
opcache.revalidate_freq=0
```

##### Do not mount unecessary directory 
Autoload error
Vendor autoload is not found. Please run 'composer install' under application root directory.

set the volumes, dont mount generated cache folder
```yaml
# - ./src:/var/www/html/src
# - ./src:/var/www/html/src:cached
- ./src/app:/var/www/html/src/app
- ./src/pub:/var/www/html/src/pub
- ./src/composer.json:/var/www/html/src/composer.json
- ./src/composer.lock:/var/www/html/src/composer.lock
# -----
```
```bash
docker exec -it magento-app composer install
########### using the cache
docker exec -it magento-app composer install --no-interaction --prefer-dist --optimize-autoloader
########### remove previously compiled file
docker exec -it magento-app rm -rf /var/www/html/src/generated/code/*
docker exec -it magento-app rm -rf /var/www/html/src/var/cache/*
docker exec -it magento-app rm -rf /var/www/html/src/var/page_cache/*
docker exec -it magento-app php bin/magento deploy:mode:set developer
########### compile the files
docker exec -it magento-app php bin/magento setup:di:compile
########### set the permissions
docker exec -it magento-app chown -R www-data:www-data var pub generated
docker exec -it magento-app chmod -R 775 var pub generated


php bin/magento setup:static-content:deploy -f
php bin/magento cache:flush
#php bin/magento cache:clean


# not useful
php bin/magento indexer:reindex
```

Really speeds up all the commands, which was previously frustrating.

##### Disable unnecssary modules
Ensure you’re in developer mode to avoid di:compile:

```bash
docker exec -it magento-app php bin/magento deploy:mode:show
```

If not in developer mode, set it:
```bash
docker exec -it magento-app php bin/magento deploy:mode:set developer
```

3-2-1 Development battle begins, oh my god I am running out of time.

## The Development Battle with limited time

```txt
src/app/code/Strativ/ProductTags/
├── Block/
│   ├── Adminhtml/Tag/Grid.php
│   ├── Product/Tags.php
├── Controller/
│   ├── Adminhtml/Tag/Index.php
│   ├── Tag/View.php
├── Model/
│   ├── ProductTags.php
│   ├── ResourceModel/ProductTags.php
│   ├── ResourceModel/ProductTags/Collection.php
│   ├── Repository/ProductTagsRepository.php
├── Setup/
│   ├── InstallSchema.php
├── Ui/
│   ├── Component/Listing/Columns/TagActions.php
│   ├── DataProvider/Product/Form/Modifier/Tags.php
├── view/
│   ├── adminhtml/
│   │   ├── layout/strativ_tag_index.xml
│   │   ├── ui_component/strativ_product_tags_listing.xml
│   ├── frontend/
│   │   ├── layout/producttags_tag_view.xml
│   │   ├── templates/product/tags.phtml
│   │   ├── web/css/tags.css
├── composer.json
├── etc/
│   ├── adminhtml/menu.xml
│   ├── adminhtml/routes.xml
│   ├── db_schema.xml
│   ├── di.xml
│   ├── frontend/routes.xml
│   ├── module.xml
│   ├── ui_component/product_form.xml
├── registration.php
```

After setting up the file, lets enable the module :
```bash
##### enabling modules
docker exec -it magento-app php bin/magento module:enable Strativ_ProductTags
##### migrating schema
docker exec -it magento-app php bin/magento setup:upgrade
docker exec -it magento-app php bin/magento setup:di:compile

docker exec -it magento-app php bin/magento cache:flush

```

##### Use 
Log in to your admin panel at http://localhost/admin_q2macny using the credentials from your .env file (MAGENTO_ADMIN_USER=admin, MAGENTO_ADMIN_PASSWORD=admin123).
Navigate to Catalog > Products in the left sidebar.
Click Edit on any existing product or click Add Product to create a new one.
Scroll to the Content section in the product form.
Look for a field labeled Strativ Tags (it appears below the product description field).

##### Update the system with new source
execute ./docker/magento-scripts/update.sh