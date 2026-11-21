# FarmLink Jamaica

CS334 Applied Web Programming Group Project

## Project Description
FarmLink Jamaica is a web-based marketplace
that connects farmers with buyers.

Farmers can list agricultural products, while
buyers can browse, search, and contact sellers.

## Technologies
- HTML, CSS, JavaScript, Bootstrap
- PHP
- MariaDB
- Docker
- Git and GitHub

## Team Members
- Member 1:
- Member 2:

## Project Status
Initial setup in progress.

# AgriReach

This is a **frontend prototype**. Product data is stored in `public/includes/data.php` as examples, not in MySQL. Login and registration do not save data. The dashboard shows example records.

## Install into your existing repository

1. Back up your existing `compose.yaml`, `docker/Dockerfile`, `public/index.php`, and `.gitignore` if you have customized them.
2. Copy the contents of this package into your repository, preserving the folders. Do not overwrite your existing `database/schema.sql`.
3. Create a `.env` in the repository root using `.env.example` and fill in your **existing** MySQL root and user passwords. Never commit `.env`.
4. IMPORTANT: The Docker Compose volume name depends on the Compose project name. Running from the same repository folder keeps your existing named volume. Do not run `docker compose down -v`.
5. In the repository root run `docker compose config` and `docker compose up -d --build`.
6. Visit http://localhost:8080 and test the navigation, marketplace filters, and product pages.

## Important file responsibilities

- `public/includes/header.php` — shared navigation and page opening.
- `public/includes/footer.php` — shared footer and JavaScript.
- `public/includes/data.php` — temporary sample products and helper functions.
- `public/includes/product-card.php` — reusable product card.
- `public/assets/css/style.css` — website design and responsive styling.
- `public/assets/js/main.js` — preview form messages.
- `docker/Dockerfile` — PHP and Apache image with `/public` as document root.
- `compose.yaml` — PHP and MySQL containers; uses `.env` credentials.

## Next batch

Secure PHP PDO connection, registration/login/logout, sessions, farmer authorization, real listings from MySQL, and CRUD.
