# agenda-online-v4
Nova versão do aplicativo WEB Agenda


# Diagrama banco de dados
- `https://dbdiagram.io/d/agenda-online-666b3419a179551be6d4a6d5`


# Configurações do Projeto

- **Caminho do Arquivo de Configuração**:
    - `/var/www/html/config/env/cadastro-fornecedor/.env`
  
Este repositório contém um arquivo de configuração em linguagem Shell Script que define várias variáveis de ambiente para configurar um sistema ou aplicação. Abaixo está uma explicação das principais variáveis definidas no arquivo:


- **URL**:
    - URL base do projeto: `http://localhost:80/app-agenda`.

- **Dados de banco de dados**:
    - `DB_HOST`: `localhost`.
    - `DB_NAME`: `database`.
    - `DB_USER`: `<usuário_de_acesso_ao_banco>`.
    - `DB_PASS`: `<senha_do_banco>`.
    - `BD_PORT`: `3306`.

- **Modo de Manutenção**:
    - `MAINTENANCE`: `false`.

- **Limites de Paginação**:
    - `LIMIT_FRONT_TESTIMONIES`: 2.
    - `LIMIT_ADMIN_USERS`: 5.
    - `LIMIT_API_EVENTS`: 5.

- **Caminhos da Aplicação**:
    - `PATH_ADMIN`: `/admin`.
    - `PATH_AGENDA`: `/agenda`.
    - `PATH_MAIN`: `application`.
    - `PATH_LOGO_PNG_IASD`: `lib/img/logo-circular-iasd.png`.
    - `PATH_LOGO_ICO`: `lib/img/favicon.ico`.
    - `PATH_LOGO_PNG`: `lib/img/favicon.png`.

- **Chaves**:
    - `PRIVATE_KEY`: `private_key.pem`.
    - `PUBLIC_KEY`: `public_key.pem`.

- **Tempo de Expiração da Sessão**:
    - `SESSION_EXPIRATION`: 90 minutos.
    - `SESSION_NAME`: `agenda_online`.

- **Configuração de Notificações [DESATIVADO]**:
    - `ID_TWILIO`: `AC05876be33f28d7c9eddd11dcaef1f0d1`.
    - `TOKEN_TWILIO`: `f89ffb302208aebd32bd71c7848041ad`.
    - `FROM_TWILIO`: `+16204136869`.
- **Configuração de Notificação por WhatsApp**
    - `TEMPLATE_SEND_AGENDA_WHATSAPP`:`notificacao_escala_sonoplastia`
    - `TEMPLATE_SEND_MODIFY_AGENDA_WHATSAPP`:pedido_alteracao_escala`
    - `TEMPLATE_REMINDER_AGENDA_WHATSAPP`:`lembre_escala_sonoplastia`
    - `TEMPLATE_SEND_RECEPTION_WHATSAPP`:`lembrete_escala_recepcao`
    - `TEMPLATE_SEND_WORSHIP_WHATSAPP`:`lembrete_escala_louvor`
    - `URL_BASE_API_WHATSAPP`: `https://graph.facebook.com`
    - `VERSION_API_WHATSAPP`: `v20.0`

Essas variáveis são utilizadas para personalizar o comportamento da aplicação de acordo com as necessidades específicas do projeto.


# Endpoints APIs

- URL_BASE: `http://localhost:80/app-agenda/api`
## Endpoint: Get Info API
### Method: GET
>```
>{{URL_BASE}}/v1
>```
### Headers

|Content-Type|Value|
|---|---|
|Content-Type|application/json|


### Headers

|Content-Type|Value|
|---|---|
|x-api-key||


### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token||string|



⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: Gerar Token
### Method: POST
>```
>{{URL_BASE}}/v1/auth
>```
### Headers

|Content-Type|Value|
|---|---|
|x-api-key||


### Body (**raw**)

```json
{
    "username" : "email@email.com.br",
    "password" : "********"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## Endpoint: Check Token
### Method: POST
>```
>{{URL_BASE}}/v1/check
>```
### Headers

|Content-Type|Value|
|---|---|
|Content-Type|application/json|


### Headers

|Content-Type|Value|
|---|---|
|x-api-key||


### Body (**raw**)

```json
{
    "token" : ""
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## Endpoint: Get Events
### Method: GET
>```
>{{URL_BASE}}/v1/event?page=25
>```
### Headers

|Content-Type|Value|
|---|---|
|Content-Type|application/json|


### Query Params

|Param|value|
|---|---|
|page|25|


### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token||string|



⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## Endpoint: Update Overdue AskToChange
### Method: POST
>```
>{{URL_BASE}}/v1/ask-to-change/overdue
>```
### Headers

|Content-Type|Value|
|---|---|
|Content-Type|application/json|


### Body (**raw**)

```json
{}
```

### 🔑 Authentication noauth

|Param|value|Type|
|---|---|---|

⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## Endpoint: Get By Event
### Method: GET
>```
>{{URL_BASE}}/v1/event/131
>```
### Headers

|Content-Type|Value|
|---|---|
|Content-Type|application/json|


### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|eyJhbGciOiJBMjU2S1ciLCJlbmMiOiJBMjU2Q0JDLUhTNTEyIiwiemlwIjoiREVGIn0.XIxIUDWst9VqDKu-8UUgEeEe8zBtdLmDEw1cjSgITn8oyrHB7BxuOqjTZq0z6iqXtPvC_ucMXooZf9EHuy7xmgXQQmnkHD5V.x7D3t9uZnk0GGuW_AxEQRg.gRnnOWdTaRBIvfS6pqBavpjfoaf8BLk50diY-3ziQ1uDISRmticmzIn-YxUFvf8H27CjCjj-2Zu5aVW4VpXdBxhug2dR00WcdLjikg8kUtaOnO9GVSMVFQtS1LQXQosTkjrZgX5PgOv-8LQeK0O5iXLhz6dNOWT8DJsHJglr88kbkSMlRhwEdUK3CKORfyjfe9IL0Rl4muE9RlW5tAA9Ig.ETl3xTSvlc_ah1BaN8ncGjbZNkcmnPUlXAHmGch-4_0|string|



⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃
