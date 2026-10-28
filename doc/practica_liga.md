# Sistema de Gestión de Liga de Básquetbol

**Universidad Tecnológica de los Andes (UTEA) · Ingeniería de Sistemas e Informática**
**Autor:** Denis Alexander Meza Huarcaya

## 1. Descripción general

Armé una aplicación web para administrar una liga de básquetbol. Permite registrar equipos, jugadores y partidos, guardar el resultado con las estadísticas de cada jugador y ver la tabla de posiciones y los máximos anotadores. Hay dos roles: el visitante solo consulta y el administrador además crea, edita y elimina.

Usé PHP sin framework, MySQL con PDO, Git y GitHub. El sistema está publicado en InfinityFree.

## 2. Arquitectura

Organicé el código con el patrón MVC. Lo entiendo como un restaurante: el controlador es el mesero que recibe el pedido, el modelo es la cocina que prepara los datos y la vista es el plato que llega a la mesa.

Todas las peticiones entran por `public/index.php`, que lee la URL (por ejemplo `?c=equipo&a=crear`), comprueba que el nombre sea válido y llama al método correspondiente.

## 3. Clases implementadas

- **Controladores:** AuthController, EquipoController, JugadorController, PartidoController y EstadisticaController.
- **Modelos:** Usuario, Equipo, Jugador, Partido y Estadistica. Cada uno tiene las consultas SQL de su tabla.
- **Núcleo:** Database abre la conexión a la base de datos y helpers.php reúne funciones de ayuda, como mostrar vistas y revisar la sesión.

## 4. Base de datos

Son cinco tablas: usuarios, equipos, jugadores, partidos y estadisticas_jugador. Si se borra un equipo, se borran sus jugadores. Un partido guarda dos equipos (local y visitante) sin borrado en cascada, así que no se puede eliminar un equipo que ya tiene partidos. La tabla de estadísticas une a un jugador con un partido.

## 5. Funcionalidades y reglas

- **Usuarios:** el registro siempre crea visitantes. El administrador se asigna en la base de datos.
- **Equipos:** nombre único y año de fundación entre 1900 y el año actual.
- **Jugadores:** dorsal de 0 a 99, sin repetir dentro del equipo.
- **Partidos:** un equipo no juega contra sí mismo, no hay empates y solo se registra el resultado de partidos programados.
- **Posiciones:** 2 puntos por victoria y 1 por derrota.

## 6. Fragmentos relevantes

**Registrar resultado.** El método `registrarResultado` de PartidoController revisa que los puntos de los jugadores de cada equipo no superen el marcador. Luego guarda marcador y estadísticas en una transacción. Funciona como una transferencia bancaria: o se hace completa o no se hace.

**Tabla de posiciones.** El método `tablaPosiciones` de Estadistica usa una sola consulta. Cuenta cada partido dos veces, una para el local y otra para el visitante, y ordena por puntos y diferencia de puntos anotados.

**Permisos.** La función `requerirAdmin()` se llama al inicio de cada acción de administrador. La protección está en el servidor y no solo en ocultar botones.

## 7. Problemas y soluciones

- **La transacción no podía funcionar.** Cada modelo abría su propia conexión. Cambié Database para que todos compartan una sola.
- **Error de sintaxis en el modelo Jugador.** Quedó un carácter de más en una línea y lo corregí en un commit aparte.
- **El hosting no deja crear la base de datos.** Escribí `liga_hosting.sql`, que solo crea las tablas, y le agregué el motor InnoDB para que las claves foráneas funcionen.
- **Borrar un equipo con partidos.** La base de datos lo impide y el controlador captura ese error para mostrar un mensaje claro.

## 8. Decisiones técnicas

- No usé Laravel y armé el MVC a mano para entender cómo se conectan sus piezas.
- Usé URLs con parámetros y no URLs amigables, para no depender de `mod_rewrite`.
- El dorsal único se valida en el código y no en la base de datos.

## 9. Seguridad

- Contraseñas con `password_hash` y consultas preparadas contra la inyección SQL.
- Todo texto mostrado pasa por `htmlspecialchars`, contra ataques XSS.
- Eliminar solo funciona por POST y con confirmación.
- `config/database.php` no se sube a GitHub; solo está `database.example.php`.
- En el hosting, `.htaccess` manda todo a `public`, así que `app`, `config` y `database` no se abren desde el navegador.

## 10. Instalación y uso

1. Copiar el proyecto a `htdocs` de XAMPP.
2. Importar `database/liga.sql` (en el hosting, `liga_hosting.sql`).
3. Copiar `config/database.example.php` como `config/database.php` y poner los datos.
4. Abrir `http://localhost/liga-basquetbol/public/`.

Para tener un administrador, registro un usuario y cambio su rol a `admin` en la base de datos.

## 11. Limitaciones

No tiene protección CSRF, no permite editar ni cancelar partidos programados y no incluye pruebas automáticas.

## 12. Control de versiones

Trabajé con una rama por módulo y fusioné cada una a `main` con un pull request. Los commits son pequeños, así que el historial muestra el avance paso a paso.