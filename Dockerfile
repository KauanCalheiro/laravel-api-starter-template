FROM php:8.3-cli

###########################################################################################
### Instalação de dependências de sistema e extensões necessárias para o PHP           ###
###########################################################################################

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    libxml2-dev \
    libonig-dev \
    libpq-dev \
    build-essential \
    pkg-config \
    tzdata \
    cron \
    supervisor \
    caddy \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        zip \
        opcache \
        intl \
        bcmath \
        mbstring \
        sockets \
        xml \
        soap \
        pcntl \
        exif \
        ctype \
        fileinfo

###########################################################################################
### Instalação do Composer                                                             ###
###########################################################################################

# Copia o binário do Composer da imagem oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

###########################################################################################
### Setup do Projeto Laravel                                                           ###
###########################################################################################

# Define o diretório de trabalho da aplicação
WORKDIR /var/www/html

# Copia todos os arquivos do projeto para o container
COPY . .

# Instala as dependências do Composer em modo de produção (sem require-dev)
RUN composer install --optimize-autoloader --no-dev

# Gera a chave de aplicação do Laravel
RUN php artisan key:generate --force
RUN php artisan jwt:secret --force

###########################################################################################
### Setup Laravel Octane com FrankenPHP e Caddy                                         ###
###########################################################################################

# Instala o Octane com suporte ao servidor FrankenPHP
RUN php artisan octane:install --server=frankenphp --force --no-interaction

# Formata o Caddyfile automaticamente
RUN caddy fmt --overwrite config/octane/Caddyfile

# Cria diretórios para configuração e dados do Caddy e define permissões
RUN mkdir -p /var/www/html/storage/caddy/data \
             /var/www/html/storage/caddy/config \
    && chown -R www-data:www-data /var/www/html/storage/caddy

# Define variáveis de ambiente para que o Caddy use os diretórios do Laravel
ENV XDG_DATA_HOME=/var/www/html/storage/caddy/data
ENV XDG_CONFIG_HOME=/var/www/html/storage/caddy/config

###########################################################################################
### Configuração do Cron                                                               ###
###########################################################################################

# Copia a definição de cron personalizada para o sistema
COPY docker/cron /etc/cron.d/cron

# Define permissões apropriadas para o cron
RUN chmod 0644 /etc/cron.d/cron

# Cria o log do cron e define proprietário como www-data
RUN touch /var/log/cron.log \
 && chown www-data:www-data /var/log/cron.log

###########################################################################################
### Configuração do Supervisor                                                         ###
###########################################################################################

# Copia o arquivo de configuração do Supervisor para gerenciamento de serviços
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Cria o diretório de log do Supervisor e define as permissões
RUN mkdir -p /var/log/supervisor && chown -R www-data:www-data /var/log/supervisor

###########################################################################################
### Permissões finais                                                                  ###
###########################################################################################

# Garante que toda a estrutura do projeto pertence ao usuário www-data
# Define permissões básicas para toda a aplicação
# Ajusta permissões das pastas necessárias para escrita pelo Laravel
RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 770 /var/www/html/storage /var/www/html/bootstrap/cache \
 && chmod -R 755 /var/www/html

###########################################################################################
### Exposição e inicialização                                                          ###
###########################################################################################

# Expõe a porta utilizada pelo Laravel Octane
EXPOSE 8000

# Comando principal para iniciar o Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
