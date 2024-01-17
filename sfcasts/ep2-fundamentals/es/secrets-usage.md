# Lectura de secretos frente a variables de entorno

Acabamos de crear una bóveda de secretos para nuestro entorno `dev`... que contendrá una versión "segura" por defecto de cualquier variable de entorno sensible. Por ejemplo, hemos establecido el valor `GITHUB_TOKEN` como `CHANGEME`.

Ahora vamos a crear la bóveda del entorno `prod`. Hazlo diciendo:

```terminal
./bin/console secrets:set GITHUB_TOKEN --env=prod
```

Esta vez, coge el valor secreto real de `.env.local` y pégalo aquí. Al igual que antes, como no había ya una bóveda de `prod`, Symfony la creó. Y tiene los mismos cuatro archivos que antes. Aunque, hay una sutil, pero importante diferencia.

Añade ese nuevo directorio a git:

```terminal-silent
git add config/secrets/prod
```

Y luego ejecuta:

```terminal
git status
```

¡Woh! Sólo se han añadido tres de los cuatro archivos. El cuarto archivo -la clave `decrypt`- es ignorado por Git. Ya tenemos una línea dentro en `.gitignore` para eso. No queremos confirmar la clave de descifrado de `prod` en el repositorio... porque cualquiera que tenga esta clave podrá leer todos nuestros secretos.

Así, si otro desarrollador baja el proyecto ahora, tendrá la clave de descifrado de `dev`, por lo que no tendrá problemas para leer los valores de la bóveda de `dev`. No tendrá la clave de descifrado de `prod`... ¡pero no pasa nada! ¡El único lugar donde necesitas la clave de descifrado `prod` es en producción!

Así que con esta configuración, cuando despliegues, en lugar de tener que crear un archivo`.env.local` entero que contenga todos tus secretos, sólo tendrás que preocuparte de introducir este único archivo `prod.decrypt.private.php` en tu código. O, alternativamente, puedes leer esta clave y establecerla en una variable de entorno: puedes consultar la documentación para saber cómo hacerlo.

## Utilizar la Bóveda de Secretos

Pero... espera un segundo. ¡En realidad no he explicado cómo se utiliza la bóveda! Sabemos que el entorno `dev` utilizará la bóveda `dev`... y `prod` utilizará`prod`... pero ¿cómo leemos los secretos de la bóveda?

La respuesta es... ¡ya lo estamos haciendo! Los secretos se convierten en variables de entorno. ¡Es tan sencillo como eso! Así que en `config/packages/framework.yaml`, utilizando esta sintaxis `env`, este `GITHUB_TOKEN` podría ser una variable de entorno real, o podría ser un secreto en nuestra bóveda.

Para ver si esto funciona, dirígete a `MixRepository` y`dd($this->githubContentClient)`:

[[[ code('53828c9ece') ]]]

Muévete, refresca y... veamos si podemos encontrar la cabecera de Autorización en esto. En realidad, hay un truco muy bueno con el volcado. Haz clic en esta zona y mantén pulsado "comando" o "control" + "F" para buscar dentro de ella. Busca la palabra "token" y... ¡oh, eso no está bien! Ese es nuestro verdadero token. Pero... puesto que estamos en el entorno dev, ¿no debería estar leyendo nuestra bóveda dev en la que establecimos el valor falso de `CHANGEME`? ¿Qué está pasando?

## Los secretos deben convertirse completamente en variables de entorno

Como he mencionado, los secretos se convierten en variables de entorno. Pero las variables de entorno tienen prioridad sobre los secretos: incluso las variables de entorno definidas en los archivos `.env`. Sí, porque tenemos una variable de entorno `GITHUB_TOKEN` definida en `.env` y`.env.local`, ¡que tiene prioridad sobre el valor de la bóveda!

Esta es la cuestión. En cuanto elijas convertir un valor de una variable de entorno en un secreto, tienes que dejar de establecerlo como variable de entorno por completo. En otras palabras, borra `GITHUB_TOKEN` en `.env` y también en `.env.local`.

Ve a actualizar, haz clic de nuevo en esto, usa "comando" + "F", busca "token", y... ¡lo tienes! ¡Vemos "CHANGEME"! Si estuviéramos en el entorno `prod`, leería el valor de la bóveda prod... suponiendo que la clave de desencriptación prod estuviera disponible.

## El comando secrets:list

Vale, quita ese `dd()` y actualiza para descubrir que... ¡localmente, todo está roto! ¡Maldita sea! Pero... ¡por supuesto! Ahora está usando ese token falso de la bóveda dev. Funciona bien en producción... pero ¿cómo puedo arreglar mi configuración local para seguir trabajando?

Podríamos anular temporalmente el valor secreto de `GITHUB_TOKEN` en la bóveda de `dev` ejecutando el comando `secrets:set`. Pero... ¡eso es una tontería! Tendríamos que ser muy cuidadosos para no confirmar el archivo modificado y encriptado.

Antes de arreglar esto, quiero mostrarte un comando muy útil para la bóveda:

```terminal
php bin/console secrets:list
```

Sí, esto te muestra todos los secretos de nuestra bóveda. ¡Es genial! E incluso puedes pasar `--reveal` para revelar el valor... siempre que tengas la clave `decrypt`.

Te habrás dado cuenta de que nos da el valor justo aquí... pero luego dice "Valor local" con un espacio en blanco. Hmm...

Vuelve a ejecutar el comando, pero esta vez añade `--env=prod`.

```terminal-silent
php bin/console secrets:list --reveal --env=prod
```

Y... ¡lo mismo! Esto nos muestra el valor real de `prod`... pero sigue habiendo este punto de "Valor local" sin nada.

Este "Valor Local" es la clave para arreglar nuestra configuración rota de dev: es una forma de anular un secreto, pero sólo localmente en nuestra única máquina.

¿Cómo se establece este valor local de anulación? Copia el valor real de `GITHUB_TOKEN`, luego muévete, busca `.env.local` -el mismo archivo en el que hemos estado trabajando- y di`GITHUB_TOKEN=` y pega el valor que acabamos de copiar.

¡Sí! ¡Localmente, vamos a aprovechar que las variables de entorno "ganan" a los secretos! De vuelta a tu terminal, ejecuta

```terminal
php bin/console secrets:list --reveal
```

de nuevo. ¡Sí! El valor oficial en la bóveda es "CHANGEME"... pero el valor local es nuestro token real que, como sabemos, anulará el secreto y se utilizará. Si volvemos a probar la página... ¡funciona!

¡Bien, equipo! Estamos... bueno... ¡básicamente hechos! Así que, como recompensa por vuestro duro trabajo en estos temas tan importantes, vamos a celebrarlo utilizando la biblioteca generadora de código de Symfony MakerBundle.
