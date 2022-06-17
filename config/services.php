<?php

return [

  /*
  |--------------------------------------------------------------------------
  | Third Party Services
  |--------------------------------------------------------------------------
  |
  | This file is for storing the credentials for third party services such
  | as Mailgun, Postmark, AWS and more. This file provides the de facto
  | location for this type of information, allowing packages to have
  | a conventional file to locate the various service credentials.
  |
  */

  'mailgun' => [
    'domain' => env('MAILGUN_DOMAIN'),
    'secret' => env('MAILGUN_SECRET'),
    'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
  ],

  'postmark' => [
    'token' => env('POSTMARK_TOKEN'),
  ],

  'ses' => [
    'key' => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
  ],
  'adveduplat' => [
    'domain' => env('ADVEDUPLAT_URL'),
    'api_token' => env('ADVEDUPLAT_ADMIN_API_TOKEN')
  ],
  'amocrm' => [
    'advance' => [
      'subdomain' => env('AMOCRM_SUBDOMAIN'),
      'client_id' => env('AMOCRM_CLIENT_ID'),
      'client_secret' => env('AMOCRM_CLIENT_SECRET'),
      'auth_code' => env('AMOCRM_AUTH_CODE'),
      'redirect_uri' => env('AMOCRM_REDIRECT_URI'),
      'custom_fields' => [
        'contacts' => [
          'position' => (int)env('AMOCRM_CUSTOM_FIELDS_CONTACTS_POSITION'), // Должность
          'phones' => (int)env('AMOCRM_CUSTOM_FIELDS_CONTACTS_PHONES'), // Телефон
          'emails' => (int)env('AMOCRM_CUSTOM_FIELDS_CONTACTS_EMAILS'),
          'country' => (int)env('AMOCRM_CUSTOM_FIELDS_CONTACTS_COUNTRY'), //Страна
          'city' => (int)env('AMOCRM_CUSTOM_FIELDS_CONTACTS_CITY'), //Город
          'partner' => (int)env('AMOCRM_CUSTOM_FIELDS_CONTACTS_PARTNER'),
        ],
        'leads' => [
          'pay_date' => (int)env('AMOCRM_CUSTOM_FIELDS_LEADS_PAY_DATE'), // Дата оплаты
          'order_id' => (int)env('AMOCRM_CUSTOM_FIELDS_LEADS_GETCOURSE_ORDER_ID'), //id заказа
          'order_num' => (int)env('AMOCRM_CUSTOM_FIELDS_LEADS_GETCOURSE_ORDER_NUM'), //номер заказа
          'partner' => (int)env('AMOCRM_CUSTOM_FIELDS_LEADS_PARTNER'), //партнер
          'city' => (int)env('AMOCRM_CUSTOM_FIELDS_LEADS_CITY'), //Город
          'integrator' => (int)env('AMOCRM_CUSTOM_FIELDS_LEADS_INTEGRATOR'),
        ]
      ]
    ],
  ],
  'getcourse' => [
    'advance' => [
      'hostname' => env('GETCOURSE_HOSTNAME'),
      'secret_key' => env('GETCOURSE_SECRET_KEY'),
      'users_export_id' => env('GET_COURSE_USERS_EXPORT_ID'),
      'deals_export_id' => env('GET_COURSE_DEALS_EXPORT_ID'),
      'deals_export_cache' => env('GET_COURSE_DEALS_EXPORT_CACHE', false),
    ],
  ],
];
