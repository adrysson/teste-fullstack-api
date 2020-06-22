# teste-fullstack
Repositório usado para a avaliação técnica da Mesha Tecnologia.

## Instruções de instalação
O projeto foi desenvolvido com docker, então basta ter o docker e o docker-compose instalado para conseguir rodar o projeto.
1. Clone o projeto:
```
git clone git@github.com:adrysson/teste-backend.git
```
2. Copie o arquivo de exemplo das variáveis de ambiente:
```
cp .env.example .env
```
3. Personalize as seguintes variáveis de ambiente:
```
APP_NAME
APP_URL

DB_PORT
DB_DATABASE
DB_USERNAME
DB_PASSWORD

NGINX_PORT
POSTGRES_PORT
```
4. Suba os containers da aplicação:
```
docker-compose up -d
```
5. Instale as dependências:
```
docker-compose exec app composer install
docker-compose exec app npm install
```
6. Gere a chave do Laravel:
```
docker-compose exec app php artisan key:generate
```
7. Gere o link simbólico para que o upload de imagens funcione corretamente:
```
docker-compose exec app php artisan storage:link
```
8. Rode as migrations para criar as tabelas no banco:
```
docker-compose exec app php artisan migrate
```
9. A aplicação estará rodando no ambiente local na porta especificada na variável de ambiente "NGINX_PORT".
