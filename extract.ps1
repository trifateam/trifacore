$content = Get-Content 'd:\TRPL Muhammad Ridho Syaputra\SEMESTER 4\PBL\trifacore\resources\views\layouts\sidebar.blade.php' -Encoding UTF8
$keep = $content[0..322]
$keep | Set-Content 'd:\TRPL Muhammad Ridho Syaputra\SEMESTER 4\PBL\trifacore\resources\views\layouts\sidebar.blade.php' -Encoding UTF8
