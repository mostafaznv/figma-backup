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


## Important ENV keys



## License

The FigmaBackup is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
