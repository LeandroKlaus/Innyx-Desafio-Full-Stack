# Desafio Full Stack INNYX - Front-end

Este repositório contém o código-fonte do front-end para o desafio de Desenvolvedor Full Stack da INNYX, desenvolvido com Vue.js.

## 🚀 Sobre o Projeto

A aplicação é uma SPA (Single Page Application) que consome a API do back-end para permitir que usuários autenticados gerenciem um catálogo de produtos. A interface é responsiva, intuitiva e fornece feedback visual para as ações do usuário.

---

## 🛠️ Tecnologias Utilizadas

- **Vue.js 3**: Framework progressivo para a construção de interfaces de usuário. Foi utilizado com a **Composition API (`<script setup>`)** conforme solicitado.
- **TypeScript**: Adiciona tipagem estática ao JavaScript, aumentando a segurança e a manutenibilidade do código.
- **Vite**: Ferramenta de build moderna que proporciona um ambiente de desenvolvimento extremamente rápido.
- **Vue Router**: Para gerenciamento das rotas da SPA.
- **Pinia**: Biblioteca de gerenciamento de estado, utilizada para controlar o estado de autenticação do usuário em toda a aplicação.
- **Vuetify 3**: Biblioteca de componentes UI baseada em Material Design. Foi escolhida por sua vasta gama de componentes prontos, que aceleram o desenvolvimento e garantem um design consistente e responsivo.
- **Axios**: Cliente HTTP para realizar as chamadas para a API Laravel.
- **@mdi/font**: Biblioteca de ícones Material Design utilizada em conjunto com o Vuetify.

---

## ⚙️ Como Configurar e Executar o Projeto

Siga os passos abaixo para executar a aplicação localmente.

**Pré-requisitos:**
- Node.js >= 20.x
- NPM ou Yarn

**Passos:**

1.  **Clone o repositório:**
    ```bash
    git clone [https://github.com/LeandroKlaus/innyx-desafio-frontend.git](https://github.com/LeandroKlaus/innyx-desafio-frontend.git)
    cd innyx-desafio-frontend
    ```

2.  **Instale as dependências do projeto:**
    ```bash
    npm install
    ```

3.  **Configure a URL da API:**
    - Verifique no arquivo `src/services/api.ts` se a constante `API_URL` aponta para o endereço onde seu back-end está rodando (por padrão, `http://127.0.0.1:8000/api`).

4.  **Inicie o servidor de desenvolvimento:**
    ```bash
    npm run dev
    ```

A aplicação estará disponível em `http://localhost:5173` (ou a porta indicada no terminal).