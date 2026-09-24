<?php

/*
 * Najczęściej używane reguły. Brakujące klucze Laravel bierze z języka zapasowego (en).
 */
return [

    'accepted' => 'Pole :attribute musi zostać zaakceptowane.',
    'array' => 'Pole :attribute musi być tablicą.',
    'boolean' => 'Pole :attribute musi mieć wartość tak lub nie.',
    'confirmed' => 'Potwierdzenie pola :attribute nie zgadza się.',
    'current_password' => 'Podane hasło jest nieprawidłowe.',
    'date' => 'Pole :attribute musi być poprawną datą.',
    'date_format' => 'Pole :attribute musi mieć format :format.',
    'digits' => 'Pole :attribute musi składać się z :digits cyfr.',
    'distinct' => 'Pole :attribute zawiera zduplikowaną wartość.',
    'email' => 'Pole :attribute musi być poprawnym adresem e-mail.',
    'exists' => 'Wybrana wartość pola :attribute jest nieprawidłowa.',
    'file' => 'Pole :attribute musi być plikiem.',
    'filled' => 'Pole :attribute nie może być puste.',
    'gt' => [
        'numeric' => 'Pole :attribute musi być większe niż :value.',
        'string' => 'Pole :attribute musi mieć więcej niż :value znaków.',
        'array' => 'Pole :attribute musi mieć więcej niż :value elementów.',
        'file' => 'Plik :attribute musi ważyć więcej niż :value kB.',
    ],
    'gte' => [
        'numeric' => 'Pole :attribute musi być większe lub równe :value.',
        'string' => 'Pole :attribute musi mieć co najmniej :value znaków.',
        'array' => 'Pole :attribute musi mieć co najmniej :value elementów.',
        'file' => 'Plik :attribute musi ważyć co najmniej :value kB.',
    ],
    'image' => 'Plik :attribute musi być obrazem.',
    'in' => 'Wybrana wartość pola :attribute jest nieprawidłowa.',
    'integer' => 'Pole :attribute musi być liczbą całkowitą.',
    'json' => 'Pole :attribute musi być poprawnym ciągiem JSON.',
    'lt' => [
        'numeric' => 'Pole :attribute musi być mniejsze niż :value.',
        'string' => 'Pole :attribute musi mieć mniej niż :value znaków.',
        'array' => 'Pole :attribute musi mieć mniej niż :value elementów.',
        'file' => 'Plik :attribute musi ważyć mniej niż :value kB.',
    ],
    'lte' => [
        'numeric' => 'Pole :attribute musi być mniejsze lub równe :value.',
        'string' => 'Pole :attribute musi mieć co najwyżej :value znaków.',
        'array' => 'Pole :attribute musi mieć co najwyżej :value elementów.',
        'file' => 'Plik :attribute musi ważyć co najwyżej :value kB.',
    ],
    'max' => [
        'numeric' => 'Pole :attribute nie może być większe niż :max.',
        'string' => 'Pole :attribute nie może mieć więcej niż :max znaków.',
        'array' => 'Pole :attribute nie może mieć więcej niż :max elementów.',
        'file' => 'Plik :attribute nie może ważyć więcej niż :max kB.',
    ],
    'mimes' => 'Plik :attribute musi mieć typ: :values.',
    'mimetypes' => 'Plik :attribute musi mieć typ: :values.',
    'min' => [
        'numeric' => 'Pole :attribute musi wynosić co najmniej :min.',
        'string' => 'Pole :attribute musi mieć co najmniej :min znaków.',
        'array' => 'Pole :attribute musi mieć co najmniej :min elementów.',
        'file' => 'Plik :attribute musi ważyć co najmniej :min kB.',
    ],
    'not_in' => 'Wybrana wartość pola :attribute jest nieprawidłowa.',
    'numeric' => 'Pole :attribute musi być liczbą.',
    'password' => [
        'letters' => 'Pole :attribute musi zawierać co najmniej jedną literę.',
        'mixed' => 'Pole :attribute musi zawierać co najmniej jedną wielką i jedną małą literę.',
        'numbers' => 'Pole :attribute musi zawierać co najmniej jedną cyfrę.',
        'symbols' => 'Pole :attribute musi zawierać co najmniej jeden symbol.',
        'uncompromised' => 'Podana wartość pola :attribute wyciekła w publicznym wycieku danych. Wybierz inną.',
    ],
    'present' => 'Pole :attribute musi być obecne.',
    'regex' => 'Format pola :attribute jest nieprawidłowy.',
    'required' => 'Pole :attribute jest wymagane.',
    'required_if' => 'Pole :attribute jest wymagane, gdy :other ma wartość :value.',
    'required_with' => 'Pole :attribute jest wymagane, gdy podano :values.',
    'required_with_all' => 'Pole :attribute jest wymagane, gdy podano :values.',
    'required_without' => 'Pole :attribute jest wymagane, gdy nie podano :values.',
    'required_without_all' => 'Pole :attribute jest wymagane, gdy nie podano żadnej z wartości :values.',
    'same' => 'Pola :attribute i :other muszą być takie same.',
    'size' => [
        'numeric' => 'Pole :attribute musi wynosić :size.',
        'string' => 'Pole :attribute musi mieć :size znaków.',
        'array' => 'Pole :attribute musi zawierać :size elementów.',
        'file' => 'Plik :attribute musi ważyć :size kB.',
    ],
    'string' => 'Pole :attribute musi być tekstem.',
    'unique' => 'Taka wartość pola :attribute jest już zajęta.',
    'uploaded' => 'Nie udało się wgrać pliku :attribute.',
    'url' => 'Pole :attribute musi być poprawnym adresem URL.',

    'custom' => [],

    'attributes' => [
        'name' => 'imię',
        'email' => 'e-mail',
        'password' => 'hasło',
        'password_confirmation' => 'powtórzenie hasła',
    ],

];
