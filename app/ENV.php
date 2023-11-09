<?php

namespace App;

class ENV
{
    const APP_CORRELATIVE = 'massive';
    const APP_NAME = 'Massive';
    const APP_LONGNAME = 'Massive de SoDe';
    const APP_URL = 'https://massive.sode.me';
    const APP_DOMAIN = 'sode.localhost';

    const DB_CONNECTION = 'mysql';
    const DB_PORT = '3306';
    const DB_SOCKET = '';
    const DB_DATABASE = 'u163873840_massive_db';
    const DB_DATABASE_SODE = 'u163873840_sode_dev';

    # LOCAL
    // const DB_HOST = 'localhost';
    // const DB_USERNAME = 'root';
    // const DB_PASSWORD = '';
    // const DB_USERNAME_SODE = 'root';
    // const DB_PASSWORD_SODE = '';

    # PRODUCCION
    const DB_HOST = '154.56.47.1';
    const DB_USERNAME = 'u163873840_massive_user';
    const DB_PASSWORD = 'u6uS=DR6)#21H]xdq!e-J##H8ww5#[%<SrvZ';
    const DB_USERNAME_SODE = 'u163873840_sode_user';
    const DB_PASSWORD_SODE = 'VQ&KiiL5q67dgLnNK6Ap0d3ypN@8498g@add';

    const MAIL_MAILER = 'smtp';
    const MAIL_HOST = 'gmail';
    const MAIL_PORT = '587';
    const MAIL_USERNAME = 'sodeworld@gmail.com';
    const MAIL_PASSWORD = 'baazaamceetbkqhu';
    const MAIL_ENCRYPTION = 'null';
    const MAIL_FROM_ADDRESS = 'sodeworld@gmail.com';
    const MAIL_FROM_NAME = 'SoDe World';
}
