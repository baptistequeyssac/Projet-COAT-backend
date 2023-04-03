# création du projet
composer create-project symfony/skeleton project
mv project/* project/.* .
rmdir project/
# Twig
composer require annotations
composer require twig
# Twig et debug
composer require symfony/asset
composer require --dev symfony/profiler-pack
composer require --dev symfony/var-dumper
composer require --dev symfony/debug-bundle
composer require --dev symfony/maker-bundle
# Doctrine
composer require symfony/orm-pack
composer require --dev orm-fixtures
# Formulaires
composer require symfony/form
composer require symfony/validator

# make:crud
composer require security-csrf

# En vrac pour projet APO :
composer require --dev fakerphp/faker
composer require symfony/security-bundle
composer require symfony/http-client
composer require symfony/serializer
composer require nelmio/api-doc-bundle
composer require --dev symfony/test-pack
composer require symfony/apache-pack