#!/bin/bash
set -e

SCRIPT_DIR=$(cd $(dirname $0); pwd)

$SCRIPT_DIR/bin/php-class-diagram \
	--enable-class-properties \
	--enable-class-methods $SCRIPT_DIR/src \
	| plantuml -pipe -tpng > $SCRIPT_DIR/dogfood.png
$SCRIPT_DIR/bin/php-class-diagram \
	--package-diagram $SCRIPT_DIR/src \
	| plantuml -pipe -tpng > $SCRIPT_DIR/dogfood-package.png
