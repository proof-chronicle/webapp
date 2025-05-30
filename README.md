<p align="center"><img src="public/images/logo.svg" alt="Logo" height="100px"></p>


## About the project

The service provides an API for integration with news platforms. Every article published by a partner platform is automatically indexed by ProofChronicle and recorded as a hash on the blockchain. An interactive banner is displayed on the article’s webpage, indicating whether the content has remained unchanged or has been modified. If any changes are detected, users can access a detailed comparison page showing both the original text and a screenshot of the original version.

## project structure

We are trying to keep all our moving parts separated, so here is a brief overview of the project structure:
- [webapp](https://github.com/proof-chronicle/webapp) - our frontend application, built with Laravel. Provides user interface for the service. Contain user dashboard and public API.
- [content indexer](https://github.com/proof-chronicle/content-indexer) - a service writen on Golang that listens to the events from the webapp and indexes the articles. It also generates the hashes and stores them on the blockchain.
- [chain-gateway](https://github.com/proof-chronicle/chain-gateway) - our blockchain gateway writen on Rust, which is responsible for interacting with the blockchain. It provides an API for the content indexer to store and retrieve hashes. Provides unified API for all blockchain interactions.
- [solana-proof-store](https://github.com/proof-chronicle/solana-proof-store) - a smart contract deployed on the Solana blockchain, which is used to store the hashes of the articles.
- [infrastructure](https://github.com/proof-chronicle/infrastructure) - a repository that contains the Docker Compose configuration and other infrastructure-related files. It is used to run the entire project locally.

<p align="center"><img src="https://github.com/proof-chronicle/infrastructure/raw/master/docker_compose_architecture.png" height="600px"></p>


## Installation
To run the project locally, you need to:
- Clone all the repositories into the same directory.
- Configure the `.env` files in each repository. You can use the `.env.example` files as a reference.
- Enter the `infrastructure` directory 
- Run `docker-compose up --build`.
- Wait for the containers to start. It may take a few minutes.

After that you can accessL
- Webapp at `http://localhost:80`
- Rabbitmq at `http://localhost:15672`
- Adminer at `http://localhost:8080`
- Solana explorer at `http://localhost:3000`.


## Webapp configuration
- `docker compose exec webapp composer install`: Install composer dependencies
- `docker compose exec webapp php artisan migrate`: Run migrations
- `docker compose exec webapp php artisan migrate --seed`: Run migrations and seed the database
