<?php require_once __DIR__ . '/../config/init.php';

    $allowedTypes = ['petit_dejeuner', 'dejeuner', 'diner'];
    $type = $_GET['type'] ?? '';
    if ( !in_array($type, $allowedTypes, true) ) {
        header("Location: calorie.php");
        exit;
    }

    $mealsFromType = getMealsFromType($type);

    $meals = [];
    foreach($mealsFromType as $row) {
        $id = $row['id_repas'];
        $meals[$id]['nom'] = $row['nomRepas'];

        $nutrition = calculIngredientNutrition($row);
        $meals[$id]['total']['calories'] += $nutrition['calories'];
        $meals[$id]['total']['proteines'] += $nutrition['proteines'];
        $meals[$id]['total']['glucides'] += $nutrition['glucides'];
        $meals[$id]['total']['lipides'] += $nutrition['lipides'];


        $meals[$id]['ingredients'][] =  [
            'nomIngredient' => $row['nomIngredient'],
            'quantite_g' => $row['quantite_g']
        ];
    }

    ?>

<main id="mealDialog" class="meal-dialog">
    <header class="dialogHeader">
        <h2 class="dialogTitle">Choisir mon repas</h2>
        <button type="button" id="closeMealDialog" class="btnCloseDialog">X</button>
    </header>

    <input type="hidden" id="type_repas" name="type_repas">

    <button type="button" id="btnFavoriteMeals" class="dialogSidebar">
        <img src="/Be-Healthy/style/icons/calorie/favorite.svg" alt="Logo repas favoris" class="dialogSidebarIcon">
        <span>Mes repas</span>
    </button>

    <label class="dialogSidebar dialogSidebar--search" for="searchMeal">
        <img src="/Be-Healthy/style/icons/calorie/search.svg" alt="Loupe de recherche" class="dialogSidebarIcon">
        <input type="search" id="searchMeal" placeholder="Rechercher un repas"/>
    </label>

    <!-- Srollable menu -->
    <section class="mealsMenu">
        <h3 class="mealsMenuTitle">Choisir mon repas</h3>

        <ul class="mealsScrollMenu">
            <!-- for each -->
            <?php // foreach ($meals as $meal): ?>
            <li class="mealItem">
                <span class="mealName">Porridge flocons d'avoine, banane et miel</span>
                <a href="/Be-Healthy/meal/" class="btnSelectMeal" data-meal-id="1">+</a>
            </li>
            <?php // endforeach; ?>
        </ul>
    </section>
</main>


