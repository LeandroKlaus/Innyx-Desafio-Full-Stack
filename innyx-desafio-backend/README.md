# Desafio Full Stack INNYX - Back-end

Este repositório contém o código-fonte do back-end para o desafio de Desenvolvedor Full Stack da INNYX, desenvolvido em Laravel.

## 🚀 Sobre o Projeto

A aplicação consiste em uma API RESTful para gerenciamento de produtos e categorias. Ela fornece endpoints seguros para um cliente front-end realizar operações de CRUD (Criar, Ler, Atualizar, Deletar) em produtos, além de gerenciar a autenticação de usuários.

---

## 🛠️ Tecnologias Utilizadas

- **PHP 8.2+**
- **Laravel 11+**: Framework robusto e elegante que agiliza o desenvolvimento de aplicações web seguras.
- **MySQL 8.0**: Sistema de gerenciamento de banco de dados relacional.
- **Laravel Sanctum**: Para a autenticação baseada em tokens, garantindo que os endpoints sejam acessados de forma segura. Foi escolhido por sua simplicidade e integração perfeita com o ecossistema Laravel.

---

## ⚙️ Como Configurar e Executar o Projeto

Siga os passos abaixo para executar a aplicação localmente.

**Pré-requisitos:**
- PHP >= 8.2
- Composer
- MySQL
- Um ambiente de desenvolvimento como o Laragon (recomendado)

**Passos:**

1.  **Clone o repositório:**
    ```bash
    git clone [https://github.com/seu-usuario/innyx-desafio-backend.git](https://github.com/seu-usuario/innyx-desafio-backend.git)
    cd innyx-desafio-backend
    ```

2.  **Instale as dependências do PHP:**
    ```bash
    composer install
    ```

3.  **Configure o arquivo de ambiente:**
    - Copie o arquivo `.env.example` para um novo arquivo chamado `.env`.
    - Crie um banco de dados MySQL para o projeto (ex: `innyx_desafio`).
    - Atualize as variáveis de banco de dados no arquivo `.env`:
      ```env
      DB_CONNECTION=mysql
      DB_HOST=127.0.0.1
      DB_PORT=3306
      DB_DATABASE=innyx_desafio
      DB_USERNAME=root
      DB_PASSWORD=
      ```

4.  **Gere a chave da aplicação:**
    ```bash
    php artisan key:generate
    ```

5.  **Execute as migrações e popule o banco de dados:**
    (Este comando criará as tabelas e adicionará categorias de exemplo)
    ```bash
    php artisan migrate --seed
    ```

6.  **Crie o link simbólico para o armazenamento de imagens:**
    ```bash
    php artisan storage:link
    ```

7.  **Inicie o servidor de desenvolvimento:**
    ```bash
    php artisan serve
    ```

A API estará disponível em `http://127.0.0.1:8000`.