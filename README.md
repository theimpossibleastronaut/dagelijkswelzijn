# Dagelijks Welzijn

This will at some point become a health tracking service where you will be able to track your mental state and/or physical pains and potentionally correlate that data with different sensors like the weather or medicine intake.

A roadmap and features will be announced later. For now this is an early work for an idea that I've had for years to replace my paper log.

The name is Dutch, and for now my focus lies with friends and peers, but there will be internationalisation.

## Technology

The service should be as simple as possible but without sacrificing security. All tech is written in PHP and should adhere to modern standards. I will take all steps possible to my knowledge to keep your data secure.

For now this service should be git clonable and be runnable by pointing some kind of webserver to the public_html folder. Data storage is outside of the public_html so make sure your webserver can access this.

Your PHP version should support gettext, and your locales should be available on your system as this is a gettext requirement.

## Running
Run php-fpm. On os x, that's done by installing php 7.2 and caddy server for example.

```brew install php@7.2```
```brew services start php@7.2```
```cd public_html```
```caddy```

## Translating
This app is still in flux, so contributing locales isn't the best idea yet ;). But if you really want to look at the locale folder, extractor and existing languages.
POT file is generated by running php locale/extractmessages.php from the project root.
