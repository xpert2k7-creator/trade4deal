#!/usr/bin/env bash
# Local filesystem/command simulation; never connects to a server or database.
set -euo pipefail
script_dir="$(cd "$(dirname "$0")" && pwd)"
sandbox="$(mktemp -d /tmp/trade4dealtest-XXXXXX)"
upload=""
cleanup() {
    [[ "$sandbox" == /tmp/trade4dealtest-* ]] && rm -rf -- "$sandbox"
    if [[ "$upload" =~ ^/tmp/trade4deal-[0-9]+-[0-9]+$ ]]; then
        rm -rf -- "$upload"
    fi
}
trap cleanup EXIT
mkdir -p "$sandbox/bin" "$sandbox/package/bootstrap/cache"
touch "$sandbox/package/artisan"
cat > "$sandbox/bin/php" <<'PHP'
#!/usr/bin/env bash
set -eu
echo "$*" >> "$DEPLOY_TEST_LOG"
[[ "${DEPLOY_FAIL_MIGRATE:-false}" != true || "${2:-}" != migrate ]]
PHP
cat > "$sandbox/bin/curl" <<'CURL'
#!/usr/bin/env bash
[[ "${DEPLOY_FAIL_HEALTH:-false}" != true ]]
CURL
chmod +x "$sandbox/bin/php" "$sandbox/bin/curl"
if ! command -v flock >/dev/null; then
    # Git Bash lacks flock. Linux CI uses the real command; Windows simulates only release/rollback.
    printf '#!/usr/bin/env bash\nexit 0\n' > "$sandbox/bin/flock"
    chmod +x "$sandbox/bin/flock"
    echo 'NOTE: flock unavailable; lock acquisition is not verified on this platform.'
fi
export PATH="$sandbox/bin:$PATH"
export DEPLOY_TEST_LOG="$sandbox/commands.log"
for scenario in success migration-failure health-failure; do
    root="$sandbox/$scenario"
    mkdir -p "$root/shared" "$root/releases/previous"
    touch "$root/shared/.env" "$root/releases/previous/artisan"
    ln -s "$root/releases/previous" "$root/current"
    release_id="${RANDOM}${RANDOM}-1"
    upload="/tmp/trade4deal-$release_id"
    mkdir "$upload"
    tar -czf "$upload/release.tar.gz" -C "$sandbox/package" .
    export DEPLOY_FAIL_MIGRATE=false DEPLOY_FAIL_HEALTH=false
    [[ "$scenario" != migration-failure ]] || export DEPLOY_FAIL_MIGRATE=true
    [[ "$scenario" != health-failure ]] || export DEPLOY_FAIL_HEALTH=true
    if bash "$script_dir/deploy-release.sh" "$root" "$release_id" "$upload/release.tar.gz" https://example.com/up > "$sandbox/$scenario.log" 2>&1; then
        [[ "$scenario" == success ]]
        [[ "$(readlink "$root/current")" == "$root/releases/$release_id" ]]
    else
        if [[ "$scenario" == success ]]; then
            cat "$sandbox/$scenario.log" >&2
        fi
        [[ "$scenario" != success ]]
        [[ "$(readlink "$root/current")" == "$root/releases/previous" ]]
    fi
    echo "PASS: $scenario"
    rm -f "$upload/release.tar.gz"
    rmdir "$upload"
    upload=""
done
