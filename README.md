# Liga de Básquetbol

Aplicación web para gestionar una liga de básquetbol: usuarios, equipos, jugadores, partidos, resultados y estadísticas básicas.

**Práctica Calificada – Universidad Tecnológica de los Andes (UTEA)**

## Tecnologías

- PHP (arquitectura MVC propia)
- MySQL + MySQL Workbench
- Apache (XAMPP)
- Visual Studio Code
- Git y GitHub

## Estructura del proyecto

```
public/       Punto de entrada (index.php)
app/
  controllers/  Controladores
  models/       Modelos (acceso a datos con PDO)
  views/        Vistas
config/       Configuración (database.example.php)
database/     Script SQL (liga.sql)
doc/          Documentación técnica y diagramas
```

## Instalación local

1. Clonar el repositorio dentro de `htdocs` de XAMPP.
2. Ejecutar `database/liga.sql` en MySQL Workbench.
3. Copiar `config/database.example.php` como `config/database.php` y completar los datos.
4. Iniciar Apache en XAMPP y abrir `http://localhost/liga-basquetbol/public/`.

## Estado

En desarrollo.
