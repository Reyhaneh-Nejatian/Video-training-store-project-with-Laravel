<?php return array (
  'arghavan/common' => 
  array (
    'providers' => 
    array (
      0 => '\\arghavan\\Common\\Providers\\CommonServiceProvider',
    ),
  ),
  'arghavan/front' => 
  array (
    'providers' => 
    array (
      0 => '\\arghavan\\Front\\Providers\\FrontServiceProvider',
    ),
  ),
  'arghavan/payment' => 
  array (
    'providers' => 
    array (
      0 => '\\arghavan\\Payment\\Providers\\PaymentServiceProviders',
    ),
  ),
  'intervention/image' => 
  array (
    'providers' => 
    array (
      0 => 'Intervention\\Image\\ImageServiceProvider',
    ),
    'aliases' => 
    array (
      'Image' => 'Intervention\\Image\\Facades\\Image',
    ),
  ),
  'irazasyed/telegram-bot-sdk' => 
  array (
    'providers' => 
    array (
      0 => 'Telegram\\Bot\\Laravel\\TelegramServiceProvider',
    ),
    'aliases' => 
    array (
      'Telegram' => 'Telegram\\Bot\\Laravel\\Facades\\Telegram',
    ),
  ),
  'laravel-notification-channels/telegram' => 
  array (
    'providers' => 
    array (
      0 => 'NotificationChannels\\Telegram\\TelegramServiceProvider',
    ),
  ),
  'laravel/sail' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Sail\\SailServiceProvider',
    ),
  ),
  'laravel/sanctum' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Sanctum\\SanctumServiceProvider',
    ),
  ),
  'laravel/tinker' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Tinker\\TinkerServiceProvider',
    ),
  ),
  'laravel/ui' => 
  array (
    'providers' => 
    array (
      0 => 'Laravel\\Ui\\UiServiceProvider',
    ),
  ),
  'nesbot/carbon' => 
  array (
    'providers' => 
    array (
      0 => 'Carbon\\Laravel\\ServiceProvider',
    ),
  ),
  'nunomaduro/collision' => 
  array (
    'providers' => 
    array (
      0 => 'NunoMaduro\\Collision\\Adapters\\Laravel\\CollisionServiceProvider',
    ),
  ),
  'spatie/laravel-ignition' => 
  array (
    'providers' => 
    array (
      0 => 'Spatie\\LaravelIgnition\\IgnitionServiceProvider',
    ),
    'aliases' => 
    array (
      'Flare' => 'Spatie\\LaravelIgnition\\Facades\\Flare',
    ),
  ),
  'spatie/laravel-permission' => 
  array (
    'providers' => 
    array (
      0 => 'Spatie\\Permission\\PermissionServiceProvider',
    ),
  ),
);