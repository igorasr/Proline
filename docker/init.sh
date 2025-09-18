#!/bin/sh

# =====================================================
# Script de Entrypoint para inicialização do container
# =====================================================

# Instalar dependências do Composer se vendor não existir
if [ ! -d vendor ]; then
    echo "[INFO] Instalando dependências PHP via Composer"
    composer install --no-interaction --prefer-dist
else
    echo "[INFO] Dependências já instaladas, pulando Composer"
fi

# Gerar chave do Laravel se não existir
APP_KEY=$(grep APP_KEY .env | cut -d '=' -f2)
if [ -z "$APP_KEY" ]; then
    echo "[INFO] Gerando APP_KEY do Laravel"
    php artisan key:generate
else
    echo "[INFO] APP_KEY já existente, pulando key:generate"
fi

# Rodar migrations se tabela users não existir (exemplo)
if ! php artisan migrate:status | grep -q 'users'; then
    echo "[INFO] Rodando migrations do Laravel"
    php artisan migrate --force
else
    echo "[INFO] Migrations já aplicadas, pulando migrate"
fi

echo "[INFO] Inicialização concluída"