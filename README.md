# SICREG

Sistema de Registro de Eventos.

## Requisitos

- PHP 8.2+
- Composer
- MySQL
- Node.js (opcional)

## Instalación

1. Clonar repositorio:

2. Entrar al proyecto:
cd SICREG

3. Instalar dependencias:
composer install

4. Copiar archivo de entorno:
copy .env.example .env

5. Configurar base de datos en .env

6. Generar key:
php artisan key:generate

7. Importar base de datos:
Importar el archivo database/bdsicreg.sql en MySQL

8. Ejecutar migraciones (si es necesario):
php artisan migrate

9. Ejecutar servidor:
php artisan serve
