# Deploy Trade4Deal to GoDaddy

Pushes to `main` run [Deploy to GoDaddy](../.github/workflows/deploy-godaddy.yml) via FTP.

## GitHub secrets (required)

Repository → **Settings** → **Secrets and variables** → **Actions** → **New repository secret**:

| Secret | Example |
|--------|---------|
| `FTP_SERVER` | `ftp.rvg.d1c.mytemp.website` |
| `FTP_USERNAME` | `t4d@trade4deal.com` |
| `FTP_PASSWORD` | (FTP account password) |

## After each deploy (SSH / cPanel Terminal)

```bash
cd ~/public_html   # or your app root on the server
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Keep production `.env` only on the server; it is never uploaded by the workflow.

## Manual deploy

Actions tab → **Deploy to GoDaddy** → **Run workflow**.
