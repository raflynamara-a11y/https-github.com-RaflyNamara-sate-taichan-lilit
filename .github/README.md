# Sate Taichan Lilit

Sate Taichan Lilit is a restaurant website demo for sate taichan. This repository now includes:

- `index.html` - static GitHub Pages-ready homepage.
- `assets/css/style.css` - styles for the static site.
- `index.php` - original PHP-based backend demo for local PHP hosting.

## GitHub Pages

GitHub Pages only supports static websites. To publish this repo as a web page:

1. Push the repository to GitHub.
2. In repository settings, enable GitHub Pages from the `main` branch.
3. Set the source to `/ (root)` or use the automatic Pages build workflow.
4. Wait a few minutes for the workflow to complete.
5. Visit `https://raflynamara-a11y.github.io/sate-taichan-lilit/` after publishing.

This repository includes an automated GitHub Pages workflow in `.github/workflows/pages.yml`.

## Local preview of the static site

You can preview the static page locally without PHP by opening `index.html` in a browser or using a simple server:

```bash
python3 -m http.server 8080
```

Then open:

```text
http://localhost:8080
```

## Local PHP demo

If you want to run the PHP version with database features, use this setup:

- PHP 8.0 or later
- MySQL / MariaDB server

Run with:

```bash
php -S localhost:8080
```

Then open:

```text
http://localhost:8080/index.php
```

## Default admin account for PHP demo

- Email: `admin@taichan.com`
- Password: `admin123`

## Notes

- `index.html` is the GitHub Pages-friendly version.
- `index.php` is for server-side PHP hosting only.
- GitHub Pages cannot execute PHP or use MySQL.
