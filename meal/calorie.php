<?php require_once __DIR__ . '/../includes/header.php'; ?>

    <main inert>
        <h2>Calories de ce jour</h2>

        <div class="kcal-progression">
            <!-- Kcal progression logo -->
            <img src="/Be-Healthy/style/icons/calorie/bar-kcal.svg">

            <div class="text-kcal">
                <span class="calories">2200</span>
                <span class="unit">Kcal</span>
            </div>
        </div>

        <div>
            <form method="POST" class="meal" action="../scripts/meal/add-meal.js">
                <div class="breakfast">
                    <fieldset>
                        <!-- breakfast logo -->
                        <img src="/Be-Healthy/style/icons/calorie/breakfast.svg">

                        <label>Petit-Déjeuner</label>
                        <button type="submit" name="type-repas" value="petit_dejeuner">+</button>
                    </fieldset>
                </div>

                <div class="lunch">
                    <fieldset>
                        <!-- lunch logo -->
                        <img src="/Be-Healthy/style/icons/calorie/lunch.svg">

                        <label>Déjeuner</label>
                        <button onclick="dejeuner.showModal()">+</button>

                        <dialog id="dejeuner">
                            <h2>Hello !</h2>
                            <p>Ceci est une popup native.</p>
                            <button onclick="dejeuner.close()">Fermer</button>
                        </dialog>
                    </fieldset>
                </div>

                <div class="dinner">
                    <fieldset>
                        <!-- dinner logo -->
                        <img src="/Be-Healthy/style/icons/calorie/dinner.svg">

                        <label>Dîner</label>
                        <button type="submit" name="type-repas" value="diner">+</button>
                    </fieldset>
                </div>
            </form>
        </div>

    </main>
    <script src="/Be-Healthy/scripts/meal/add-meal.js"></script>
<?php require_once __DIR__ . '/../includes/footer.php';