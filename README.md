# 🚀 Magento 2.4.7 + PHP 8.1 Docker Template  
> **Modern Magento 2 Starter Kit** with **MySQL**, **Elasticsearch**, **Redis**, **Nginx**, and a clean Docker setup.

---

## 🌟 Key Features

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

## ⚙️ Setup Instructions

### Prerequisites
- **Docker** and **Docker Compose** installed
- **Git** for cloning the repository
- Magento Marketplace credentials (add to `auth.json`)

### 1. Clone and Prepare the Environment
```bash
git clone https://github.com/ShaonMajumder/docker-template_magento-2-php-8.1.git docker-template_magento-2-php-8.1
cd docker-template_magento-2-php-8.1
./
```

### 2. Configure Environment
Copy the example environment file and update with your settings:
```bash
cp docker/environment/.env.example .env
```

Edit `.env` to match your setup (e.g., database credentials, ports):
```env
MAGENTO_HOST=localhost
MAGENTO_ADMIN_USER=admin
MAGENTO_ADMIN_PASSWORD=admin123!@#
MAGENTO_ADMIN_EMAIL=admin@example.com
DB_USERNAME=magento
DB_PASSWORD=magento
MYSQL_ROOT_PASSWORD=root
NGINX_PORT=80
ELASTICSEARCH_MEMORY=512m
```

Add your Magento Marketplace credentials to `auth.json`:
```json
{
  "http-basic": {
    "repo.magento.com": {
      "username": "<your-public-key>",
      "password": "<your-private-key>"
    }
  }
}
```

### 3. Build and Run
Start the Docker containers:
```bash
docker-compose --env-file .env up -d --build
```

This will:
- Build the `app` image with PHP 8.1, Composer, and dependencies.
- Start `nginx`, `mysql`, `elasticsearch`, and `redis` services.
- Run `docker/startup.sh` to install Magento 2 in `/var/www/html/src/`.

### 4. Build Magento
Compile static content and dependencies:
```bash
docker exec magento-app bash -c "cd /var/www/html/src && php bin/magento setup:static-content:deploy -f"
docker exec magento-app bash -c "cd /var/www/html/src && php bin/magento setup:di:compile"
docker exec magento-app bash -c "cd /var/www/html/src && php bin/magento cache:flush"
```

### 5. Set Permissions (if needed)
Fix permissions if you encounter issues:
```bash
docker exec magento-app bash -c "chown -R www-data:www-data /var/www/html/src"
docker exec magento-app bash -c "chmod -R 755 /var/www/html/src"
docker exec magento-app bash -c "find /var/www/html/src -type d -exec chmod 755 {} \;"
docker exec magento-app bash -c "find /var/www/html/src -type f -exec chmod 644 {} \;"
docker exec magento-app bash -c "chmod -R 770 /var/www/html/src/var /var/www/html/src/pub/media /var/www/html/src/pub/static"
```

---

## 🌐 Access Points

| Service       | URL                         | Description                     |
|---------------|-----------------------------|---------------------------------|
| Magento       | http://localhost:${NGINX_PORT} | Magento Frontend                |
| Admin Panel   | http://localhost:${NGINX_PORT}/admin | Magento Admin (use `MAGENTO_ADMIN_USER`/`MAGENTO_ADMIN_PASSWORD`) |
| MySQL         | localhost:${MYSQL_PORT}     | MySQL DB (`DB_USERNAME`/`DB_PASSWORD`) |
| Elasticsearch | localhost:${ELASTICSEARCH_PORT} | Elasticsearch Instance          |
| Redis         | localhost:${REDIS_PORT}     | Redis Instance                  |

---

## 🧰 Developer Notes

- **Magento Root**: Magento is installed in `/var/www/html/src/` (mapped to `./src` on the host). Update `docker-compose.yml` and `docker/nginx/magento.conf` if you change this path.
- **Startup Script**: Modify `docker/startup.sh` to customize installation or service checks.
- **Nginx Configuration**: Adjust `docker/nginx/magento.conf` for custom routing or performance tweaks (e.g., caching, gzip).
- **Composer Cache**: Persisted in `./composer_cache` for faster builds.
- **Windows Compatibility**: Uses `dos2unix` in the `Dockerfile` to handle line endings.
- **Environment Files**: Supports multiple `.env` files (e.g., `.env.local`, `.env.production`) in `docker/environment/`.
- **Frontend Development**: Install Node.js in the `app` container for theme development (e.g., Grunt):
  ```bash
  docker exec magento-app bash -c "apt-get update && apt-get install -y nodejs npm"
  docker exec magento-app bash -c "cd /var/www/html/src && npm install && npm install -g grunt-cli"
  ```

---

## 🛡️ Security & Scaling Tips

- **HTTPS**: Use a reverse proxy (e.g., Traefik) or Let's Encrypt for SSL.
- **Environment Security**: Store sensitive data (e.g., `auth.json`, `.env`) securely and avoid committing to Git.
- **Scaling**: Use Docker Swarm or Kubernetes to scale `app` and `nginx` services.
- **Performance**: Enable Magento's production mode (`php bin/magento deploy:mode:set production`) and configure Redis for full-page caching.
- **Monitoring**: Add Prometheus/Grafana for observability or use Magento's built-in logs (`/var/www/html/src/var/log/`).
- **Backups**: Regularly back up `dbdata` and `esdata` volumes.

---

## 🧠 Why this Boilerplate?

This setup is ideal for:

- **E-commerce Developers** building scalable Magento stores.
- **Startups** launching online shops with robust search and caching.
- **DevOps Engineers** setting up production-ready environments.
- **Freelancers** delivering professional Magento projects quickly.
- **Job Candidates** showcasing Docker, PHP, and Magento expertise.

---

## 📜 License

MIT – use freely, contribute respectfully.

---

## <a id="credit"></a>👨‍💻 Built & Maintained By

👔 Ready to join a team building high-impact systems
📨 Let’s connect for Backend, DevOps, Architect, Engineering Manager or System Design roles

**Shaon Majumder**  
Senior Software Engineer  
Open source contributor | Laravel ecosystem & Scalibility expert | System design advocate  
🔗 [LinkedIn](https://linkedin.com/in/shaonmajumder) • [Portfolio](https://github.com/ShaonMajumder)