# Encore, StimulusBundle y sus cambios de recetas

Sigamos actualizando recetas.

## symfony/twig-bundle Actualización de recetas

El siguiente es TwigBundle. Este tiene un conflicto en el único archivo que actualizó: `templates/base.html.twig`.

Y... es extraño. Puedes ver nuestro contenido personalizado aquí.... y luego el título por defecto con el favicon por defecto abajo. Conserva nuestro contenido personalizado y borra este comentario. No lo necesitamos.

Ejecuta:

```terminal
git add templates
```

Entonces:

```terminal
git diff --cached
```

Esto muestra `symfony.lock` por supuesto, pero hubo un cambio en `base.html.twig`: eliminó `encore_entry_link_tags()` y `encore_entry_script_tags()`. ¿Por qué?

## La reordenación de las recetas

Una gran adición reciente al mundo frontend de Symfony fue StimulusBundle. Por sí solo, no es gran cosa. Pero, cuando se introdujo, se reordenaron varias recetas. Algunos cambios que antes vivían en la receta de un paquete se empaquetaron y se trasladaron a otro.

Por ejemplo, estas líneas formaban parte de la receta de TwigBundle, pero se trasladaron a la receta de WebpackEncoreBundle. Así que cuando actualicemos la receta de TwigBundle, parece que estas líneas deberían eliminarse.

Por supuesto, seguimos necesitándolas, pero acepta este cambio temporalmente. Veremos cómo se vuelven a añadir más adelante, cuando actualicemos la receta de WebpackEncoreBundle.

## symfony/webpack-encore-bundle Actualización de la receta

Vale, confirma esto y... ¡hagamos nuestra última actualización de la receta: WebpackEncoreBundle!

Y... más conflictos. No podemos tomarnos un respiro. Ejecuta:

```terminal
git status
```

Vale, en `package.json`, tenemos varios cambios. La receta está intentando actualizarnos de la versión 3 de Encore a la 4. La mayor diferencia entre la 3 y la 4 es que ahora es responsabilidad tuya tener unos cuantos paquetes en tu `package.json`, como el propio`webpack`... o los paquetes babel.

Quedémonos con la versión 4... y quedémonos con todo lo demás. Esto es una mezcla de paquetes personalizados que hemos añadido y los nuevos necesarios para Encore 4.

Ejecuta:

```terminal
git add package.json
```

A continuación, comprueba qué más ha cambiado con `git diff`. Algunas configuraciones sin sentido,`package.json` y `symfony.lock`. `webpack.config.js` contiene algunos cambios de bajo nivel: el uso de una versión más reciente de core.js y el `plugin-proposal-class-properties` ya no es necesario.

Así que, ¡aburrido, pero todo buen material! Confirma esa receta. Y como acabamos de actualizar`package.json`, en la otra pestaña, pulsa Control+C para detener `yarn`. A continuación, ejecuta

```terminal
yarn install
```

para obtener las últimas dependencias de nodos... y

```terminal
yarn watch
```

para reiniciar el proceso. Eh, ¡ahora estamos construyendo con Encore 4! ¡Ánimo equipo!

## Actualizar WebpackEncoreBundle a v2

El mayor cambio en el mundo Encore fue realmente la introducción de StimulusBundle. En relación con esto, en `composer.json`, `symfony/webpack-encore-bundle` tiene una nueva versión principal. Cámbiala a `^2.0`.

A continuación, gira y, en la pestaña principal de tu terminal, ejecuta:

```terminal
composer up
```

Por cierto, esto fallará al final con algo relacionado con SensioFrameworkExtraBundle. En el capítulo anterior... rompimos un poco nuestra aplicación al actualizar la receta del framework bundle. Lo arreglaremos en el próximo capítulo, pero ahora mismo no afecta a nada.

Entonces, ¿qué ha cambiado entre la versión 1 y la 2 de WebpackEncoreBundle? Sólo una cosa: las funciones de ayuda de Twig `stimulus_` -como `stimulus_controller()` - se eliminaron y se trasladaron al nuevo StimulusBundle. Nada del otro mundo.

