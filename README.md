# 💼 Lavoro Challenge

Este proyecto es una aplicación web desarrollada en **Laravel 12** con **React + Inertia.js** que permite consultar y almacenar los tipos de cambio de SUNAT, filtrarlos por rango de fechas y exportarlos a un archivo Excel.

---

## 📌 Requisitos

Antes de comenzar, asegúrate de tener instalado:

- [PHP 8.2+](https://www.php.net/)
- [Composer](https://getcomposer.org/)
- [Node.js 18+](https://nodejs.org/)
- [npm](https://www.npmjs.com/)
- [MySQL o PostgreSQL](https://www.mysql.com/)
- [Git](https://git-scm.com/)

---

## 🚀 Instalación paso a paso

### 1. Clonar el repositorio

```bash
git clone https://github.com/aatari88/lavoro_challange.git
cd lavoro_challange
```

### 2. Configurar el entorno
```bash
//Copiá el archivo de ejemplo del entorno:
cp .env.example .env

// Generá la clave de la aplicación:
php artisan key:generate
```

### 3. Configurar base de datos
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lavoro_db
DB_USERNAME=root
DB_PASSWORD=tu_password
```

### 4. Instalar dependencias
```bash
composer install

npm install
```

### 4. Migraciones
```bash
php artisan migrate
```

### 4. Levantar el proyecto en local
```bash
composer run dev
```





