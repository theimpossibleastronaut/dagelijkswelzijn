# Dagelijks Welzijn

This will at some point become the health tracking service where you will be able to track your mental state and/or physical pains and potentionally correlate that data with different sensors like the weather or medicine intake.

A roadmap and features will be announced later. For now this is early works for an idea that I've had for years to replace my paper log.

The name is Dutch, and for now my focus lies with friends and peers, but there will be internationalisation.

## Technology

The service would be as simple as that I can make it without sacrificing for security. All tech is written in PHP and should adhere to modern standards. I will take all steps possible to my knowledge to keep your data secure.

For now this service should be git clonable and be runnable by pointing some kind of webserver to the public_html folder. Data storage is outside of the public_html so make sre your webserver can access this.

## Running
Run php-fpm. On os x, that's done by installing php 7.2 and caddy server for example.

```brew install php@7.2```
```brew services start php@7.2```
```cd public_html```
```caddy```
