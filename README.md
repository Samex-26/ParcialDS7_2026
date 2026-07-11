# 🚀 Plataforma de Gestión de Colaboradores y Perfiles Laborales
## Desarrollo de Software VII | Proyecto Parcial 2
### Desarrollador: Samuel Ojo


## 📌 Índice General

- [Contexto del Proyecto](#-contexto-del-proyecto)
- [Funcionalidades Principales](#-funcionalidades-principales)
- [Stack Tecnológico](#️-stack-tecnológico)
- [Distribución del Código Fuente](#️-distribución-del-código-fuente)
- [Modelo de Datos](#-modelo-de-datos)
- [Pre-requisitos](#-pre-requisitos)
- [Instalación del Entorno](#-instalación-del-entorno)
- [Rutas Disponibles](#-rutas-disponibles)
- [Validaciones Clave](#-validaciones-clave)
- [Notas de Seguridad](#-notas-de-seguridad)
- [Créditos](#-créditos)

---

## 📝 Contexto del Proyecto

La aplicación web gestiona colaboradores y sus perfiles laborales. El sistema administra datos personales, información de ruta, tipo de sangre, tipo de empleado, planilla, ocupación, salario y estado activo del perfil.

### Meta Académica
Construir un sistema con arquitectura MVC que permita validar y sanitizar entradas, mantener consistencia referencial en la base de datos y ofrecer reportes completos con exportación a Excel.

---

## ✨ Funcionalidades Principales

- Registro de colaboradores con datos personales y perfil laboral.
- Catálogos dinámicos para rutas, ocupaciones, tipos de empleado y tipos de planilla.
- Reporte de colaboradores con estado activo/inactivo, con acceso directo desde el formulario.
- Exportación de reporte a formato Excel (.xls).
- Validación de datos en servidor para evitar entradas inválidas.
- Formato estandarizado de identidad tipo cédula panameña (`00-0000-0000`).
- Firma digital de reportes mediante claves RSA (OpenSSL) para garantizar integridad de la información.

---

## 🛠️ Stack Tecnológico

| Componente | Especificación | Propósito Operativo |
| :--- | :--- | :--- |
| **PHP** | v8.3.28 | Procesamiento lógico en el servidor |
| **MySQL** | v8.4 | Repositorio de datos relacional |
| **Apache** | v2.4.65 | Despacho de peticiones HTTP |
| **WAMP Server** | Edición Local | Servidor de desarrollo integrado |
| **PDO** | Capa Nativa | Abstracción segura y parametrización de consultas SQL |
| **HTML5 / CSS3** | Estándar | Esqueleto estructural y personalización visual |
| **JavaScript** | Vanilla | Dinamismo del lado del cliente |
| **OpenSSL (RSA 2048)** | Extensión PHP | Firma y verificación digital de reportes |
| **Google Fonts** | Inter | Familia tipográfica principal |
| **Font Awesome** | v6.4.0 | Paquete de vectores e iconos integrados |

---

## 🗂️ Distribución del Código Fuente

```text
ParcialDSF7/
├── 📁 app/                          # Núcleo de la Aplicación (Backend)
│   ├── 📁 config/
│   │   └── 📄 BaseDatos.php         # Abstracción de Conexión PDO (Singleton)
│   ├── 📁 controllers/
│   │   └── 📄 ControladorColaborador.php
│   ├── 📁 models/
│   │   ├── 📄 Colaborador.php       # Registro y consulta de colaboradores/perfiles
│   │   ├── 📄 GrupoSanguineo.php    # Catálogo de tipos de sangre
│   │   └── 📄 Nacion.php            # Catálogo de países
│   ├── 📁 utils/
│   │   ├── 📄 Limpiador.php         # Sanitización de entradas
│   │   └── 📄 Validador.php         # Reglas de validación
│   └── 📁 views/
│       ├── 📄 formulario.php        # Alta / edición de colaboradores
│       └── 📄 reporte.php           # Listado y exportación
├── 📁 public/                       # Punto de Entrada Público
│   ├── 📄 index.php                 # Enrutador Central (Front Controller)
│   └── 📄 estilos.css
├── 📄 .htaccess                     # Configuración de URLs Amigables (Apache)
├── 📄 index.php                     # Redirección Inicial de Peticiones
└── 📄 itech_DBP.sql                 # Esquema Estructural de la Base de Datos
```

---

## 🗄️ Modelo de Datos

Base de datos: **`itech_DBP`**

| Tabla | Descripción |
| :--- | :--- |
| `colaboradores` | Datos personales y de contacto del colaborador |
| `perfiles_laborales` | Historial de perfiles laborales (uno activo por colaborador) |
| `paises` | Catálogo de países (nacionalidad / residencia) |
| `cat_tipos_sangre` | Catálogo de tipos de sangre |
| `cat_rutas` | Catálogo de rutas (Panamá Este, Oeste, Norte) |
| `cat_tipos_planilla` | Catálogo de planillas (Eventual, Permanente, Interino) |
| `cat_tipos_empleado` | Catálogo de tipos de empleado |
| `cat_ocupaciones` | Catálogo de ocupaciones / puestos |

La relación `colaboradores` → `perfiles_laborales` es de **1 a N**, donde el campo `es_activo` distingue el perfil laboral vigente del historial de perfiles anteriores.

---

## ✅ Pre-requisitos

- PHP 8.3 o superior con extensión **OpenSSL** habilitada.
- MySQL 8.4 o superior.
- Servidor Apache con `mod_rewrite` activo.
- Entorno local recomendado: **WampServer**.

---

## ⚙️ Instalación del Entorno

1. Cloná o copiá el proyecto dentro de la carpeta pública de tu servidor local (ej. `C:\wamp64\www\ParcialDSF7`).
2. Importá el esquema de base de datos ejecutando el archivo `itech_DBP.sql` en tu gestor MySQL.
3. Verificá las credenciales de conexión en `app/config/BaseDatos.php` (`host`, `dbname`, `username`, `password`).
4. Generá el par de claves RSA necesario para la firma digital de reportes:
   ```bash
   php generar_claves.php
   ```
5. Iniciá Apache y MySQL desde tu panel de WampServer.
6. Accedé a la aplicación desde el navegador:
   ```
   http://localhost/ParcialDSF7/
   ```

---

## 🌐 Rutas Disponibles

| Ruta | Método | Acción |
| :--- | :--- | :--- |
| `/` o `/formulario` | GET | Muestra el formulario de registro |
| `/guardar` | POST | Registra o actualiza un colaborador y su perfil laboral |
| `/reporte` | GET | Muestra el listado de colaboradores registrados |
| `/exportar-excel` | GET | Descarga el reporte en formato `.xls` |

> Todas las rutas se resuelven a través del *front controller* en `public/index.php`, con soporte de URLs amigables mediante `.htaccess`.

---

## 🔒 Validaciones Clave

- **Identidad:** formato obligatorio `00-0000-0000` (2 dígitos, guion, 4 dígitos, guion, 4 dígitos).
- **Correo electrónico:** validado con filtros nativos de PHP (`FILTER_VALIDATE_EMAIL`).
- **Celular:** exactamente 8 dígitos numéricos.
- **Edad:** rango permitido entre 18 y 120 años.
- **Fechas:** formato estricto `AAAA-MM-DD`; si existe fecha de fin, el motivo de baja es obligatorio.
- **Selectores (país, ruta, ocupación, tipo de empleado, planilla):** deben corresponder a un identificador numérico válido y existente en el catálogo.

---

## 🛡️ Notas de Seguridad

- Todas las consultas a la base de datos utilizan **sentencias preparadas (PDO)** para prevenir inyección SQL.
- Las entradas de texto se sanitizan (`Limpiador`) antes de validarse (`Validador`), separando ambas responsabilidades.
- Los reportes pueden firmarse digitalmente (SHA-256 + RSA 2048) para verificar su integridad.
- **Recomendación:** los archivos `check_db.php`, `test_tema.php`, `app/views/test.php` y `app/views/phpinfo.php` son utilidades de diagnóstico y **no deberían desplegarse en un entorno de producción**.

---

## 👤 Créditos

**Desarrollado por:** Samuel
**Curso:** Desarrollo de Software VII — Universidad Tecnológica de Panamá
**Proyecto:** Parcial 2 — Plataforma de Gestión de Colaboradores y Perfiles Laborales

---

<p align="center">
  <i class="fas fa-copyright"></i> 2026 iTECH Contrataciones. Todos los derechos reservados.
</p>
