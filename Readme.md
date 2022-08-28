ProcessWire Up!
===============

[![ProcessWire Up!](https://circleci.com/gh/dadish/pwup.svg?style=svg)](https://circleci.com/gh/dadish/pwup)

Docker services to start up a ProcessWire website.

## Requirements

- [Docker](https://docs.docker.com/get-docker/)

## Usage

- Clone this repository.
- Run `cp .env.example .env`.
- Edit `.env` file with the desired configuration variables.
- Run `docker compose up -d --wait` and wait till the command finishes running. When running for the first time it might take a while.
- Navigate to `127.0.0.1` (`localhost`).