# DoarMais

Sistema integrado para gerenciamento de doações, desenvolvido como projeto de estudo e aplicação prática de programação e banco de dados.

## 📌 Sobre o projeto

O **DoarMais** é um sistema que integra:

* 💻 Programa desktop desenvolvido em **C#**
* 🌐 Site desenvolvido em **PHP**
* 🗄️ Banco de dados **MySQL**
* 🔐 Senhas protegidas com **BCrypt**
* 🖼️ Armazenamento de fotos utilizando **Cloudinary**
* 📍 Consulta de endereço através da **API dos Correios (CEP)**

O objetivo é permitir o cadastro e gerenciamento de usuários e doações através de diferentes interfaces conectadas ao mesmo banco de dados.

## 🛠️ Tecnologias utilizadas

* C#
* Windows Forms
* PHP
* HTML
* CSS
* JavaScript
* MySQL
* BCrypt
* Cloudinary
* API dos Correios

## 🗂️ Estrutura

```text
DoarMais/
│
├── C#/
│   └── Projeto
│
├── PHP/
│   └── Site
│
├── Banco de Dados/
│   └── doarmais.sql
│
└── README.md
```

## 🔐 Segurança

As senhas dos usuários não são armazenadas em texto puro.

O sistema utiliza **BCrypt** para gerar o hash das senhas antes de armazená-las no banco de dados.

## 🖼️ Fotos

As imagens das doações são armazenadas no **Cloudinary**.

O banco de dados mantém as informações necessárias para relacionar as imagens às respectivas doações.

## 📍 CEP

O cadastro de endereço utiliza a **API dos Correios** para consulta de informações através do CEP.

## 🗄️ Banco de Dados

O sistema utiliza **MySQL** para armazenar e relacionar as informações de usuários, doações e demais dados do sistema.

## 🔗 Integração

```text
             ┌───────────────┐
             │    Site PHP   │
             └───────┬───────┘
                     │
                     ▼
             ┌───────────────┐
             │     MySQL     │
             │   DoarMais    │
             └───────┬───────┘
                     ▲
                     │
             ┌───────┴───────┐
             │   C# Desktop  │
             │   Windows     │
             │    Forms      │
             └───────────────┘

       ┌──────────────┐
       │  Cloudinary  │ ← Fotos
       └──────────────┘

       ┌──────────────┐
       │ API Correios │ ← CEP
       └──────────────┘
```

## 🎯 Objetivo

Este projeto foi desenvolvido para praticar e demonstrar conhecimentos em:

* Programação C#
* Desenvolvimento Web
* Banco de Dados
* CRUD
* Integração entre sistemas
* APIs
* Autenticação e segurança
* Armazenamento de imagens
* Desenvolvimento de aplicações integradas

## 👨‍💻 Autor

**Célio Lopes**

Projeto desenvolvido para fins de estudo e portfólio.
