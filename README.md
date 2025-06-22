# 🏷️ ProductTags Magento 2 Module

Welcome to the **ProductTags** module, a custom Magento 2 feature developed as part of a learning assignment to demonstrate PHP and OOP skills in a new framework. This module allows admin users to add comma-separated tags to products in the Magento admin panel and optionally display them on the frontend. Despite time constraints and initial unfamiliarity with Magento 2, this project showcases a journey of learning, problem-solving, and performance optimization.

## ⚙️ The Environment Setup patience

### 💡 The ideology behind the customized approach
You suggested using the Magento Docker setup from Mark Shust:
 🔗 https://github.com/markshust/docker-magento

But, I found in that repository, there are lot of overhead scripts, I have no idea. So I tried to taste the magento in my own style. So that, I can embrace magento in long run rather than, using it once for an assessment. So, this will make things faster in long run, for sure. With customized docker setup, I can speed things up. You can have the proof from my optimization for docker scripts `docker/` and `docker-compose.yml`. Here I also added `composer_cache` to speed things further.

### 🧱 Challenges before first view of Magento

#### I. Autoload error
`Autoload error`
Vendor autoload is not found. Please run 'composer install' under application root directory.

solve :
```bash
docker exec -it magento-app composer install
########### using the cache
docker exec -it magento-app composer install --no-interaction --prefer-dist 
```


#### II. 🧩 view is broken still

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

#### III. 🔒 Stopping 2 factor auth in development
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

#### IV. 🐢 Magento is Slow Out of the Box
this command `docker exec -it magento-app php bin/magento setup:di:compile` and  other commands, taking too much time to execute.
I have understood magento 2 is too much slow out of the box.
I am searching for how to reduce time, by caching or any other approach.


##### IV A. Developer mode skips many optimizations and is best for local development.

Set developer mode:

```bash
docker exec -it magento-app php bin/magento deploy:mode:set developer
php bin/magento cache:enable
```

##### IV B. `Then tried to disable unnecessary modules.`
Ensure you’re in developer mode to avoid di:compile:

```bash
docker exec -it magento-app php bin/magento deploy:mode:show
```

If not in developer mode, set it:
```bash
docker exec -it magento-app php bin/magento deploy:mode:set developer
```
Then 
```bash
docker exec -it magento-app php bin/magento module:disable <module name>
```

##### IV C. Also thinking of enabling opcache in php.ini :
```ini
opcache.enable=1
opcache.memory_consumption=512
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.validate_timestamps=1
opcache.revalidate_freq=0
```

##### IV D. Do not mount unecessary directory 

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
Then compile everything :
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
php bin/magento cache:clean

```

🎉 🥳 Really speeds up all the commands, which was previously frustrating. Horray


### 🌟 Key Features - Customized docker setup

✅ **Magento 2.4.7** with **PHP 8.1** (stable for Magento)  
✅ **Redis** for caching and session management  
✅ **Elasticsearch 7.17.9** for fast and scalable search  
✅ **MySQL 8.0** for robust relational database storage  
✅ **Nginx** with optimized configuration for Magento  
✅ **Supervisor** for managing PHP-FPM processes  
✅ **Multi-stage Dockerfile** for efficient builds  
✅ **Composer** with Magento Marketplace authentication  
✅ **Flexible ENV support** with `.env.local`, `.env.production`, etc.  
✅ **Windows-friendly** with `dos2unix` for script compatibility  
✅ **CI/CD Ready** for GitHub Actions, Jenkins, or other pipelines  
✅ **Customizable startup** via `docker/startup.sh`  

---

## The Development Battle with limited time

3-2-1 Development battle begins, oh my god I am running out of time.

Directory structure
```txt
src/app/code/Strativ/ProductTags/
│   composer.json
│   README.md
│   registration.php
│   tree.txt
│   
├───Block
│   ├───Adminhtml
│   │   └───Tag
│   │           Grid.php
│   │           
│   └───Product
│           Tags.php
│           
├───Controller
│   ├───Adminhtml
│   │   └───Tag
│   │           Index.php
│   │           
│   └───Tag
│           View.php
│           
├───etc
│   │   db_schema.xml
│   │   di.xml
│   │   module.xml
│   │   
│   ├───adminhtml
│   │       menu.xml
│   │       routes.xml
│   │       
│   ├───frontend
│   │       routes.xml
│   │       
│   └───ui_component
├───Model
│   │   ProductTags.php
│   │   
│   ├───Repository
│   │       ProductTagsRepository.php
│   │       
│   └───ResourceModel
│       │   ProductTags.php
│       │   
│       └───ProductTags
│               Collection.php
│               
├───Plugin
│   └───Product
│           Save.php
│           
├───Setup
│       InstallData.php
│       
├───Ui
│   ├───Component
│   │   └───Listing
│   │       └───Columns
│   │               TagActions.php
│   │               
│   └───DataProvider
│       │   TagDataProvider.php
│       │   
│       └───Product
│           └───Form
│               └───Modifier
│                       Tags.php
│                       
└───view
    ├───adminhtml
    │   ├───layout
    │   │       catalog_product_edit.xml
    │   │       strativ_tag_index.xml
    │   │       
    │   └───ui_component
    │           strativ_product_tags_listing.xml
    │           
    └───frontend
        ├───layout
        │       producttags_tag_view.xml
        │       
        ├───templates
        │   └───product
        │           tags.phtml
        │           
        └───web
            └───css
                    tags.css
