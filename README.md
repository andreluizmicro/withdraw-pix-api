# Withdraw PIX API

API responsável por realizar o transações PIX.


## Sumário

- [Tecnologias usandas](#tecnologias-usadas)
- [Arquitetura](#arquitetura)
- [Diagrama C4](#diagrama-c4)
- [Fluxo de mensageria](#fluxo-de-mensageria)
- [Rodando o projeto](#rodando-o-projeto)
- [Endpoints](#endpoints)
- [DDL Banco de dados](#ddl-banco-de-dados)


### Tecnologias usadas

- `Hyperf/PHP`: API densenvolvida com o framework Hyperf
- `RabbitMQ`: Usanda para armazenar todos os eventos de sucesso e erro garantindo maior entrega de notificação.
- `MailHog` Serviço para notificação das transações via e-mail.

### Arquitetura

### Diagrama C4

<img src="./docs/diagrama-c4.png">

### Fluxo de mensageria

Atualmente o sistema conta com duas filas.

- `scheduled_pix_queue`: Fila que recebe os ids de pix agendados serem processados.
- `withdraw_que`: Recebe todas as retiradas.

<img src="./docs/rabbitmq_flow.png">

### Rodando o Projeto

O projeto foi desenvolvido usando o `Docker` e para sua execução é necessário o `Docker` e `docker-compose` previamente
instalados na maquina local.

#### Buildando a aplicação

Para realizar o build da aplicação basta rodar o comando abaixo:

```
docker compose build
```

#### Para subir todos os containers da aplicação basta rodar o comando

```
docker compose up -d
```

O arquivo `.env.example` contém todas as variáveis necessárias para o funcionamento da aplicação.

```
APP_NAME=skeleton
APP_ENV=dev

DB_DRIVER=mysql
DB_HOST=withdraw-pix-db
DB_PORT=3306
DB_DATABASE=withdraw_pix
DB_USERNAME=root
DB_PASSWORD=root
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci
DB_PREFIX=

REDIS_HOST=redis
REDIS_AUTH=(null)
REDIS_PORT=6379
REDIS_DB=0

AMQP_HOST=rabbitmq
RABBITMQ_QUEUE_CONNECTION=default
AMQP_PORT=5672
AMQP_USER=guest
AMQP_PASSWORD=guest
AMQP_VHOST=/

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=""
MAIL_FROM_ADDRESS="noreply@test.co"
MAIL_FROM_NAME="${APP_NAME}"
```

### DDL Banco de dados

Abaixo estão listadas as tabelas do banco de dados.

#### Acount
```
-- withdraw_pix.account definition

CREATE TABLE `account` (
    `id` char(36) NOT NULL,
    `name` varchar(255) NOT NULL,
    `balance` decimal(15,2) NOT NULL DEFAULT '0.00',
    `is_active` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'active',
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

```

#### Account Withdraw
```
-- withdraw_pix.account_withdraw definition

CREATE TABLE `account_withdraw` (
    `id` char(36) NOT NULL,
    `account_id` char(36) NOT NULL,
    `method` varchar(50) NOT NULL,
    `amount` decimal(15,2) NOT NULL,
    `scheduled` tinyint(1) DEFAULT '0',
    `scheduled_for` datetime DEFAULT NULL,
    `done` tinyint(1) DEFAULT '0',
    `error` tinyint(1) DEFAULT '0',
    `error_reason` varchar(255) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
     PRIMARY KEY (`id`),
     KEY `account_id` (`account_id`),
    CONSTRAINT `account_withdraw_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
```

#### Account Withdraw Pix

```
-- withdraw_pix.account_withdraw_pix definition

CREATE TABLE `account_withdraw_pix` (
    `id` char(36) NOT NULL,
    `account_withdraw_id` char(36) NOT NULL,
    `type` enum('cpf','cnpj','email','phone','random') NOT NULL,
    `key` varchar(255) NOT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `account_withdraw_id` (`account_withdraw_id`),
    CONSTRAINT `account_withdraw_pix_ibfk_1` FOREIGN KEY (`account_withdraw_id`) REFERENCES `account_withdraw` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
```

#### 

### Endpoints

Abaixo está disponibilizado o Curl dos endpoints da aplicação.

- Endpoint facilitados para criação de contas 
```
curl --location 'http://localhost:9501/v1/account' \
--header 'Content-Type: application/json' \
--data '{
    "name": "JOSE Maria"
}'
```

- Endpoint para criação de uma nova transação

```
curl --location 'http://localhost:9501/v1/account/bc6d82f7-eaac-4694-8110-53bdd8fd633e/balance/withdraw' \
--header 'Content-Type: application/json' \
--data-raw '{
    "method": "PIX",
    "pix": {
        "type": "email",
        "key": "andreluizmicro@gmail.com"
    },
    "amount": 24.00,
    "schedule": "2025-10-08"
}'
``` 