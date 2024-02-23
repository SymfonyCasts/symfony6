# Nuevos atributos de autocableado

¿Qué hay de nuevo en Symfony 7? ¡Nada! La verdadera pregunta es, ¿qué hay de nuevo en Symfony 6.4? O quizás, ¿qué hay de nuevo en 6.3 o 6.2 que... quizás nos hayamos perdido?

## Visita rápida a las nuevas funciones

El mejor lugar para encontrar estas cosas... es el blog de Symfony. Javier hace un trabajo fantástico con cada versión, desvelando las características más importantes.

He sacado algunas de mis favoritas, como el perfilador del flujo de trabajo. Si utilizas el componente de flujo de trabajo, ahora puedes ver una visualización muy chula de tu flujo de trabajo dentro del perfilador.

También hay algunos cambios en el sistema de cierre de sesión, sólo para simplificar la vida... algunas restricciones nuevas, como `PasswordStrengthConstraint` y otra que impide caracteres sospechosos, como caracteres de espacio de anchura cero. Esto puede utilizarse para evitar que alguien cree un nombre de usuario que se parezca al de otra persona.

Si estás creando una API, hay un excelente comando `debug:serializer` para ver todos los metadatos de una clase.

Y, por último, los nuevos componentes `Webhook` y `RemoteEvent`, que merecen su propio tutorial. Así que lo dejaremos para otra ocasión.

Éstas son sólo algunas de mis funciones favoritas, pero puedes verlo todo yendo a la sección "Vivir al límite" del blog y filtrando por la versión. Una forma estupenda de empollar.

## El atributo Autowire

Pero quiero que recorramos juntos algunas nuevas funciones, empezando por las mejoras en el sistema de autocableado. Éstas se han producido en las últimas versiones de Symfony y... hacen muchas cosas. El efecto general es que probablemente no tendrás que volver a entrar en `services.yaml`. 

¡Vamos a sumergirnos! En un antiguo tutorial, añadí este `bind` para un argumento `$isDebug`.

[[[ code('3f0ef111dc') ]]]

La razón por la que hice eso vive en `src/Controller/VinylController.php`: le di a este controlador un argumento `$isDebug`... que no es autowirable.

[[[ code('0d19b13543') ]]]

En `services.yaml`, elimina el `bind`.

Al actualizar, ¡error! Dice:

> Oye, tonto: tienes un argumento `$isDebug` en un servicio, pero no tengo ni
> idea de qué pasarle.

De ahí que tuviéramos el `bind`. Desde hace unas cuantas versiones de Symfony, ahora tenemos un atributo`Autowire`. Si tienes un argumento que no se puede autoconectar, éste es tu amigo. Añádelo antes del arg y define lo que quieras. Puede ser un servicio, una expresión, una variable de entorno, un parámetro, un gatito, lo que sea. Queremos un parámetro: `kernel.debug`.

[[[ code('ee9f473968') ]]]

Dentro, `dump($this->isDebug)` para asegurarte de que funciona.

Y... ¡lo está! Autowire es mi nuevo atributo favorito. Pero si mantienes pulsado comando o control para abrir esta clase... y luego haces doble clic en el directorio `Attribute`, veremos toda una lista de geniales atributos relacionados con la inyección de dependencias. `Exclude` es una forma de excluir una clase de ser autoregistrada como servicio. `Autoconfigure`
y `AutoconfigureTag` son formas de configurar opciones en tu servicio. 
Pon esto encima de tu clase -o incluso encima de una interfaz- y las opciones se aplicarán al servicio o servicios que implementen esa interfaz.

También están `AutowireIterator` y `AutowireLocator`. Si tienes un conjunto de servicios que implementan una etiqueta, puedes utilizar `AutowireIterator` para que te pasen esos servicios como un iterador, o `AutowireLocator` para que te los pasen como un localizador, básicamente una matriz asociativa de servicios.

## Probando AutowireIterator

Por ejemplo, imagina que, en `VinylController`, queremos obtener un iterable de cada comando de consola de nuestra app. Digamos `private iterable $commands`. Y para probar que esto funciona, foreach sobre `$this->commands` como `$command`... y luego vuelca el objeto.

[[[ code('8b76f3cf12') ]]]

Si nos detuviéramos ahora, obtendríamos el clásico error que dice

> ¡No tengo ni idea de qué pasar para este argumento `$commands`!

Queremos un iterable de todos los servicios que implementan una etiqueta específica. Cógelos con `#[AutowireIterator]`, luego la etiqueta que queremos: `console.command`.

[[[ code('518d5413e6') ]]]

¡Y ya los tenemos! Vemos los 102 comandos de consola de mi aplicación. Lo sé, es un ejemplo tonto, pero ¿no es genial?

De vuelta al controlador, deshazlo.

[[[ code('e52ae3b5ad') ]]]

A continuación: vamos a hablar de algunas sutiles, pero potentes, nuevas formas de obtener datos de peticiones, como los parámetros de consulta y la carga útil de la petición.
