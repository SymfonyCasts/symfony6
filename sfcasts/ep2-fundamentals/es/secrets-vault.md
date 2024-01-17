# La Bóveda de los Secretos

No quiero adentrarme demasiado en el despliegue, pero vamos a hacer un curso rápido de "Cómo desplegar tu aplicación Symfony 101". Esta es la idea.

## Despliegue 101

Paso 1: Tienes que llevar de alguna manera todo tu código comprometido a tu máquina de producción y luego ejecutar

```terminal
composer install
```

para rellenar el directorio `vendor/`.

Paso 2: crea de algún modo un archivo `.env.local` con todas tus variables de entorno de producción, que incluirá `APP_ENV=prod`, para que estés en el entorno de producción.

Y Paso 3: ejecuta

```terminal
php bin/console cache:clear
```

que borrará la caché en el entorno de producción, y luego

```terminal
php bin/console cache:warmup
```

para "calentar" la caché. Puede haber algunos otros comandos, como ejecutar las migraciones de tu base de datos... pero esta es la idea general. Y los documentos de Symfony tienen más detalles.

Por cierto, en caso de que te lo preguntes, desplegamos a través de https://platform.sh, utilizando la integración en la nube de Symfony... que se encarga de muchas cosas por nosotros. Puedes comprobarlo entrando en https://symfony.com/cloud. También ayuda a apoyar el proyecto Symfony, así que todos salimos ganando.

## Utiliza variables de entorno reales cuando sea posible

De todos modos, la parte más complicada del proceso es el paso 2: crear el archivo `.env.local`con todos tus valores de producción, que incluirán cosas como las claves de la API, los detalles de la conexión a la base de datos y más.

Ahora bien, si tu plataforma de alojamiento te permite almacenar variables de entorno reales directamente dentro de ella, ¡problema resuelto! Si estableces variables de entorno reales, entonces no hay necesidad de gestionar un archivo `.env.local` en absoluto. En cuanto despliegues, Symfony verá y utilizará instantáneamente las variables de entorno reales. Eso es lo que hacemos para Symfonycasts.

## ¿Crear el .env.local durante el despliegue?

Pero si eso no es una opción para ti, tendrás que dar de alguna manera a tu sistema de despliegue acceso a tus valores sensibles para que pueda crear el archivo`.env.local`. Pero... como no vamos a consignar ninguno de estos valores en nuestro repositorio, ¿dónde debemos almacenarlos?

Una opción para manejar los valores sensibles es la bóveda de secretos de Symfony. Es un conjunto de archivos que contienen variables de entorno de forma encriptada. Estos archivos son seguros para enviarlos a tu repositorio... ¡porque están encriptados!

## Creando la Bóveda dev

Si quieres almacenar secretos en una bóveda, necesitarás dos: una para el entorno`dev` y otra para el entorno `prod`. Primero vamos a crear estas dos bóvedas... y luego te explicaré cómo leer valores de ellas.

Empieza creando una para el entorno `dev`. Ejecuta:

```terminal
php bin/console secrets:set
```

Pasa este `GITHUB_TOKEN`, que es el secreto que queremos establecer. A continuación, nos pide nuestro "valor secreto". Como se trata de la bóveda del entorno `dev`, queremos poner algo que sea seguro para que todos lo vean. En un momento explicaré por qué. Diré `CHANGEME`. No puedes verme escribir eso... sólo porque Symfony lo oculta por razones de seguridad.

Como este es el primer secreto que hemos creado, Symfony creó automáticamente la bóveda de secretos entre bastidores... que es literalmente un conjunto de archivos que viven en `config/secrets/dev/`. Para la bóveda dev, vamos a confirmar todos estos archivos en el repositorio. Vamos a hacerlo. Añade todo el directorio de secretos:

```terminal-silent
git add config/secrets/dev
```

Luego haz el commit con:

```terminal
git commit -m "adding dev secrets vault"
```

## Los archivos de la bóveda de secretos

He aquí una explicación rápida de los archivos. `dev.list.php` almacena una lista de los valores que viven dentro de la bóveda, `dev.GITHUB_TOKEN.28bd2f.php` almacena el valor cifrado real, y `dev.encrypt.public.php` es la clave criptográfica que permite a los desarrolladores de tu equipo añadir más secretos. De modo que si otro desarrollador se baja del proyecto, tendrá este archivo... para poder añadir más secretos. Por último, `dev.decrypt.private.php` es la clave secreta que nos permite desencriptar y leer los valores de la bóveda.

En cuanto los archivos de la bóveda estén presentes, Symfony los abrirá automáticamente, descifrará los secretos y los expondrá como variables de entorno Pero, más sobre esto en unos minutos.

## ¿Acervar la clave de desencriptación dev?

Pero espera: ¿realmente acabamos de confirmar la clave `decrypt` en el repositorio? Sí, ¡eso normalmente sería un no-no! ¿Por qué te tomarías la molestia de encriptar valores... sólo para almacenar la clave de desencriptación junto a ellos?

La razón por la que hacemos esto es que se trata de nuestra bóveda de desarrollo, lo que significa que sólo vamos a almacenar valores que sean seguros para que los vean todos los desarrolladores. La bóveda de `dev` sólo se utilizará para el desarrollo local... y queremos que nuestros compañeros de equipo puedan bajar el código y leerlo sin problemas.

Bien, en este punto tenemos una bóveda `dev` que Symfony utilizará automáticamente en el entorno `dev`. Siguiente: vamos a crear la bóveda prod, que contendrá los valores verdaderamente secretos. Luego aprenderemos la relación entre los secretos de la bóveda y las variables de entorno... así como una forma fácil de visualizar todo esto.
