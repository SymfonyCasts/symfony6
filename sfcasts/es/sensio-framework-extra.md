# Adiós a SensioFrameworkExtraBundle

Nuestra aplicación se ha estropeado: algo sobre SensioFrameworkExtraBundle. Esto ocurrió mientras actualizábamos las recetas. En `framework.yaml`, es el `annotations: false`.

[[[ code('81f3246fac') ]]]

SensioFrameworkExtraBundle nos proporcionó todo tipo de funciones, como la anotación `@Route`, la anotación de seguridad y algo llamado convertidor de parámetros. Todas ellas dependían del sistema de anotaciones, que ha sido sustituido por atributos centrales de PHP. Cuando las cambiamos a false... al bundle no le gustó.

Pero bueno, ¡no pasa nada! Todas esas ingeniosas funciones encontraron un nuevo hogar en el núcleo de Symfony. Así que es hora de despedirnos con cariño de SensioFrameworkExtraBundle.

## Desinstalarlo

Ejecuta: en tu terminal:

```terminal
composer remove sensio/framework-extra-bundle
```

Hasta siempre, y gracias por todas las anotaciones. Cuando termina... y refrescamos, ¡el sitio vuelve a funcionar!

## Comprobación de las características de SensioFrameworkExtraBundle

Pero... ¿utilizábamos alguna de sus funciones? No lo sé Una forma fácil de comprobarlo es ejecutándolo:

```terminal
git grep FrameworkExtra
```

¡No! No parece que estemos haciendo referencia directa a ninguna sentencia `use`. Si es así, sólo es cuestión de averiguar qué nuevo atributo de Symfony sustituye a esa característica.

Para ayudarte, Symfony tiene una gran página de documentación llamada [Symfony Attributes Overview](https://symfony.com/doc/current/reference/attributes.html). Muestra todos los atributos PHP de Symfony. Por ejemplo, SensioFrameworkExtraBundle tenía una anotación `Security`. Ahora Symfony tiene un atributo `IsGranted`que puedes utilizar en su lugar.

Así que si estás utilizando algo del sistema antiguo, busca la nueva forma y actualízate.

## El nuevo "Convertidor de parámetros

Aunque... hay una función de SensioFrameworkExtraBundle que no requería una anotación... así que puede que la hayas estado utilizando sin darte cuenta. Haz clic en una de las mezclas. Fíjate en que la URL tiene un `slug`. El controlador para esto es`src/Controller/MixController.php`. Aquí abajo, la ruta tiene un comodín `{slug}`... pero luego un argumento `$mix`, que es una entidad Doctrine.

[[[ code('bed79c06c1') ]]]

Entre bastidores, el convertidor de parámetros buscaría automáticamente un`VinylMix` en el que `slug` fuera igual a `{slug}` en la URL. No hacía falta ninguna anotación: simplemente funcionaba.

La buena noticia es que, como puedes ver, ¡esta magia sigue funcionando! La función vive ahora en el núcleo. Y en la mayoría de los casos, seguirá haciendo lo suyo silenciosamente, como antes.

Si añades una letra más al final del slug para obtener un 404, vemos que el sistema que hay detrás de esto es `EntityValueResolver`. Si necesitas algún control extra, puedes configurarlo con el atributo `#[MapEntity]`.

Siguiente paso: ¡Quiero actualizar a Symfony 7! Pero para hacerlo, necesitamos eliminar todas estas deprecaciones.
