#!/usr/bin/env sh
rm HelloWorld-*.zip
zip -r HelloWorld-1.0.1.zip HelloWorld -x "*.DS_Store" -x ".git*" -x ".idea*" -x "*.gitkeep"
