FROM node:22

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    lsb-release \
    ca-certificates \
    apt-transport-https \
    software-properties-common \
    && rm -rf /var/lib/apt/lists/*

RUN curl -sSL https://packages.sury.org/php/README.txt | bash - \
    && apt-get update && apt-get install -y \
    php8.2 \
    php8.2-cli \
    php8.2-common \
    php8.2-curl \
    php8.2-mbstring \
    php8.2-xml \
    php8.2-zip \
    php8.2-sqlite3 \
    php8.2-bcmath \
    && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . .

RUN composer install --no-interaction --optimize-autoloader

RUN npm install

RUN npm run build

EXPOSE 8000
EXPOSE 5173

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]

CMD ["npx", "concurrently", "-c", "#93c5fd,#c4b5fd,#fb7185,#fdba74", "php artisan serve --host 0.0.0.0", "php artisan queue:listen --tries=1", "php artisan schedule:work -v", "php artisan pail --timeout=0", "npm run dev --host 0.0.0.0", "--names=server,queue,logs,vite"]
