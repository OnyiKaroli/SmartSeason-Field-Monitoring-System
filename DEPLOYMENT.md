# Deployment Instructions for Render

This guide explains how to deploy the SmartSeason Field Monitoring System to Render using the provided Docker and Blueprint configurations.

## Prerequisites

1.  A [Render](https://render.com/) account.
2.  A [GitHub](https://github.com/) account with the project repository uploaded.

## Deployment Steps

### Option 1: Using Render Blueprint (Recommended)

Render Blueprints allow you to deploy multiple services (Web Service + Database) at once using the `render.yaml` file.

1.  Log in to your Render Dashboard.
2.  Click **New +** and select **Blueprint**.
3.  Connect your GitHub repository.
4.  Render will automatically detect the `render.yaml` file.
5.  Follow the prompts to create the services.
6.  Once the deployment is complete, your app will be live at the provided URL.

### Option 2: Manual Deployment

If you prefer to set up services manually:

#### 1. Create a PostgreSQL Database
1.  Click **New +** and select **PostgreSQL**.
2.  Name it `smartseason-db`.
3.  Copy the internal Database URL or individual credentials (Host, Database, User, Password).

#### 2. Create a Web Service
1.  Click **New +** and select **Web Service**.
2.  Connect your GitHub repository.
3.  Select **Docker** as the Runtime.
4.  Add the following Environment Variables in the **Env** tab:
    - `APP_ENV`: `production`
    - `APP_DEBUG`: `false`
    - `APP_KEY`: **CRITICAL**: Run `php artisan key:generate --show` locally and paste the `base64:...` string here. Do NOT use Render's default generate button as it creates incompatible keys.
    - `DB_CONNECTION`: `pgsql`
    - `DB_HOST`: (Your database host)
    - `DB_PORT`: `5432`
    - `DB_DATABASE`: (Your database name)
    - `DB_USERNAME`: (Your database user)
    - `DB_PASSWORD`: (Your database password)
    - `LOG_CHANNEL`: `errorlog`

## Post-Deployment

- **Migrations**: The Docker image is configured to run `php artisan migrate --force` automatically on startup via `docker/entrypoint.sh`.
- **First User**: You may need to seed the database or register the first admin user. You can run commands in the Render Shell:
  ```bash
  php artisan db:seed --force
  ```

## Preventing Spin-Down (Free Tier)

Render's free tier web services spin down after 15 minutes of inactivity. To keep your service active 24/7:

1.  **Use an External Pinger**: Use a free service like [cron-job.org](https://cron-job.org/) or [UptimeRobot](https://uptimerobot.com/).
2.  **Configure the Ping**: Set up a job to send an HTTP GET request to `https://your-app.onrender.com/up` every 10-14 minutes.
3.  **Path**: Use the `/up` path, which is a lightweight health check built into Laravel.

## Laravel Task Scheduling (Cron Job)

Laravel requires a cron job to run every minute to handle scheduled tasks.

### Option 1: Render Cron Job (Paid)
If you are on a paid plan, the `render.yaml` file already includes a Cron Job service definition. It will run `php artisan schedule:run` every minute.

### Option 2: External Trigger (Free)
If you are on the free tier, you can use [cron-job.org](https://cron-job.org/) to trigger a secret URL every minute.
1. Create a route in `routes/console.php` or a dedicated web route that runs the scheduler.
2. **Note**: This is less secure and recommended only for development.

## Troubleshooting

- **Logs**: Check the **Logs** tab in the Render Dashboard for any startup errors.
- **APP_KEY Error**: If you see `Unsupported cipher or incorrect key length`, it means your `APP_KEY` is invalid. Ensure it starts with `base64:` and was generated using `php artisan key:generate`.
- **Environment**: Ensure all required environment variables are set. If you added new variables to `.env.example`, make sure to add them to Render as well.
