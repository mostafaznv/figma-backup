
## Deploy

1. Install PHP-FPM / MySQL / Nginx / NPM
2. Install PHP Dependencies
    ```shell
    composer install --optimize-autoloader --no-dev
    ```
3. Install Node Modules
   ```shell
   npm install
   npm run build
   ```
4. Set Environment Variables
    ```shell
    cp .env.example .env
    ```
    > Edit `.env` file

5. Optimizing `routes` and `cache`
    ```shell
    php artisan key:generate
    php artisan optimize
    ```

6. Migrate
    ```shell
    php artisan migrate
    ```

7. Add Schedule Command to Crontab
    ```shell
    * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
    ```

## Create Admin
```shell
php artisan make:admin 
```


## License

The FigmaBackup is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
