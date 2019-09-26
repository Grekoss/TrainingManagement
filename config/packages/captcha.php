<?php

if (!class_exists('CaptchaConfiguration')) {

    return;
}

// BotDetect PHP Captcha configuration options

return [
    // Captcha configuration for forgot password page
    'ForgotCaptcha' => [
        'UserInputID' => 'captchaCode',
        'ImageWidth' => 250,
        'ImageHeight' => 50,
    ],
    // Captcha configuration for login page
    'LoginCaptcha' => [
        'UserInputID' => 'captchaCode',
        'CodeLength' => CaptchaRandomization::GetRandomCodeLength(4, 6),
        'ImageStyle' => [
            ImageStyle::Radar,
            ImageStyle::Collage,
            ImageStyle::Fingerprints,
        ],
    ],
];
