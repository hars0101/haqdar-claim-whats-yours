# HaqDar – Claim What's Yours
# live link: https://iharsha.online/haqdar/public/
HaqDar helps low-income students in Telangana, India find government scholarships they are likely to qualify for. It explains why, lists the documents you need, guides you through applying, and tracks deadlines.

> **TODO before going live:** Every scheme value in `seed.sql` (income limits, benefit amounts, deadlines, documents, and web links) MUST be checked by a person against the official government source. Do not launch until this is done.

---

## Setup on cPanel (step by step)

**(a) Create the database.**
In cPanel, open **MySQL Databases**. Create a new database. Then create a new database user. Add the user to the database and give it **ALL PRIVILEGES**.
Write down the database name, user name, and password.

**(b) Load the tables and the scheme list.**
Open **phpMyAdmin**. Click your database on the left. Click **Import**, choose `schema.sql`, and run it. Then import `seed.sql` the same way.

**(c) Upload the project and set the website folder.**
Upload the whole project folder to your home folder, for example `/home/USER/haqdar/`.
In cPanel, open **Domains** (or **Subdomains**). Create a domain such as `haqdar.example.com`. Set its **Document Root** to the `public` folder, for example `/home/USER/haqdar/public`.

**(d) Create the config file.**
Copy `config.sample.php` to `/home/USER/haqdar/config.php`.
This file must sit **ABOVE** the `public` folder, not inside it.
Open it and fill in:
- your database name, user, and password
- an LLM key (optional, see below)
- `admin_password_hash` (see step e)
- `base_url` (your full web address, for example `https://haqdar.example.com`)

**(e) Make the admin password.**
On your computer, run this command. Replace `your-password` with the password you want:

```
php -r "echo password_hash('your-password', PASSWORD_DEFAULT);"
```

It prints a long code. Copy it and paste it into `admin_password_hash` in `config.php`.
The admin page is at **https://YOUR-DOMAIN/admin.php**.

**(f) Choose the PHP version.**
In cPanel, open **MultiPHP Select PHP Version**. Choose **PHP 8.x**. Make sure these extensions are ticked: **pdo_mysql, curl, mbstring, json**.

**(g) Set the daily reminder.**
In cPanel, open **Cron Jobs**. Add this job to run every day at 08:00:

```
0 8 * * * /usr/local/bin/php /home/USER/haqdar/app/cron/reminders.php
```

Cron uses the PHP program path. Use the PHP path that cPanel shows you on the Cron Jobs page. If it is different from `/usr/local/bin/php`, change it.

**(h) Check that it works.**
Open your website. Click one of the sample people (personas). Check that scholarship results appear.

---

## Testing on your Windows computer

1. Install PHP 8 and MySQL. Or install **XAMPP**, which includes both.
2. Create a database. Import `schema.sql` and then `seed.sql`.
3. Copy `config.sample.php` into a file named `config.php`. Put it in the folder **ABOVE** `public`, which is the project folder itself.
4. Open a command prompt in the project folder and run:

```
php -S localhost:8000 -t public
```

5. Open http://localhost:8000 in your browser.

---

## Troubleshooting

- **Blank page:** Open the error log in cPanel (**Errors** or **Error Log**). It tells you what went wrong.
- **Reminder emails do not arrive:** Some hosts turn off the PHP `mail()` function. In that case the reminders fail quietly. Check the logs.
- **LLM key is optional:** Without a key, the site uses its built-in rules instead. It still works.

---

## Files

- `schema.sql` – creates the database tables
- `seed.sql` – adds the list of scholarship schemes (must be verified, see TODO above)
- `config.sample.php` – template for your private settings file
- `public/` – the only folder the website should point to
- `app/` – application code (not served to visitors)
