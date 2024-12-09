# Shopping Website

A simple shopping website built in PHP.

## Dependencies

- PHP + mysqli
- MySQL

## Local Development

1. Start the MySQL Server instance.
2. Run the queries found in `database/create.sql` and `database/insert.sql`
3. Copy `.env.example` to `.env`:

```bash
cp .env.example .env
```

4. Enter your credentials into `.env`

5. Run the PHP server:

```bash
php -S localhost:8000
```

6. Visit [http://localhost:8000](http://localhost:8000)

## Deploying

1. Create the database using a tool like phpMyAdmin
2. Run the queries found in `database/create.sql` and `database/insert.sql`
3. Enter credentials into `.env`
4. Deploy
