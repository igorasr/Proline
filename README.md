### Proline Interview Project

**Pré-requisitos:**  
- Docker instalado  
- Composer instalado  
- Node.js (v20+) e npm instalados  
- Laravel Sail configurado

#### Como rodar o projeto

1. Instale as dependências do projeto:
  ```bash
  composer install
  npm install
  ```

2. Copie o arquivo de exemplo de ambiente e ajuste as credenciais padrão do Sail:
  ```bash
  cp .env.example .env
  ```

3. Inicie o ambiente:
  ```bash
  ./vendor/bin/sail up -d
  OU
  docker compose up -d
  ```

4. As migrations devem rodas automaticamente, caso contrário execute as migrations para preparar o banco de dados:
  ```bash
  docker exec -it proline_api php artisan migrate
  ```

5. Aguarde todos os serviços subirem e acesse o projeto em `http://localhost:8080/app/envio` no seu navegador.


### Arquivo para teste

O Arquivo JSON disponibilizado via e-mail se encontra na raiz do projeto com nome `proline.json`