# WhatsApp notification channel for Laravel

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

This package makes it easy to send [WhatsApp notifications](https://developers.facebook.com/docs/whatsapp) through [YCloud](https://ycloud.com) platform with Laravel 5.5+, 6.x, 7.x, 8.x & 9.x

## Contents

- [Installation](#installation)
  - [WhatsApp Provider](#whatsapp-provider)
  - [Configuration](#configuration)
  - [Advanced Configuration](#advanced-configuration)
- [Usage](#usage)
	- [Available Message Methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

Make sure to add this repository to your `composer.json`:

``` json
"repositories" : [
    {
        "url": "https://github.com/clickstudioltd/laravel-whatsapp-notification-channel.git",
        "type": "git"
    }
]
```

Then, you can install the package via composer:

``` bash
composer require clickstudio/laravel-whatsapp-notification-channel
```

### WhatsApp Provider

Meta provides two methods to access its API:

- Through its Business Platform and Cloud API.
- Through third-party solution providers that are official partners with Meta.

First method requires more setup and development which might not be preferable for everyone. Therefore, second method is more feasible and common for developers to gain quick access to the WhatsApp API.

This package uses [YCloud](https://www.ycloud.com) platform which is an official Meta partner to facilitate access to WhatsApp API. YCloud provides an easy to use API alongside a shared inbox, campaign manager and many more features to benefit from your account.

In order to use this package, you need to create an account on YCloud and connect your WhatsApp Business Account to your YCloud account which is explained in detail inside your client dashboard. Afterwards, generate an [API Key](https://docs.ycloud.com/reference/authentication) from your client dashboard and use it for your configuration.

### Configuration

Add your YCloud API key, and From number (optional) to your `.env`:

```dotenv
WHATSAPP_API_KEY=ABCD
WHATSAPP_FROM=100000000 # Optional if using `from` method on the message objects
```

### Advanced Configuration

Run `php artisan vendor:publish --provider="NotificationChannels\WhatsApp\WhatsAppProvider"`
```
/config/whatsapp-notification-channel.php
```

#### Suppressing specific errors or all errors

Publish the config using the above command, and edit the `ignored_error_codes` array. You can get the list of
exception codes from [the documentation](https://docs.ycloud.com/reference/errors).

If you want to suppress all errors, you can set the option to `['*']`. The errors will not be logged but notification
failed events will still be emitted.

## Usage

Now you can use the channel in your `via()` method inside the notification:

``` php
use NotificationChannels\WhatsApp\WhatsAppChannel;
use NotificationChannels\WhatsApp\WhatsAppTemplateMessage;
use Illuminate\Notifications\Notification;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [WhatsAppChannel::class];
    }

    public function toWhatsApp($notifiable)
    {
        return (new WhatsAppTemplateMessage())
            ->from('+16001234567') // Can also set WHATSAPP_FROM in .env to exclude this line
            ->template([
                'name' => 'sample_template'
            ]);
    }
}
```

In order to let your `Notification` know which phone you are sending to, the channel will look for the `phone_number` attribute of the `Notifiable` model. If you want to override this behavior, add the `routeNotificationForWhatsApp` method to your `Notifiable` model.

```php
public function routeNotificationForWhatsApp()
{
    return '+1234567890';
}
```

Or simply override the existing To number by calling the `to` method on a `WhatsAppMessage` instance.

```php
public function toWhatsApp($notifiable)
{
    return (new WhatsAppTemplateMessage())
        ->template([
            'name' => 'sample_template'
        ])
        ->to('+1234567890');
}
```

### Available Message Methods

#### WhatsAppTemplateMessage

- `from('')`: Accepts a phone to use as the notification sender.
- `to('')`: Accepts a phone to use as the notification receiver.
- `template('')`: Accepts an array value for the notification message template.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email mahangm@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Mahan Heshmati Moghaddam](https://github.com/mahangm)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
