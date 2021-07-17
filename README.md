# micro-api

⚡ Micro API using Phalcon Framework

## I. Requirements

- _A Laptop_ 😉
- Docker (docker-compose\*)
- Makefile (cli)

## II. Installation

Run commad below to install docker containers using docker-compose

```bash
make run
```

## III. Architecture

- Web Server `(Nginx)`
- Cache `(Redis)`
- Rest API `(PHP - Phalcon framework)`
- Databse `(SQL - Postgres)`
- Storage `(Minio S3 Object Storage)`
- Mailer `(SMTP / PHPMail)`

## IV. Usage

1. Run (build) docker containers

```bash
make run
```

2. Stop containers

```bash
make stop
```

3. Clean all (drop: containers, volumns..)

```bash
make dclean
```

4. Run bash on php service

```bash
make console
```

5. Database Migration

```bash
make migrate
```

6. Run composer on the php container

```bash
make composer
```

7. View Logs (live) of the php container

```bash
make dlogs
```
