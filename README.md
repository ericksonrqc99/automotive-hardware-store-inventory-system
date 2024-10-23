## Installation

1. Clone the repository:

    ```sh
    git clone https://github.com/your-repo/project-name.git
    cd project-name
    ```

2. Install dependencies:

    ```sh
    composer install
    npm install
    ```

3. Copy the example environment file and configure it:

    ```sh
    cp .env.example .env
    ```

4. Generate the application key:

    ```sh
    php artisan key:generate
    ```

5. Run the migrations:
    ```sh
    php artisan migrate
    ```

## Usage

To start the development server, run:

```sh
php artisan serve
```
