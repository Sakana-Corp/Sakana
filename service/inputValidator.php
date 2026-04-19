<?php 
class InputValidator {
    private $errors = [];

    public function notEmpty(string $fieldName, string $value, int $minLength = 1): self {
        $trimmed = trim($value);

        if (strlen($trimmed) < $minLength) {
            $this->errors[$fieldName] = "Campo obrigatório";
        }
        return $this;
    }

    public function email(string $fieldName, string $value): self {
        $trimmed = trim($value);
        if (!filter_var($trimmed, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$fieldName] = "Email inválido.";
        }
        return $this;
    }

    public function minLength(string $fieldName, string $value, int $minLength): self {
        if (strlen($value) < $minLength) {
            $this->errors[$fieldName] = "Mínimo {$minLength} caracteres.";
        }
        return $this;
    }

    public function match(string $fieldName, string $value1, string $value2, string $label = "Valores"): self {
        if ($value1 !== $value2) {
            $this->errors[$fieldName] = "{$label} não conferem.";
        }
        return $this;
    }

    public function isValid(): bool {
        return empty($this->errors);
    }

    public function getErrors(): array {
        return $this->errors;
    }

    public function getFirstError(): ?string {
        return empty($this->errors) ? null : reset($this->errors);
    }

    public function clear(): self {
        $this->errors = [];
        return $this;
    }
}
?>