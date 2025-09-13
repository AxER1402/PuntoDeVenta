#  Punto de Venta - Laravel

Este proyecto es un **sistema de punto de venta** desarrollado en **Laravel**.  
Incluye autenticación, gestión de facturas y base de datos con migraciones.

---

## 🚀 Requisitos previos

Asegúrate de tener instalado en tu máquina:

- [PHP >= 8.1](https://www.php.net/downloads.php)
- [Composer](https://getcomposer.org/)
- [Node.js y npm](https://nodejs.org/) (para assets con Vite)
- [MySQL o MariaDB](https://www.mysql.com/) (u otro motor soportado por Laravel)
- [Git](https://git-scm.com/)

---

## ⚙️ Instalación

1. Clonar el repositorio:
   ```bash
   git clone https://github.com/AxER1402/PuntoDeVenta.git
   cd PuntoDeVenta



## Instalar dependencias de PHP:

composer install


## Generar la clave de aplicación:

php artisan key:generate


## Configurar en el archivo .env los datos de conexión a la base de datos:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=punto_venta
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

--- 

#  Ejecutar las migraciones para crear las tablas:

php artisan migrate


## Si deseas cargar datos iniciales (usuarios, roles, etc.):

php artisan db:seed


## Levantar el servidor de desarrollo de Laravel:

php artisan serve
La aplicación estará disponible en:
👉 http://localhost:8000