<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Sobre el Proyecto

Este proyecto es una aplicación web construida con Laravel y Vue.js. Utiliza varias tecnologías modernas para proporcionar una experiencia de desarrollo robusta y eficiente.

### Tecnologías Utilizadas

- Laravel
- Vue.js
- Vite
- Bootstrap
- PrimeVue
- Axios
- SweetAlert2
- Chart.js

## Rutas del Proyecto

- `/`: Página principal
- `/login`: Página de inicio de sesión
- `/register`: Página de registro
- `/dashboard`: Panel de usuario

## Cómo Clonar el Proyecto

Para clonar el proyecto, ejecuta el siguiente comando en tu terminal:

```bash
git clone https://github.com/tu-usuario/tu-repositorio.git
cd tu-repositorio
```

## Cómo Correr el Proyecto

1. Instala las dependencias de PHP:

```bash
composer install
```

2. Instala las dependencias de Node.js:

```bash
npm install
```

3. Copia el archivo de entorno y genera la clave de la aplicación:

```bash
cp .env.example .env
php artisan key:generate
```

4. Inicia el servidor de desarrollo:

```bash
php artisan serve
npm run dev
```

## Migraciones y Seeders

Para ejecutar las migraciones y seeders, utiliza los siguientes comandos:

1. Ejecuta las migraciones:

```bash
php artisan migrate
```

2. Ejecuta los seeders:

```bash
php artisan db:seed
```

## Contribuyendo

Gracias por considerar contribuir a este proyecto. Las guías de contribución se pueden encontrar en la documentación del proyecto.

## Código de Conducta

Para asegurar que la comunidad sea acogedora para todos, por favor revisa y sigue el [Código de Conducta](https://laravel.com/docs/contributions#code-of-conduct).

## Vulnerabilidades de Seguridad

Si descubres una vulnerabilidad de seguridad, por favor envía un correo electrónico a [tu-email@dominio.com](mailto:tu-email@dominio.com). Todas las vulnerabilidades de seguridad serán atendidas de inmediato.

## Licencia

Este proyecto es software de código abierto licenciado bajo la [licencia MIT](https://opensource.org/licenses/MIT).
