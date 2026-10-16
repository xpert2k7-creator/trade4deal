#!/usr/bin/env bash
set -Eeuo pipefail

root="${1:?Deployment root required}"
release_id="${2:?Release ID required}"
archive="${3:?Archive required}"
health_url="${4:?HTTPS health URL required}"
[[ "$root" =~ ^/[a-zA-Z0-9/_-]+$ && "$root" != / && "$root" == /*/*/* ]]
[[ "$release_id" =~ ^[0-9]+-[0-9]+$ ]]
[[ "$archive" == "/tmp/trade4deal-$release_id/release.tar.gz" ]]
[[ "$health_url" =~ ^https://[a-zA-Z0-9./_-]+$ ]]
[[ -f "$root/shared/.env" ]] || { echo 'Provision shared/.env before deployment.' >&2; exit 1; }
[[ ! -e "$root/current" || -L "$root/current" ]] || { echo 'current must be a release symlink.' >&2; exit 1; }

mkdir -p "$root/releases" "$root/shared/storage/app/public" "$root/shared/storage/app/private" \
    "$root/shared/storage/framework/cache/data" "$root/shared/storage/framework/sessions" \
    "$root/shared/storage/framework/views" "$root/shared/storage/logs"
exec 9>"$root/deploy.lock"
flock -n 9 || { echo 'Another deployment is running.' >&2; exit 1; }
release="$root/releases/$release_id"
[[ ! -e "$release" ]] || { echo 'Release already exists.' >&2; exit 1; }
previous="$(readlink -f "$root/current" 2>/dev/null || true)"
activated=false
maintenance=false
rollback() {
    result=$?
    trap - ERR
    if [[ "$activated" == true && -n "$previous" ]]; then
        ln -s "$previous" "$root/current.rollback"
        mv -Tf "$root/current.rollback" "$root/current"
    elif [[ "$activated" == true ]]; then
        rm -f "$root/current"
    fi
    if [[ "$maintenance" == true && -n "$previous" ]]; then
        (cd "$previous" && php artisan up) || true
    fi
    echo 'Deployment failed. Previous application release retained; review migrations before retrying.' >&2
    exit "$result"
}
trap rollback ERR
mkdir "$release"
tar -xzf "$archive" -C "$release"
ln -s "$root/shared/.env" "$release/.env"
ln -s "$root/shared/storage" "$release/storage"
mkdir -p "$release/bootstrap/cache"
cd "$release"
php artisan config:cache
php artisan app:check-production
php artisan route:cache
php artisan view:cache
php artisan storage:link
if [[ -n "$previous" ]]; then
    (cd "$previous" && php artisan down --retry=60)
    maintenance=true
fi
# Back up the database before deploying. Migrations must support the prior release for rollback.
php artisan migrate --force
ln -s "$release" "$root/current.next"
mv -Tf "$root/current.next" "$root/current"
activated=true
php artisan up
curl --fail --silent --show-error --retry 3 --retry-delay 2 --max-time 20 "$health_url" >/dev/null
php artisan queue:restart
trap - ERR
rm -f "$archive"
echo "Activated release $release_id; previous release retained at $previous"
