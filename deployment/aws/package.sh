#!/usr/bin/env bash

tar --exclude='./node_modules' --exclude='./vendor' --exclude='.git' -zcvf codebase.tar.gz ./
