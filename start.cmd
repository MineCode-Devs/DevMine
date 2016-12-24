@echo off
TITLE Devmine- The Awesome software that does NOT use the Pocketmine folder structure!
cd /d %~dp0

if exist bin\php\php.exe (
	set PHPRC=""
	set PHP_BINARY=bin\php\php.exe
) else (
	set PHP_BINARY=php
)

if exist src\devmine\server\DevMine.php (
		set DEVMINE_FILE=src\devmine\server\DevMine.php
	) else (
		echo "error> There was an error in starting DevMine. Check that there is either a file named DevMine.phar or an src folder, or try reinstalling DevMine."
		pause
		exit 7
	)
)
if exist bin\mintty.exe (
	start "" bin\mintty.exe -o Columns=88 -o Rows=32 -o AllowBlinking=0 -o FontQuality=3 -o Font="DejaVu Sans Mono" -o FontHeight=10 -o CursorType=0 -o CursorBlinks=1 -h error -t "DevMine" -i bin/DevMine.ico -w max %PHP_BINARY% %DevMine_FILE% --enable-ansi %*
) else (
	%PHP_BINARY% -c bin\php %DevMine_FILE% %*
)
pause
