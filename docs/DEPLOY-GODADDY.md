# Deploy Trade4Deal to GoDaddy

Pushes to `main` run [Deploy to GoDaddy](../.github/workflows/deploy-godaddy.yml) via FTP.

## GitHub secrets

| Secret | Example |
|--------|---------|
| `FTP_SERVER` | `ftp.rvg.d1c.mytemp.website` |
| `FTP_USERNAME` | `t4d@trade4deal.com` |
| `FTP_PASSWORD` | FTP account password |

## After deploy (SSH / cPanel Terminal)

```bash
cd ~/public_html
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
