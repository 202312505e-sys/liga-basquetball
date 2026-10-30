# Liga de Básquetbol

Aplicación web para administrar una liga de básquetbol: equipos, jugadores, partidos, resultados con estadísticas, tabla de posiciones y máximos anotadores.

**Práctica Calificada – Universidad Tecnológica de los Andes (UTEA)**

**Autor:** Denis Alexander Meza Huarcaya

Sitio publicado: https://liga-basquetbol.infinityfreeapp.com

## Tecnologías

- PHP con arquitectura MVC propia, sin framework
- MySQL con PDO
- Apache (XAMPP) para trabajar en local
- Git y GitHub
- InfinityFree para la publicación

## Estructura del proyecto

```
public/         Punto de entrada (index.php), css e imágenes
app/
  core/         Database y funciones de ayuda
  controllers/  Controladores
  models/       Modelos (consultas con PDO)
  views/        Vistas
config/         database.example.php
database/       liga.sql (local) y liga_hosting.sql (hosting)
doc/            Documentación y diagramas
```

## Instalación local

1. Copiar el proyecto dentro de `htdocs` de XAMPP, en una carpeta llamada `liga-basquetbol`.
2. Importar `database/liga.sql` desde phpMyAdmin o MySQL Workbench.
3. Copiar `config/database.example.php` como `config/database.php` y completar los datos.
4. Encender Apache y MySQL y abrir `http://localhost/liga-basquetbol/public/`.

Para tener un administrador: registrar un usuario y ejecutar `UPDATE usuarios SET rol = 'admin' WHERE email = 'tu_correo';`

## Documentación

La documentación técnica está en `doc/practica_liga.md` y los diagramas en `doc/diagramas`.

## Estado

Versión final de la práctica.
