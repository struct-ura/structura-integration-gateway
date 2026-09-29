# Structura Integration Gateway

Structura Integration Gateway is a lightweight Nextcloud app for centrally managed JavaScript integrations.

Administrators can configure custom JavaScript through the Nextcloud administration settings. Configured JavaScript is loaded only for authenticated Nextcloud users.

The JavaScript is not injected on login or unauthenticated pages, and the JavaScript endpoint itself requires an authenticated Nextcloud session.

## How it works

Custom JavaScript configured by an administrator is automatically loaded for signed-in users.

## Upstream

Structura Integration Gateway is based on the open-source Nextcloud JSLoader project:

https://github.com/nextcloud/jsloader

The project is maintained as an independent Structura fork while retaining the applicable original copyright and licensing information.

## License

AGPL-3.0-or-later. See the license files included in this repository.
