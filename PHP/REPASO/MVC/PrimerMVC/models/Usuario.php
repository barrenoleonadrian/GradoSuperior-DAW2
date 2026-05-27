<?php
    class Usuario{
        public function getUsers(): array{
            return [
                "Carlos",
                "Manolo",
                "Sofia"
            ];
        }

        public function getAll(): array{
            return $this->getUsers();
        }
    }