# Indian Sports League — shareable prototype

Self-contained static snapshot of the ISL prototype. **No server or install needed** — just open
the files in a browser.

## How to view

- Start at **`overview.html`** — a gallery linking to every screen, or
- Open **`index.html`** — the landing page; use the top nav to move around.

Navigation, the knockout bracket, and the registration/certificate flows are all clickable. The
admin panel is reachable from the **Admin** link (or `admin-login.html`) — the **Sign in** button
opens the dashboard.

## Notes

- Styles and fonts are bundled in `assets/` and work offline. An internet connection is only needed
  for minor menu animations (Alpine.js loads from a CDN).
- These are exported HTML files for **sharing/review only**. The real, editable application is the
  Laravel project in the parent folder.

## Regenerating

After changing the Laravel app, re-export from the project root:

```bash
php artisan serve --port=8099     # one terminal
php export-prototype.php           # another terminal
```
