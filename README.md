# OLX watcher service

Disclaimer: Do not event ask me why I reinvent the wheel. The project does not allow to use any library or frameworks.

## Features

- Subscribe on olx ad
- Notification via email

## How to run

- deploy docker compose

```bash
docker compose up -d
```

- watch docker logs till database is ready

```bash
docker compose logs -f
```

- setup database schema

```bash
chmod +x ./setup_db.sh
./setup_db.sh
```

- Go to http://127.0.0.1:3000

## Contributors

- [Dmytro Laptiev](https://github.com/AkioSarkiz)