Lo realmente complicado es lo que he mencionado antes: como resultado del nuevo bundle, se reorganizaron un montón de partes de la receta entre los paquetes. Además de que las funciones Twig de `encore_entry()` se trasladaron a la receta de WebpackEncoreBundle, algunos archivos -como `assets/controllers.json` - se trasladaron de la receta de WebpackEncoreBundle a la de StimulusBundle.

Todo esto está bien: la nueva situación es más limpia, con los archivos relacionados con Stimulus en la receta de ese bundle. Pero... es un poco lioso actualizar las recetas.

Así que vamos a repasarlo. Ejecuta:

```terminal
git status
```

Ejecuta: confirma estos cambios... y luego ejecuta

```terminal
composer recipes
```

de nuevo. ¡Sorpresa! ¡Hay dos nuevas actualizaciones! ¿De dónde han salido? Bueno, acabamos de actualizar StimulusBundle y WebpackEncoreBundle y esas nuevas versiones tienen nuevas versiones de recetas.

## symfony/stimulus-bundle Actualización de recetas

Actualización `symfony/stimulus-bundle`. Aquí... es donde empieza lo raro. Ejecuta: :

```terminal
git status
```

Tenemos un conflicto en `assets/controllers.json`. Este archivo ya existía y la receta intentó añadirlo. Esto se debe a que StimulusBundle es ahora el responsable de añadir este archivo... y está confundido porque ya está aquí. Arregla esto manteniendo nuestro archivo `controllers.json` exactamente como estaba.

Añade eso, y luego `git diff` para ver los demás cambios. Vale, ha añadido una línea de importación a `app.js`. Eso tampoco es algo que queramos porque... ¡ya lo tenemos aquí abajo! Es otro ejemplo de que la receta hace algo que ya está hecho. Elimina eso de arriba... y luego `git add` ese archivo.

Y... todo lo demás está bien. Nos ha dado un nuevo `hello_controller.js`, que puedes conservar o eliminar, y `symfony.lock`. Todo bien.

## symfony/webpack-encore-bundle V2 Actualización de la receta

Confirma esto... y pasemos a la última actualización de WebpackEncoreBundle. Esta es particularmente extraña. Ejecuta:

```terminal
git status
```

Dos conflictos. Muchos de los archivos que hay aquí solían estar en la receta de WebpackEncoreBundle, pero se movieron fuera de ella. Así que cuando actualicemos la receta, parece que habrá que eliminar un montón de cosas. En `assets/app.js`, este archivo no se ha borrado, pero está intentando eliminar sus tripas. Mantenlo como estaba antes. Después añádelo a `git`.

El siguiente es `package.json`. Es... más o menos lo mismo: está intentando borrar cosas. ¡No dejes que se salga con la suya! Mantén nuestro código... y añade también este archivo a`git`.

Bien, veamos cómo quedan las cosas. Quiere borrar `assets/bootstrap.js` -no queremos eso- y también quiere borrar `controllers.json`. Tampoco queremos eso. No queremos ninguno de estos cambios... ¡especialmente la letra "G" que aparentemente acabo de escribir en `package.json`! En realidad sólo hay un cambio que nos importa: en `base.html.twig`. ¡Tada! Vuelve a añadir`encore_entry_link_tags()` y `encore_entry_script_tags()`.

Es un buen cambio. Para el archivo final - `webpack.config.js` - quiere eliminar `enableStimulusBridge()`. Como estamos utilizando Stimulus, seguimos queriendo eso. Ejecuta

```terminal
git reset HEAD
```

para mover todo fuera del área de preparación de git, y luego

```terminal
git checkout assets webpack.config.js
```

para deshacer esos cambios. Perfecto. Nos quedan `symfony.lock` y `base.html.twig`. Confírmalos. 

¡Y ya está! Ya tenemos la última versión de WebpackEncoreBundle con la última versión de WebpackEncore y hemos pasado por esa extraña actualización de receta única.

Por desgracia, antes hemos estropeado nuestra aplicación. Así que lo siguiente es eliminar SensioFrameworkExtraBundle.
