#!/usr/bin/env bash
#
# Memeriksa apakah ada rahasia (API key, token, password, private key)
# yang ikut ter-commit ke Git.
#
# Jalankan sebelum push:
#   bash scripts/cek-rahasia.sh
#
# Keluar dengan kode 1 jika ditemukan sesuatu yang mencurigakan.

set -uo pipefail

if ! git rev-parse --git-dir >/dev/null 2>&1; then
    echo "Bukan repositori Git."
    exit 1
fi

masalah=0

tampilkan() {
    echo "$1" | sed 's/^/  ⚠ /'
    masalah=1
}

echo "== 1. File yang seharusnya tidak di-commit =="
terlarang=$(git ls-files | grep -E '(^|/)(\.env|\.env\..*|.*\.pem|.*\.key|.*\.p12|.*\.pfx|auth\.json|.*\.sql|.*\.dump|.*\.sqlite)$' | grep -v '^\.env\.example$' || true)
if [ -n "$terlarang" ]; then
    tampilkan "$terlarang"
else
    echo "  ✓ bersih"
fi

echo "== 2. Pola rahasia di isi file =="
pola='(sk-[A-Za-z0-9]{20,}|sk_live_[A-Za-z0-9]{10,}|AKIA[0-9A-Z]{16}|gh[pousr]_[A-Za-z0-9]{20,}|xox[baprs]-[A-Za-z0-9-]{10,}|AIza[0-9A-Za-z_-]{30,}|-----BEGIN (RSA|OPENSSH|EC|DSA) PRIVATE KEY-----|postgres(ql)?://[^[:space:]]+:[^[:space:]]+@)'
temuan=$(git grep -nIE "$pola" -- . ':!docs' ':!scripts/cek-rahasia.sh' || true)
if [ -n "$temuan" ]; then
    tampilkan "$temuan"
else
    echo "  ✓ bersih"
fi

echo "== 3. Penugasan nilai rahasia yang mencurigakan =="
pola2="(api[_-]?key|secret|token|passwd)[[:space:]]*[:=][[:space:]]*[\"'][^\"']{12,}[\"']"
temuan2=$(git grep -nIiE "$pola2" -- app config database routes tests .env.example || true)
if [ -n "$temuan2" ]; then
    tampilkan "$temuan2"
else
    echo "  ✓ bersih"
fi

echo
if [ "$masalah" -ne 0 ]; then
    echo "HASIL: ada hal yang perlu diperiksa sebelum push."
    exit 1
fi

echo "HASIL: aman. Tidak ditemukan rahasia yang ter-commit."
