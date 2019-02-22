L-systems
===========
Реализация алгоритма систем Линденмайера(l-систем) на PHP
---------------

* **SVG** - используется для визуализации l-систем по принципу "Черепашьей графики". 
* **Silex** - был использован для роутинга, и мультиязычности интерфейса.

**Описание основных классов:**
* `/app/src/Lsystems/Src/Lsystem.php` - Основной класс алгоритма L-систем
* `/app/src/Lsystems/Src/Turtle.php` - Класс черепахи, звисимость от интерфейса `GraphicInterface.php`
* `/app/src/Lsystems/Src/SvgGraphic.php` - Класс реализует интерфейс `GraphicInterface.php` 
