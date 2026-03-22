<?php

declare(strict_types=1);

namespace App\Core;

class Validator
{
    private array $errors = [];

    public function __construct(private array $data) {}

    private function setError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getValue(string $field): ?string
    {
        return $this->data[$field] ?? null;
    }

    public function required(string $field): static
    {
        if (!$this->getValue($field)) {
            $this->setError($field, 'Поле не должно быть пустым.');
        }
        return $this;
    }

    public function length(string $field, int $min = 2, int $max = 50): static
    {
        if (mb_strlen($this->getValue($field)) < $min) {
            $this->setError($field, "Минимальный размер {$field} - {$min}.");
        }
        if (mb_strlen($this->getValue($field)) > $max) {
            $this->setError($field, "Максимальный размер {$field} - {$max}.");
        }
        return $this;
    }

    public function email(string $field): static
    {
        if (!filter_var($this->getValue($field), FILTER_VALIDATE_EMAIL)) {
            $this->setError($field, "Введите корректный email.");
        }
        return $this;
    }

    public function confirmPass(): static
    {
        if ($this->getValue('password') !== $this->getValue('password_confirm')) {
            $this->setError('password_confirm', 'Пароли не совпадают.');
        }
        return $this;
    }

    public function isValid(): bool
    {
        return empty($this->errors);
    }

    public function url(string $field)
    {
        if (empty($this->getValue($field))) {
            $this->setError($field, 'Введено пустое поле. Введите ссылку на товар.');
        }

        if (!filter_var($this->getValue($field), FILTER_VALIDATE_URL)) {
            $this->setError($field, 'Не валидная ссылка. Убедитесь, что ссылка валидна.');
        }
        return $this;
    }
}
