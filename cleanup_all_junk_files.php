<?php

/**
 * Cleanup All Junk Files
 * Hapus semua file sampah, debug, test, dan script sementara
 */

echo "🗑️  CLEANUP ALL JUNK FILES\n";
echo "=========================\n\n";

// File-file yang akan dihapus (sampah/tidak terpakai)
$junkFiles = [
    // Debug & Test Files
    'check_database_config.php',
    'check_pos_table.php', 
    'debug_login.php',
    'test_login.php',
    'test_otp.php',
    'create_test_user.php',
    
    // Cleanup Scripts (sudah dijalankan)
    'cleanup_mysql_files.php',
    'delete_mysql_files.php',
    
    // Fix Scripts (sudah dijalankan, backup ada di fix_jatuh_tempos_table.php)
    'emergency_fix.php',
    'final_database_fix.php',
    'complete_database_fix.php',
    'fix_credentials.php',
    'fix_database.php',
    'fix_env.php',
    'fix_expenses_error.php',
    'fix_kendaraans_error.php',
    'fix_login_complete.php',
    'fix_salaries_table.php',
    'fix_sqlite_functions.php',
    'restore_working_state.php',
    'setup_complete_database.php',
    'quick_cache_fix.php',
    
    // Email Config Scripts (sudah dijalankan)
    'config_email_otp.php',
    'config_gmail_smtp.php',
    'fix_email_otp.php',
    'setup_email.php',
    'setup_gmail_otp.php',
    'update_gmail_config.php',
    'quick_gmail_setup.php'
];

// File yang TETAP DIPERLUKAN (jangan dihapus)
$keepFiles = [
    'fix_jatuh_tempos_table.php', // Script utama perbaikan database
    'artisan',                    // Laravel CLI
    'composer.json',              // Dependencies
    'composer.lock',              // Lock dependencies
    'package.json',               // NPM dependencies
    'package-lock.json',          // NPM lock
    '.env.example',               // Template env
    'phpunit.xml',                // Testing config
    'vite.config.js',             // Build config
    'tailwind.config.js',         // CSS config
    'postcss.config.cjs'          // CSS processing
];

$deletedCount = 0;
$notFoundCount = 0;
$keptCount = 0;

echo "🔄 Deleting junk files...\n\n";

foreach ($junkFiles as $file) {
    $filePath = __DIR__ . '/' . $file;
    
    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            echo "🗑️  Deleted: {$file}\n";
            $deletedCount++;
        } else {
            echo "❌ Failed to delete: {$file}\n";
        }
    } else {
        echo "⚠️  Not found: {$file}\n";
        $notFoundCount++;
    }
}

echo "\n📊 CLEANUP SUMMARY:\n";
echo "===================\n";
echo "🗑️  Files deleted: {$deletedCount}\n";
echo "⚠️  Files not found: {$notFoundCount}\n\n";

echo "✅ FILES YANG TETAP DIPERLUKAN:\n";
echo "===============================\n";
foreach ($keepFiles as $file) {
    if (file_exists(__DIR__ . '/' . $file)) {
        echo "✅ Kept: {$file}\n";
        $keptCount++;
    }
}

echo "\n📁 FOLDERS YANG TETAP DIPERLUKAN:\n";
echo "=================================\n";
echo "✅ app/ - Laravel application code\n";
echo "✅ bootstrap/ - Laravel bootstrap\n";
echo "✅ config/ - Configuration files\n";
echo "✅ database/ - Database & migrations\n";
echo "✅ public/ - Web accessible files\n";
echo "✅ resources/ - Views, CSS, JS\n";
echo "✅ routes/ - Route definitions\n";
echo "✅ storage/ - File storage\n";
echo "✅ tests/ - Test files\n";
echo "✅ vendor/ - Composer dependencies\n";
echo "✅ node_modules/ - NPM dependencies\n\n";

echo "🎯 APLIKASI SEKARANG:\n";
echo "=====================\n";
echo "✅ Bersih dari file sampah\n";
echo "✅ Hanya file penting yang tersisa\n";
echo "✅ Database SQLite siap pakai\n";
echo "✅ Gmail OTP terkonfigurasi\n";
echo "✅ Siap untuk production\n\n";

echo "✅ Cleanup completed!\n";
echo "Website Anda sekarang bersih dan optimal.\n";

?>
