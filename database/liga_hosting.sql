-- ==============================================================
-- Liga de Básquetbol: script para HOSTING en internet
-- En un hosting la base de datos ya viene creada por el panel.
-- Por eso este archivo NO crea ni borra la base: solo crea las
-- tablas y agrega datos de ejemplo.
-- ==============================================================

-- Crear las tablas

-- TABLA 1: usuarios (quienes entran al sistema) 
CREATE TABLE usuarios (
    id            INT AUTO_INCREMENT PRIMARY KEY,  -- PK AUTO INCREMENTAL
    nombre        VARCHAR(100) NOT NULL,
    email         VARCHAR(120) NOT NULL UNIQUE,    -- dos usuarios no pueden tener el mismo email
    password_hash VARCHAR(255) NOT NULL,           -- contraseña ENCRIPTADA (nunca en texto plano)
    rol           VARCHAR(20)  NOT NULL DEFAULT 'visitante'   -- 'admin' o 'visitante'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 2: equipos 
CREATE TABLE equipos (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100) NOT NULL UNIQUE,      -- no puede haber dos equipos con el mismo nombre
    ciudad      VARCHAR(80)  NOT NULL,
    entrenador  VARCHAR(100),                      -- sin NOT NULL = puede quedar vacío
    fundado_en  INT                                -- año, por ejemplo 2010
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 3: jugadores 
-- Cada jugador PERTENECE A UN equipo. Eso se guarda con una "clave foránea" (FK).
CREATE TABLE jugadores (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    equipo_id        INT NOT NULL,                 -- aquí se guarda el id del equipo del jugador
    nombre           VARCHAR(80) NOT NULL,
    apellido         VARCHAR(80) NOT NULL,
    dorsal           INT NOT NULL,                 -- número de camiseta
    posicion         VARCHAR(20),                  -- base, escolta, alero, ala-pivot, pivot
    estatura_cm      INT,
    fecha_nacimiento DATE,

    -- FOREIGN KEY (clave foránea): "equipo_id debe ser un id que EXISTA en la tabla equipos".
    -- Así MySQL impide asignar un jugador a un equipo que no existe.
    -- ON DELETE CASCADE: si borras un equipo, se borran también sus jugadores.
    FOREIGN KEY (equipo_id) REFERENCES equipos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 4: partidos 
-- Un partido lo juegan DOS equipos, por eso tiene DOS claves foráneas hacia equipos:
-- una para el local y otra para el visitante.
CREATE TABLE partidos (
    id                INT AUTO_INCREMENT PRIMARY KEY,
    local_id          INT NOT NULL,                -- id del equipo local
    visitante_id      INT NOT NULL,                -- id del equipo visitante
    fecha             DATETIME NOT NULL,
    lugar             VARCHAR(120),
    puntos_local      INT,                         -- vacío (NULL) hasta que se juegue
    puntos_visitante  INT,
    estado            VARCHAR(20) NOT NULL DEFAULT 'programado',   -- 'programado' o 'jugado'

    -- Sin "ON DELETE", MySQL NO deja borrar un equipo que ya tiene partidos
    -- (protege el historial).
    FOREIGN KEY (local_id)     REFERENCES equipos(id),
    FOREIGN KEY (visitante_id) REFERENCES equipos(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TABLA 5: estadisticas_jugador 
-- Une jugadores con partidos: cuántos puntos, rebotes y asistencias hizo
-- UN jugador en UN partido. Por eso lleva dos claves foráneas.
CREATE TABLE estadisticas_jugador (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    partido_id  INT NOT NULL,
    jugador_id  INT NOT NULL,
    puntos      INT NOT NULL DEFAULT 0,
    rebotes     INT NOT NULL DEFAULT 0,
    asistencias INT NOT NULL DEFAULT 0,

    UNIQUE (partido_id, jugador_id),
    FOREIGN KEY (partido_id) REFERENCES partidos(id)  ON DELETE CASCADE,
    FOREIGN KEY (jugador_id) REFERENCES jugadores(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- INSERT INTO 
INSERT INTO equipos (nombre, ciudad, entrenador, fundado_en) VALUES
 ('Halcones del Cusco', 'Cusco',    'Marco Quispe', 2010),   -- será el equipo id = 1
 ('Pumas del Valle',    'Urubamba', 'Luis Condori', 2012),   -- id = 2
 ('Cóndores Andinos',   'Sicuani',  'Rosa Huamán',  2015),   -- id = 3
 ('Titanes de Wanchaq', 'Cusco',    'Jorge Mamani', 2018);   -- id = 4


INSERT INTO jugadores (equipo_id, nombre, apellido, dorsal, posicion, estatura_cm, fecha_nacimiento) VALUES
 (1, 'Carlos', 'Ramos',   4,  'base',      178, '2000-03-14'),
 (1, 'Diego',  'Paucar',  10, 'alero',     190, '1999-08-02'),
 (2, 'Juan',   'Flores',  7,  'escolta',   184, '2001-05-21'),
 (2, 'Andrés', 'Choque',  12, 'pivot',     201, '1998-11-30'),
 (3, 'Miguel', 'Torres',  5,  'ala-pivot', 196, '2000-01-09'),
 (3, 'Luis',   'Vargas',  8,  'base',      176, '2002-07-17'),
 (4, 'Pedro',  'Salas',   9,  'alero',     188, '1999-12-25'),
 (4, 'Raúl',   'Mendoza', 15, 'pivot',     203, '1997-04-04');

-- Partido 1: Halcones (1) local vs Pumas (2) visitante, ganó el local 82 a 75
INSERT INTO partidos (local_id, visitante_id, fecha, lugar, puntos_local, puntos_visitante, estado) VALUES
 (1, 2, '2026-10-05 19:00:00', 'Coliseo Cerrado',    82,   75,   'jugado'),
 (3, 4, '2026-10-06 18:00:00', 'Coliseo de Sicuani', 68,   71,   'jugado'),
 (1, 3, '2026-10-12 19:00:00', 'Coliseo Cerrado',    NULL, NULL, 'programado');

-- En el partido 1, el jugador 1 (Carlos Ramos) hizo 20 puntos, 5 rebotes y 8 asistencias
INSERT INTO estadisticas_jugador (partido_id, jugador_id, puntos, rebotes, asistencias) VALUES
 (1, 1, 20, 5, 8),
 (1, 3, 18, 7, 2);


-- ==============================================================
-- PASO 4. Consultas para comprobar y entender (ejecútalas una por una)
-- Selecciona UNA línea y pulsa el rayo con el cursor (⚡ "Execute current statement").
-- ==============================================================

-- Ver todos los equipos:
--   SELECT * FROM equipos;

-- Ver cada jugador con el NOMBRE de su equipo (aquí se ve para qué sirve la clave foránea):
--   SELECT j.nombre, j.apellido, e.nombre AS equipo
--   FROM jugadores j
--   JOIN equipos e ON j.equipo_id = e.id;

-- Ver los partidos con los nombres de los dos equipos:
--   SELECT l.nombre AS local, v.nombre AS visitante, p.puntos_local, p.puntos_visitante
--   FROM partidos p
--   JOIN equipos l ON p.local_id = l.id
--   JOIN equipos v ON p.visitante_id = v.id;


-- ==============================================================
-- Cómo crear un administrador
-- 1. Registra un usuario desde la pantalla "Registrarse".
-- 2. Ejecuta esto con el correo que usaste:
--    UPDATE usuarios SET rol = 'admin' WHERE email = 'tu_correo@ejemplo.com';
-- ==============================================================