FROM php:8.2-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    git unzip curl gnupg \
    libzip-dev libpng-dev libonig-dev libxml2-dev \
    chromium \
    && docker-php-ext-install zip

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Set working dir
WORKDIR /app

# Copy project
COPY . .

# Install Node dependencies
RUN npm install

# Puppeteer fix (biar pakai chromium dari system)
ENV PUPPETEER_EXECUTABLE_PATH=/usr/bin/chromium

# Expose port
EXPOSE 10000

# Run PHP server
CMD ["php", "-S", "0.0.0.0:10000", "index.php"]