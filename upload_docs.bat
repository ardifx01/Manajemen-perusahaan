@echo off
echo Mengonfigurasi Git...
git config --global user.name "DillanINF"
git config --global user.email "dilaninf6@gmail.com"

echo Inisialisasi Git repository...
git init

echo Menambahkan folder docs...
git add docs/

echo Commit perubahan...
git commit -m "Initial commit: Upload docs folder"

echo Menambahkan remote repository...
git remote add origin https://github.com/DillanINF/Manajemen-perusahaan.git

echo Pushing ke GitHub...
git branch -M main
git push -u origin main

echo Selesai! Folder docs telah diunggah ke GitHub.
pause
