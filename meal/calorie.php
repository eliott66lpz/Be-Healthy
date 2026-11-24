<?php require_once __DIR__ . '/../config/init.php'; ?>
<?php
    //requireLogin();

?>

<?php require_once __DIR__ . '/../includes/header.php'; ?>
    <main>
        <h2>Calories de ce jour</h2>

        <div class="kcal-progression">
            <!-- Kcal progression logo -->
            <img src="/Be-Healthy/style/icons/calorie/bar-kcal.svg" alt="Barre des calories">

            <div class="text-kcal">
                <span class="calories">2200</span>
                <span class="unit">Kcal</span>
            </div>
        </div>

        <div class="meal">
            <div>
                <fieldset>
                    <!-- breakfast logo -->
                    <img src="/Be-Healthy/style/icons/calorie/breakfast.svg" alt="Logo petit déjeuner">
                    <label>Petit-Déjeuner</label>
                    <a href="/Be-Healthy/meal/choose-meal.php?type=petit_dejeuner" class="btnAddMeal">+</a>
                </fieldset>
            </div>

            <div>
                <fieldset>
                    <!-- lunch logo -->
                    <img src="/Be-Healthy/style/icons/calorie/lunch.svg" alt="Logo déjeuner">
                    <label>Déjeuner</label>
                    <a href="/Be-Healthy/meal/choose-meal.php?type=dejeuner" class="btnAddMeal">+</a>
                </fieldset>
            </div>

            <div>
                <fieldset>
                    <!-- dinner logo -->
                    <img src="/Be-Healthy/style/icons/calorie/dinner.svg" alt="Logo dîner">
                    <label>Dîner</label>
                    <a href="/Be-Healthy/meal/choose-meal.php?type=diner" class="btnAddMeal">+</a>
                </fieldset>
            </div>
        </div>

    </main>

<?php require_once __DIR__ . '/../includes/footer.php';?>