#!/bin/sh
webdir=/data/auction/auction_backend
cd $webdir 
git pull
chmod +x sh/*
chown -R www:www $webdir 
