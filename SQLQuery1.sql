use Portafolio;

CREATE TABLE proyecto (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    imagen VARCHAR(255) NOT NULL,
    descripcion TEXT
);
select * from proyecto;



INSERT INTO Proyectos (nombre, imagen, descripcion)
VALUES ('Proyecto Google', 'imagen.jpg', 'En este proyecto me enfoqué en construir la clonación de la interfaz de GOOGLE.');

