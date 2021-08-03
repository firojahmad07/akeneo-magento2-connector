# akeneo-magento2-connector
# Installation command
`rm -rf var/cache/* ./web/bundles/* ./web/css/* ./web/js/* && bin/console --env=prod pim:installer:assets && bin/console --env=prod cache:warmup && yarn run less && yarn run webpack && php bin/console d:s:u -f`

# update extensions
`yarn run update-extensions`