# Bundle Config (para controlar los servicios de bundle)

Ahora utilizamos los servicios `HttpClientInterface` y `CacheInterface`. ¡Sí! Pero en realidad no somos responsables de instanciar estos objetos de servicio. No, los crea otra cosa (hablaremos de ello en unos minutos), y luego nos los pasa.

Eso está muy bien, porque todos estos servicios -las "herramientas" de nuestra aplicación- vienen listos para usar, listos para usar. Pero... si otra cosa se encarga de instanciar estos objetos de servicio, ¿cómo podemos controlarlos?

Presentamos: ¡la configuración del bundle!

## Configuración del bundle

Ve a ver el directorio `config/packages/`. Este tiene un número de diferentes archivos YAML, todos los cuales son cargados automáticamente por Symfony cuando se inicia por primera vez. Todos estos archivos tienen exactamente un propósito: configurar los servicios que nos proporciona cada bundle.

Abre `twig.yaml`. Por ahora, ignora este `when@test`: hablaremos de ello en unos minutos. Este archivo tiene una clave raíz llamada `twig`. Y, por tanto, todo el propósito de este archivo es controlar los servicios que proporciona el bundle "Twig". Y, no es el nombre del archivo - `twig.yaml` - lo que es importante. Podría renombrar esto como`pineapple_pizza.yaml` y funcionaría exactamente igual y sería delicioso, no me importa lo que pienses.

Cuando Symfony carga este archivo, ve esta clave raíz - `twig` - y dice:

> Oh, vale. Voy a pasar la configuración que haya debajo a TwigBundle.

¡Y recuerda! Los bundles nos dan servicios. Gracias a esta configuración, cuando TwigBundle está preparando sus servicios, Symfony le pasa esta configuración y TwigBundle la utiliza para decidir cómo deben instanciarse sus servicios... como qué nombres de clase usar para cada servicio... o qué primer segundo o tercer argumento del constructor pasar.

Por ejemplo, si cambiáramos el `default_path` por algo como`%kernel.project_dir%/views`, el resultado es que el servicio Twig que genera plantillas estaría ahora preconfigurado para buscar en ese directorio.

La cuestión es: la configuración de estos archivos nos da el poder de controlar los servicios que proporciona cada bundle.

Veamos otro: `framework.yaml`. Como la clave raíz es`framework`, toda esta configuración se pasa a FrameworkBundle... que la utiliza para configurar los servicios que proporciona.

Y, como he mencionado, el nombre del archivo no importa... aunque el nombre suele coincidir con la clave raíz... sólo por razones de cordura: como `framework` y `framework.yaml`. Pero no siempre es así. Abre `cache.yaml`. ¡Woh! Esto es... ¡más configuración para FrameworkBundle! Vive en su propio archivo... sólo porque es bueno tener un archivo separado para controlar la caché.

## Depuración de la configuración del bundle disponible

Llegados a este punto, puede que te preguntes:

> Vale, genial... pero ¿qué claves de configuración podemos poner aquí? ¿Dónde puedo
> encontrar qué opciones están disponibles?

¡Gran pregunta! Porque... no puedes "inventar" las claves que quieras: eso daría un error. En primer lugar, sí, puedes, por supuesto, leer la documentación. Pero hay otra manera: y es una de mis cosas favoritas del sistema de configuración de Symfony.

Si quieres saber qué configuración puedes pasar al bundle "Twig", hay dos comandos de`bin/console` que te ayudarán. El primero es:

```terminal
php bin/console debug:config twig
```

Esto imprimirá toda la configuración actual bajo la clave `twig`, incluyendo cualquier valor por defecto que el bundle esté añadiendo. Puedes ver que nuestro`default_path` está configurado en el directorio `templates/`, que proviene de nuestro archivo de configuración. Este `%kernel.project_dir%` es sólo una forma elegante de apuntar a la raíz de nuestro proyecto. Más adelante hablaremos de ello.

Prueba esto: cambia el valor a `views`, vuelve a ejecutar ese comando y... ¡sí! Vemos "views" en la salida. Déjame que vuelva a cambiarlo.

Así que `debug:config` nos muestra toda la configuración actual de un bundle específico, como `twig`... lo que es especialmente útil porque también te muestra los valores predeterminados añadidos por el bundle. Es una buena manera de ver lo que puedes configurar. Por ejemplo, ¡aparentemente podemos añadir una variable global a Twig mediante esta clave `globals`!

El segundo comando es similar: en lugar de `debug:config`, es `config:dump`:

```terminal
php bin/console config:dump twig
```

`debug:config` te muestra la configuración actual... pero `config:dump`te muestra un árbol gigante de configuración de ejemplo, que incluye todo lo que es posible. Aquí puedes ver `globals` con algunos ejemplos de cómo podrías utilizar esa tecla. Esta es una gran manera de ver todas las opciones potenciales que puedes pasar a un bundle... para ayudarle a configurar sus servicios.

Utilicemos este nuevo conocimiento para ver si podemos "enseñar" al servicio de caché a almacenar sus archivos en otro lugar. Eso a continuación.
