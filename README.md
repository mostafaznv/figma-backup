# FigmaBackup


## Deploy (Docker)

1. Install `docker` and `docker-compose`

2. Create a non-root user and add it to the `docker` group, then switch to the new user
    ```shell
    sudo adduser USERNAME
    sudo usermod -aG docker USERNAME
    sudo -su USERNAME
    ```

3. Clone the repository
    ```shell
    git clone https://github.com/mostafaznv/figma-backup.git
    ```

4. Set Environment Variables
    ```shell
    cp .env.example .env
    ```
   > Note: Edit the `.env` file

5. Build and Run
    ```shell
    make build
    make start
    ```

6. Install Dependencies
    ```shell
    make install-dependencies
    ```

7. Migrate
    ```shell
    make migrate
    ```
   
8. Generate key
    ```shell
    make key-generate
    ```

9. Create Admin
    ```shell
    make make-admin
    ```

10. Open the browser and go to `http://localhost`

## Manually Run Backup Commands
Download figma backup files
```shell
make figma-backup
```

Delete old figma backup files
```shell
make figma-delete-old-files
```

## Important ENV keys

| Key                       | Default Value                              | Description                                                     |
|---------------------------|--------------------------------------------|-----------------------------------------------------------------|
| APP_URL                   | https://figma-backup.com                   | The URL of the application                                      |
| DB_DATABASE               | figma-backup                               | The name of the database                                        |
| DB_PASSWORD               | Z95ikJmR8V879tFcE <br> **Note:** Change it | The password of the database                                    |
| FIGMA_EMAIL               | username@gmail.com                         | The email of your figma account                                 |
| FIGMA_PASSWORD            | password                                   | The password of your figma account                              |
| FIGMA_TOKEN               | token                                      | The token of your figma account                                 |
| TELEGRAM_MAX_FILE_SIZE    | 49                                         | The maximum file size which can be sent to Telegram (megabytes) |
| TELEGRAM_BACKUPS_SEND_TO  | 11111,22222                                | Telegram chat id(s) to send backups to                          |
| TELEGRAM_WARNINGS_SEND_TO | 33333,44444                                | Telegram chat id(s) to send warnings to                         |
| TELEGRAM_BOT_TOKEN        | token                                      |                                                                 |
| NOCAPTCHA_SECRET          | secret                                     | The Google recaptcha (v2) secret key                            |
| NOCAPTCHA_SITEKEY         | site-key                                   | The Google recaptcha (v2) site key                              |
| X_SENDFILE_BASE_PATH      | /home/ubuntu/figma-backup/                 | The root path of the project                                    |
| WEB_PORT_HTTP             | 80                                         | The port of the web server (http)                               |


## More Information

### How to get Figma AccessToken
1. Login to your Figma account.
2. Head to the account settings from the top-left menu inside Figma.
3. Find the personal access tokens section.
4. Click Create new token.
5. A token will be generated. This will be your only chance to copy the token, so make sure you keep a copy of this in a secure place.

### How to get Figma ProjectId
To get `project_id` from project page's URL:
`https://www.figma.com/files/project/{project_id}/{project_name}`

### How to find your Telegram UserId
1. Open Telegram and search for `@userinfobot`
2. Send `/start` to the bot
3. Copy the `ID` value

### How to get Telegram Bot Token
Talk to [@BotFather](https://t.me/botfather) and generate a Bot API Token.

### How to get Google reCAPTCHA v2 keys
You can obtain them from [here](https://www.google.com/recaptcha/admin)

## License
The FigmaBackup is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
