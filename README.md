# Proyecto Intermodular - Segundo de DAW - PokeHunt
Proyecto intermodular del segundo año del Grado Superior de Desarrollo de Aplicaciones Web

## Equipo del Proyecto
- Adrián Bollullos
- Agustin Conti
- Julio Perez

## PokeHunt
PokeHunt es un sitio web donde no sólo se puede ver información de los distintos Pokémon, sino también jugar a una variedad de juegos para capturar Pokémon y ampliar tu colección.

## Tecnologías utilizadas
Para la creación de este sitio web utilizamos las siguientes tecnologías:
- __PHP:__ Utilización de un Modelo-Vista-Controlador (MVC) para la organización del código, separando la parte de base de datos, los controladores y la vista. Esto permite una mayor modularidad y simplicidad a la hora de agregar nuevos sistemas a la página o de resolver problemas.
  - __PDO:__ Para la conexión con la base de datos se optó por la utilización de la clase PDO en los modelos PHP
- __Javascript:__ Se utilizó JavaScript para la implementación de ciertas funcionalidades del cliente, como la implementación de filtros dinámicos. Para lograr este funcionamiento se utilizaron las peticiones asíncronas para solicitar información a la parte servidora.
- __Bootstrap y CSS:__ Se utilizó una combinación de Bootstrap y CSS para el diseño de las páginas web, así como su adaptabilidad frente a differentes tamaños de pantallas.
- __MySQL:__ Utilizamos MySQL y phpMyAdmin para crear y gestionar la estructura de la base de datos.
- __PokeAPI:__ Para obtener toda la información de los Pokémon para su utilización tanto en juegos como en la Wiki utilizamos el API de PokeAPI. Lo conectamos al resto del proyecto a través de un modelo que se encarga de obtener y guardar toda la información necesaria para que los controladores puedan usarla.
## Base de datos
![Diagrama de la base de datos](/app/images-readme/diagrama-base-de-datos.png)
Para la base de datos, optamos por crear las siguientes tablas:
- __Usuario:__ Guarda toda la información relevante al usuario, incluído el estado del usuario (activo o inactivo) y la contraseña encriptada
- __token_validación:__ Guarda tokens temporales utilizados para acceder a ciertas páginas desde un enlace que el usuario recibe en su correo. Esta función es utilizada tanto para activar la cuenta tras registrarse, como para cambiar la contraseña en caso de pérdida.
- __j_adivinanza y j_clasificar:__ Contienen toda la información relevante a los juegos de Adivinanza y Clasificar
- __j_trivia_enunciado, j_trivia_opcion y j_trivia_respuesta:__ Guardan toda la información de los juegos de Trivia. La tabla de enunciados guarda la pregunta y el Pokémon recompensa; la tabla de opciones guarda todas las opciones utilizadas en todas las trivias (Si alguna se repite no habrá que guardarla como un dato nuevo, sino que la reutilizará); y la tabla respuesta es la que se encarga de conectar cada enunciado con sus opciones correspondientes, así como indicar cuáles de dichas opciones son correctas.
- __j_tipo_adivinanza y j_tipo_clasificar:__ Son tablas de contenido fijo que contienen los diferentes tipos de estos juegos, utilizadas para ahorra espacio y prevenir errores a medida que la cantidad de juegos crece.
- __pokemon_usuario:__ Esta tabla relaciona cada usuario con los Pokémon que hayan obtenido. Además indica si dicho Pokémon es un Pokémon favorito del usuario.

### Tipos de usuarios
La página cuenta con 3 tipos de usuarios distintos: Invitado, registrado y administrador.

- __Invitado:__ El usuario común a todos los visitantes iniciales de la página. Son los que tienen los permisos más restringidos. Pueden acceder a la página de inicio, a toda la información en la Wiki, a la página de juegos (pero no podrán jugar) y a los rankings. Además tienen la opción de registrarse o iniciar sesión.
- __Registrado:__ Aquellos usuarios que se han registrado en la página y han iniciado sesión. Podrán acceder a las páginas de inicio, Wiki, juegos y rankings. Además podrán jugar cualquiera de los juegos disponibles, obtener Pokémon, ver su perfil o editarlo (pudiendo cambiar su nombre o imagen de perfil).
- __Administrador:__ El usuario administrador no se puede crear registrándose, sino que ya viene incluido en la base de datos. Puede hacer lo mismo que un usuario registrado, además de: gestionar los juegos existentes (Creando, editando o borrándolos según necesite) y ver la lista de todos los usuarios existentes (podrá activar o desactivar cuentas manualmente según lo requiera). El usuario administrador no aparecerá en el ranking de todos los usuarios.

## Página Web
### Inicio
![Página de inicio](/app/images-readme/inicio.png)

Al entrar a nuestra página web, el usuario verá la página de inicio con 2 menús: uno general y uno de usuario. En el menú general tendrá la opción de ir al Home, a la Wiki, a los juegos o al ranking. El menú de usuario será distinto para cada usuario. Los usuarios invitados tendrán la opción de iniciar sesión o registrarse, los usuarios registrados podrán acceder a su perfil y a sus opciones, y los usuarios administradores podrán además acceder a la gestión de los juegos y a la administración de los usuarios.

### Registro
El usuario podrá ingresar su nombre de usuario, correo y contraseña para registrarse en la página web. Al hacerlo, su usuario se agregará a la base de datos y recibirá un correo en su email para activar la cuenta. Hasta que no active su cuenta no podrá iniciar sesión.

