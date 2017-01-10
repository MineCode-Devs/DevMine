# Variable define
DIR="$(cd -P "$( dirname "${BASH_SOURCE[0]}" )" && pwd)"

# Change Directory
cd "$DIR"

# Loop starting
# Don't edit this if you don't know what this does!

DO_LOOP="no"

##########################################
# DO NOT EDIT ANYTHING BEYOND THIS LINE! #
##########################################

while getopts "p:f:l" OPTION 2> /dev/null; do
	case ${OPTION} in
		p)
			PHP_BINARY="$OPTARG"
			;;
		f)
			DevMine_FILE="$OPTARG"
			;;
		l)
			DO_LOOP="yes"
			;;
		\?)
			break
			;;
	esac
done

if [ "$PHP_BINARY" == "" ]; then
	if [ -f ./bin/php7/bin/php ]; then
		export PHPRC=""
		PHP_BINARY="./bin/php7/bin/php"
	elif type php 2>/dev/null; then
		PHP_BINARY=$(type -p php)
	else
		echo "[ERROR] Couldn't find a working PHP binary, please use the installer."
		exit 1
	fi
fi

if [ "$Devmine_FILE" == "" ]; then
	if [ -f ./DevMine-devmine.phar ]; then
		DevMine_FILE="./DevMine-devmine.phar"
	elif [ -f ./Devmine*.phar ]; then
	    	DevMine_FILE="./DevMine*.phar"
	elif [ -f ./DevMine-MP.phar ]; then
		DevMine_FILE="./DevMine-MP.phar"
	elif [ -f ./src/devmine/server/DevMine.php ]; then
		Devmine_FILE="./src/devmine/server/DevMine.php"
	else
		echo "[ERROR] Couldn't find a valid Devmine installation."
		exit 1
	fi
fi

LOOPS=0

set +e
while [ "$LOOPS" -eq 0 ] || [ "$DO_LOOP" == "yes" ]; do
	if [ "$DO_LOOP" == "yes" ]; then
		"$PHP_BINARY" $DevMine_FILE $@
	else
		exec "$PHP_BINARY" $DevMine_FILE $@
	fi
	((LOOPS++))
done

if [ ${LOOPS} -gt 1 ]; then
	echo "[INFO] Restarted $LOOPS times"
fi
