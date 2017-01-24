My Restaurant Software
========================
## Server

Open source software to take orders from customers. The system is to facilitate the work for the
adoption of orders and better communication between customer service and the kitchen.

For details on how to download and get started with MRS_Server, see the
[Installation](INSTALL.md) chapter of the documentation.

What's inside?
--------------

The MRS Server is RestAPI server based on Symfony 3 Framework.

All libraries and bundles included in the MRS_Server are
released under the MIT or BSD license.

Enjoy!

Commands
--------
- Baza danych
    - `php bin/console doctrine:schema:update --force` - Aktualizacja struktury bazy
    - `php bin/console doctrine:generate:entity` - Tworzenie nowej tabeli
- Bundle
    - `php bin/console generate:bundle` - Generator Bundle
- Cache
    - `php bin/console cache:clear --env=prod` - Czyszczenie cache produkcja
    - `php bin/console cache:clear` - Czyszczenie cache dev
- Assets
    - `php bin/console assets:install --symlink` - Instalacja assetów
- FOSUserBundle
    - `php bin/console fos:user:create login owner@email.chuj passlo` - Tworzenie nowego użytkownika
    - `php bin/console fos:user:promote testuser ROLE_ADMIN` - Promowanie użytkownika na admina
    - `php bin/console fos:user:promote testuser ROLE_SUPER_ADMIN` - Promowanie użytkownika na super admina
- Controllers
    - `php bin/console generate:controller` - Generator kontrolerów