### Iniciar sesión
El usuario podrá ingresar su nombre de usuario y contraseña y, si los datos son correctos y su cuenta está activa, se iniciará su sesión y se le redirigirá a la página de inicio.

### Wiki
Esta página se podrá acceder desde la página de inicio y mostrará tres campos al usuario. El campo principal es el de selección de Pokémon, donde el usuario podrá escribir el nombre o número de cualquier Pokémon y al seleccionarlo podrá ver toda su información. Los otros dos campos son listas que el usuario puede utilizar para filtrar la lista de Pokémon por tipo o por generación.

### Información Pokémon
En esta página, el usuario podrá ver toda la información relevante de el Pokémon seleccionado, incluida toda la información necesaria para jugar los distintos juegos.

### Ranking
La página de rankings muestra una lista de los primeros 100 usuarios que hayan conseguido algún Pokémon, ordenados por la cantidad de Pokémon obtenidos. De cada usuario se mostrará su nombre e imagen, la cantidad de Pokémon obtenidos y sus Pokémon favoritos, si los hubiera.

### Juegos
En esta página se podrá ver una descripción de los tres tipos de juegos disponibles en la página web. Además, si el usuario es de tipo registrado o administrador podrá jugar cualquiera de los juegos, pero si es un usuario invitado se mostrará un mensaje para que inicie sesión para poder jugar.

### Adivinanza
Cuando el usuario elige jugar un juego de Adivinanza, verá una de dos posibles cosas. Si el usuario ya ha completado todos los juegos de adivinanza, verá un mensaje indicándole que ya no quedan juegos para jugar. En cambio, si existen juegos, éste iniciará automáticamente. El usuario vera la silueta, la descripción o podrá escuchar el grito de un Pokémon. Al mismo tiempo, verá las 3 pistas relacionada a dicho Pokémon. El usuario podrá entonces intentar adivinar el Pokémon escribiendo su nombre completo correctamente. Si el nombre es correcto, recibirá el Pokémon que adivinó. En caso contrario, se le indicará que ha fallado y podrá volver a la página de juegos.

### Clasificar
Similarmente, cuando el usuario elija jugar un juego de Clasificar, verá un mensaje si ya ha completado todos los juegos de clasificar, indicándole que ya no quedan juegos. En cambio, si existen juegos, se cargará y comenzará automáticamente. La página le mostrará una selección de múltiples Pokémon, cada uno con múltiples opciones. Estas opciones serán los diferentes tipos de Pokémon o las diferentes Generaciones. El usuario deberá seleccionar suficientes respuestas correctas para ganar el juego y obtener el Pokémon asignado a ese juego. Si el usuario no selecciona una respuesta para todos los Pokémon, se le mostrará un mensaje indicándole que quedan Pokémon por clasificar. Al igual que adivinanza, en caso de no ganar se le mostrará un mensaje indicándolo y la opción para volver a la página de juegos.

### Trivia
Al igual que los otros dos juegos, cuando el usuario elija jugar a Trivia, verá un mensaje si ya ha ganado todos los juegos de trivia que indicará que ya no quedan juegos. En caso contrario, se cargarán los datos necesarios y comenzará el juego. La página mostrará el enunciado de la trivia y le dará al usuario dos o más opciones entre las cuales elegir. Si elije todas las opciones correctas ganará el juego y su Pokémon asociado, sino se le mostrará la notificación de derrota al igual que en los otros dos juegos.

### Mi Perfil
Esta página se puede acceder desde el menú de usuario si ha iniciado sesión. Mostrará el nombre y la imagen de perfil del usuario iniciado, así como una lista de sus Pokémon favoritos y sus Pokémon totales. Además podrá seleccionar los Pokémon que quiera agregar como favoritos utilizando el checkbox en cada Pokémon.

### Opciones
Al igual que "Mi Perfil", esta página se accede desde el menú de usuario de un usuario registrado. Desde aquí, el usuario puede ir a una página para cambiar su nombre o su imagen. No podrá cambiar su nombre si ya existe un usuario con ese nombre. Podrá cambiar la imagen a una de las predefinidas por la página mediante una lista desplegable.

### DevTools
DevTools se puede acceder desde el menú de usuario de un usuario Administrador. Permite ver una lista de todos los usuarios, así como activar o desactivar cualquier usuario que no sea administrador.

### Gestionar Juegos
Otra página que puede acceder un usuario administrador desde su menú de usuario. Mostrará una lista de cada juego dividida por tipos (Adivinanza, Clasificar y Trivia), con la opción para crear un juego nuevo, y editar o eliminar uno existente. Una vez elegido un juego para crear o editar, el usuario podrá elegir el Pokémon que recibirá el jugador al ganar, así como las opciones necesarias para que el juego funcione. En caso de faltar algún campo necesario o haber alguno erróneo, se le informará al administrador. Además, se le mostrará un error si intenta seleccionar un Pokémon que ya está siendo utilizado para otro juego.

## Descarga
Para descargar y ver la página web desde tu propio ordenador puedes seguir los siguientes pasos:
- Descarga la aplicación de Docker Desktop
- Descarga la carpeta de docker del repositorio (No hace falta descargar nada más)
- Ábrela con Visual Studio Code
- Instala la extensión Container Tools
- Abre una nueva terminal
- Ejecuta el comando `docker compose up`
- Ahora puedes conectarte a la página web a través de localhost, tanto por HTTP como por HTTPS.