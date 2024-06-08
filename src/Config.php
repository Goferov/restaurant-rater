<?php

namespace App;

class Config {
    private static $settings = [
        'db' => [
            'host' => 'db',
            'username' => 'docker',
            'password' => 'docker',
            'database' => 'restaurant-rater',
            'port' => '5432'
        ],
        'messages' => [
            'wrongPassword' => 'Hasło jest nieprawidłowe!',
            'userNotExist' => 'Taki użytkownik nie istnieje!',
            'userExist' => 'Taki użytkownik już istnieje!',
            'passwordChange' => 'Hasło zostało zmienione!',
            'invalidPassword' => 'Hasło musi posiadać liczbę i minimum 6 znaków!',
            'passwordsNotMatch' => 'Hasła nie są takie same!',
            'wrongEmail' => 'Email jest nieprawidłowy',
            'registerComplete' => 'Zostałeś zarejestrowany!',
            'mustLogin' => 'Musisz się zalogować, aby móc dodawać opinie!',
            'addedOpinion' => 'Twoja opinia została dodana. Dziękujemy!',
            'opinionScope' => 'Ocena powinna być w zakresie od 1 do 5',
            'reviewIsEmpty' => 'Opinia nie może być pusta i musi mieć mniej niż 255 znaków!',
            'reviewExists' => 'Juz dodałeś opinię do tej restauracji!',
            'restaurantAdded' => 'Restauracja została dodana!',
            'restaurantEdited' => 'Restauracja została zaktualizowana!',
            'requiredFields' => 'Wymagane pola nie mogą być puste.',
            'wrongUrl' => 'Podany adres strony internetowej jest nieprawidłowy.',
            'fileError' => 'Plik jest zbyt duży lub typ jest nieodpowiedni.',
            'invalidPostalCode' => 'Proszę wprowadzić prawidłowy kod pocztowy (format dd-ddd).',
            'invalidPhoneNumber' => 'Numer telefonu może zawierać tylko cyfry.',
        ]
    ];

    public static function get($key) {
        $path = explode('.', $key);
        $value = self::$settings;

        foreach ($path as $piece) {
            if (isset($value[$piece])) {
                $value = $value[$piece];
            } else {
                return null;
            }
        }

        return $value;
    }
}
