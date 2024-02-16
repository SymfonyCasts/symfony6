# Comandos de perfilado

En el entorno de desarrollo de nuestro sitio, tenemos la barra de herramientas de depuración web. Y lo que es más importante, el perfilador, que está repleto de cosas buenas. Incluso si nuestra aplicación es enteramente una API, podemos ir directamente a `/_profiler` para consultar el perfilador de cualquier petición de API.

Esta es una de las características estrella de Symfony. Y para la 6.4, el colaborador de Symfony [Jules Pietri](https://twitter.com/julespietri) se preguntó: ¿por qué no podemos tener esto para los comandos de la consola?

Y ahora, ¡lo tenemos! Está pensado para que lo utilices para tus comandos de consola personalizados que puedan ser grandes o complejos, pero también podemos utilizarlo con comandos del núcleo.

## Activar un perfil: --perfil

Gira y ejecuta:

```terminal
php bin/console debug:container
```

Si ejecutas un comando normal, no activará el sistema de perfiles ni recopilará información. Para activarlo, tienes que ejecutar el comando con `--profile`.

```terminal-silent
php bin/console debug:container --profile
```

Nada parece diferente, pero eso acaba de activar el perfilador... que recoge información y la almacena... en algún sitio. Pero... ¡no es obvio dónde podemos ir a verla!

Así que lo que realmente quieres hacer es pasar `-v`:

```terminal-silent
php bin/console debug:container --profile -v
```

Ahora, en la parte inferior, incluye el token único que se puede utilizar en la URL del perfilador. Pero, en realidad, sé más perezoso y ejecútalo con `-vvv`:

```terminal-silent
php bin/console debug:container --profile -vvv
```

Esta vez, obtendremos un enlace, e incluso detalles sobre la memoria y el tiempo. Hago clic en el enlace y... no funciona. Es casi la URL correcta, pero mi terminal no sabe qué puerto utiliza mi servidor web local. Copia ese token, luego... ve al perfilador de cualquier petición, pega el token en la URL y... ¡genial!

## Explorando el Perfilador

Vemos información sobre el comando, la entrada, la salida... y lo más importante, ¡tenemos las secciones normales del perfilador! Una interesante es la de eventos: muestra los eventos reales que se enviaron y los oyentes de cada uno. Éstos son totalmente distintos de los eventos que se activan durante una petición, así que está bien verlos.

Probablemente te hayas dado cuenta de que la mayoría de las secciones del perfil están en gris. Pero si renderizaras una plantilla Twig... o hicieras una petición HTTP o una consulta a la base de datos, se activarían.

Incluso con este sencillo comando, desbloqueamos la sección de rendimiento. No es mucho en este caso, pero me hace sentir peligroso.

Así que ¡ya está! Otra función genial y bien pensada. Me encantaría ver cómo acaba utilizando esto la gente.

Bien, pasemos a nuestro tema final: experimentemos con uno de los mejores componentes nuevos de Symfony: Scheduler.
