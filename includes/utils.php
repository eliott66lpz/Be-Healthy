<?php

    function calculIngredientNutrition(array $ingredient): array {
        $qte = $ingredient['quantite_100g'];
        return [
            'calories' => $qte * $ingredient['calories_100g'] / 100,
            'proteines' => $qte * $ingredient['proteines_100g'] / 100,
            'glucides' => $qte * $ingredient['glucides_100g'] / 100,
            'lipides' => $qte * $ingredient['lipides_100g'] / 100
        ];
    }