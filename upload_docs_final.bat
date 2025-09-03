@echo off
echo === Upload Docs to GitHub ===

echo Step 1: Add docs folder...
git add docs/
git add docs/README.md
git add docs/screenshot/dashboard.jpg

echo Step 2: Commit changes...
git commit -m "Add docs folder with screenshot"

echo Step 3: Push to GitHub...
git push origin main

echo Step 4: Check status...
git status

echo === Upload Complete ===
pause