```

After setting up the file, lets enable the module :
```bash
docker exec -it magento-app rm -rf generated/*
docker exec -it magento-app rm -rf pub/static/adminhtml/*
docker exec -it magento-app rm -rf var/cache/*
docker exec -it magento-app rm -rf var/page_cache/*
docker exec -it magento-app rm -rf var/view_preprocessed/*

# enable the module
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
```

I have compiled this command to `execute ./docker/magento-scripts/update.sh` , so that you can use it by just one command.

---

## 📦 Installation Instructions
1. **Clone Repository**:
   ```bash
   git clone https://github.com/your-username/strativ-product-tags.git
   cd strativ-product-tags
   ```

2. **Start Docker**:
   ```bash
   ./start.sh
   ```

3. **Install Dependencies**:
   If docker setup start.sh do not run in background, then manually run -
   ```bash
   docker exec -it magento-app composer install --no-interaction --prefer-dist --optimize-autoloader
   ```

4. **Enable Module**:
   Run the setup script:
   ```bash
   ./docker/magento-scripts/update.sh
   ```

---

### Use 
- Log in to your admin panel at http://localhost/admin_q2macny using the credentials from your .env file (MAGENTO_ADMIN_USER=admin, MAGENTO_ADMIN_PASSWORD=admin123).
- Navigate to **Catalog > Products** in the left sidebar.
- Click Edit on any existing product or click Add Product to create a new one.
- Scroll to the Content section in the product form.
- Look for a field labeled Strativ Tags (it appears below the product description field).
- Enter comma-separated tags (e.g., `sale, new, featured`).
- Save the product.
- (Optional) View tags on the frontend product page or navigate to `/producttags/tag/[tag-name]` to see products with that tag.

---

## 🎉 Achievements & Bonus Features
- **Core Requirements**: Implemented the “Strativ Tags” field, database table, and frontend display.
- **Bonus Features**:
  - Custom admin page for tag management (`Strativ > Product Tags` in the admin panel).
  - Tag sanitization in `Model/Repository/ProductTagsRepository.php` (lowercase, alphanumeric tags).
  - Used dependency injection and repositories for maintainable code.
- **Performance Optimizations**: Reduced command execution time with developer mode, OPcache, and selective volume mounts.
- **Automation**: Created `update.sh` for one-command module setup.

---

## 🐞 Known Issues & Future Improvements
- **Strativ Tags Field**: Initially didn’t display due to UI component conflicts. Resolved, but further testing is needed for edge cases.
- **Frontend Tag Page**: The `/producttags/tag/[tag-name]` route works but could use enhanced styling and pagination.
- **Performance**: Magento remains slow in some cases; exploring Redis or Varnish caching could help.
- **Validation**: Basic tag sanitization is implemented; advanced validation (e.g., max length) could be added.

---

---

## 📚 Learning Journey

### Approach to Learning Magento 2
- **Magento DevDocs**: Primary resource for module structure, UI components, and dependency injection ([devdocs.magento.com](https://devdocs.magento.com/)).
- **Community Resources**:
  - Stack Overflow for common errors (e.g., autoload issues).
  - Blog posts on Magento module development (e.g., [Mageplaza tutorials](https://www.mageplaza.com/)).
- **AI Assistance**: Used AI tools to clarify Magento concepts and debug errors, documenting all solutions in this README.
- **Trial and Error**: Experimented with configurations to understand Magento’s flow, especially UI components and plugins.

### Key Takeaways
- **Module Structure**: Learned the importance of `registration.php`, `module.xml`, and `di.xml`.
- **UI Components**: Mastered adding custom fields to the product form via modifiers.
- **Performance**: Gained insights into optimizing Magento for development with developer mode and OPcache.
- **Dependency Injection**: Used repositories and plugins for clean code organization.

### Helpful Resources
- [Magento DevDocs: Module Development](https://devdocs.magento.com/guides/v2.4/extension-dev-guide/module-development.html)
- [Mageplaza: Create Custom Module](https://www.mageplaza.com/magento-2-module-development/)
- [Stack Overflow: Magento 2 Static Content 404](https://stackoverflow.com/questions/tagged/magento2)
- [PHP OPcache Guide](https://www.php.net/manual/en/book.opcache.php)

---

## 🙏 Addressing the Delay
The submission was delayed due to:
- **Custom Setup**: Building a tailored Docker environment took extra time but ensured long-term efficiency.
- **Learning Curve**: Magento 2’s complexity required deep exploration of DevDocs and community resources.
- **Performance Tuning**: Addressing slow commands was critical for a productive workflow.

I prioritized a robust, reusable setup over a quick solution, aligning with the assignment’s emphasis on learning and resourcefulness. This approach, while time-intensive, equipped me with skills to tackle future Magento projects effectively.

---

## 🔮 Final Thoughts
This assignment was a thrilling dive into Magento 2, blending PHP expertise with framework exploration. Despite the “development battle” and time constraints, I delivered a functional module, optimized my environment, and documented my journey. I’m excited to continue learning Magento and apply these skills in real-world projects.

Thank you for the opportunity to showcase my abilities! 🚀
