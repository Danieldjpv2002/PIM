<?php

namespace App;

class ENV
{
    const APP_CORRELATIVE = 'ticket';
    const APP_NAME = 'PIM';
    const APP_LONGNAME = 'PIM by Pacheco';
    const APP_URL = 'https://pim.sode.me';
    const APP_DOMAIN = 'sode.me';

    const DB_CONNECTION = 'mysql';
    const DB_PORT = '3306';
    const DB_SOCKET = '';
    const DB_DATABASE = 'u163873840_ticket_db';

    # LOCAL
    // const DB_HOST = 'localhost';
    // const DB_USERNAME = 'root';
    // const DB_PASSWORD = '';

    # PRODUCCION
    const DB_HOST = '154.56.47.1';
    const DB_USERNAME = 'u163873840_ticket_user';
    const DB_PASSWORD = 'HLy9^OhLt{-73v3wogghIl£S-n!nu\0_FC}T';

    const MAIL_MAILER = 'smtp';
    const MAIL_HOST = 'gmail';
    const MAIL_PORT = '587';
    const MAIL_USERNAME = 'sodeworld@gmail.com';
    const MAIL_PASSWORD = 'baazaamceetbkqhu';
    const MAIL_ENCRYPTION = 'null';
    const MAIL_FROM_ADDRESS = 'sodeworld@gmail.com';
    const MAIL_FROM_NAME = 'SoDe World';
}
