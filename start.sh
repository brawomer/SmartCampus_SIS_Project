#!/bin/bash
# One-click setup and run script

echo "╔════════════════════════════════════════╗"
echo "║   SmartCampus - Quick Start Script    ║"
echo "╚════════════════════════════════════════╝"
echo ""

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "❌ PHP is not installed. Please install PHP first."
    exit 1
fi

echo "✅ PHP is installed"

# Check if server is already running
if lsof -Pi :8000 -sTCP:LISTEN -t >/dev/null 2>&1; then
    echo "⚠️  Server already running on port 8000"
    echo ""
    echo "Open in Brave: http://localhost:8000/setup_complete.php"
    echo ""
    echo "Press Ctrl+C to stop the existing server, then run this script again."
    exit 0
fi

# Start the server
echo "🚀 Starting PHP development server..."
echo ""
echo "╔════════════════════════════════════════╗"
echo "║  IMPORTANT: Run Setup First!           ║"
echo "║                                        ║"
echo "║  Open in Brave:                        ║"
echo "║  http://localhost:8000/setup_complete.php ║"
echo "║                                        ║"
echo "║  This will automatically create the    ║"
echo "║  database and all users.               ║"
echo "╚════════════════════════════════════════╝"
echo ""
echo "Press Ctrl+C to stop the server"
echo ""

cd "$(dirname "$0")"
php -S localhost:8000 -t webb
