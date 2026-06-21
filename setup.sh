#!/bin/bash
#=========================================================================
# SATE TAICAN LILIT - LINUX/MAC SETUP SCRIPT
#=========================================================================
# Usage: bash setup.sh
#=========================================================================

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Functions
print_status() {
    local message=$1
    local status=${2:-"INFO"}
    
    case $status in
        OK)
            echo -e "${GREEN}✅ $message${NC}"
            ;;
        ERROR)
            echo -e "${RED}❌ $message${NC}"
            ;;
        WARN)
            echo -e "${YELLOW}⚠️  $message${NC}"
            ;;
        PROCESS)
            echo -e "${CYAN}⏳ $message${NC}"
            ;;
        *)
            echo -e "${BLUE}ℹ️  $message${NC}"
            ;;
    esac
}

# Header
echo ""
echo -e "${BLUE}========================================================================${NC}"
echo -e "${BLUE}  SATE TAICAN LILIT - AUTOMATIC SETUP (Linux/Mac)${NC}"
echo -e "${BLUE}========================================================================${NC}"
echo ""

# Check for required tools
print_status "Mengecek dependencies..." "PROCESS"

command -v php >/dev/null 2>&1 || { echo "PHP tidak terinstall"; exit 1; }
command -v mysql >/dev/null 2>&1 || { echo "MySQL tidak terinstall"; exit 1; }

print_status "PHP dan MySQL terinstall" "OK"
echo ""

# Get project directory
PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
print_status "Project directory: $PROJECT_DIR" "OK"

# Create backups folder
if [ ! -d "$PROJECT_DIR/backups" ]; then
    mkdir -p "$PROJECT_DIR/backups"
    print_status "Folder backups dibuat" "OK"
fi

echo ""

# Test database connection
print_status "Testing database connection..." "PROCESS"

mysql -u root -e "SELECT 1" >/dev/null 2>&1
if [ $? -eq 0 ]; then
    print_status "Database connection OK" "OK"
else
    print_status "Database connection FAILED" "ERROR"
    echo "Pastikan MySQL sudah berjalan dengan: brew services start mysql"
    exit 1
fi

echo ""

# Import database
print_status "Importing database..." "PROCESS"

if mysql -u root -e "USE sate_taican_db" >/dev/null 2>&1; then
    print_status "Database sudah ada" "WARN"
else
    mysql -u root < "$PROJECT_DIR/db/database.sql"
    print_status "Database berhasil diimport" "OK"
fi

echo ""

# Start PHP server
print_status "Memulai PHP server..." "PROCESS"

cd "$PROJECT_DIR"
php -S localhost:8000 > /dev/null 2>&1 &
PHP_PID=$!
print_status "PHP server berjalan di: http://localhost:8000" "OK"

echo ""

# Open browser
if command -v open >/dev/null 2>&1; then
    # macOS
    open "http://localhost:8000"
elif command -v xdg-open >/dev/null 2>&1; then
    # Linux
    xdg-open "http://localhost:8000"
fi

echo -e "${BLUE}========================================================================${NC}"
echo -e "${GREEN}  SETUP SELESAI!${NC}"
echo -e "${BLUE}========================================================================${NC}"
echo ""

echo -e "${CYAN}📍 Akses Website:${NC}"
echo -e "${GREEN}   http://localhost:8000${NC}"
echo -e "${GREEN}   Admin: http://localhost:8000/admin/${NC}"
echo ""

echo -e "${CYAN}🔐 Admin Credentials:${NC}"
echo -e "${GREEN}   Username: admin${NC}"
echo -e "${GREEN}   Password: admin123${NC}"
echo ""

echo -e "${CYAN}⏹️  Untuk menghentikan server:${NC}"
echo -e "${GREEN}   kill $PHP_PID${NC}"
echo ""

# Keep script running
wait
