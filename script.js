// 1. On récupère les éléments HTML dont on a besoin grâce à leur id
const muscleSelect = document.getElementById("muscleSelect");
const seanceSelect = document.getElementById("seanceSelect");
const exerciceSelect = document.getElementById("exerciceSelect");
const searchInput = document.getElementById("searchInput");
const selectedMuscleName = document.getElementById("selectedMuscleName");
const btnVoirSeances = document.getElementById("btnVoirSeances");

// Éléments de la pop-up
const popupModal = document.getElementById("popupModal");
const closeModalBtn = document.getElementById("closeModalBtn");
const modalTitle = document.getElementById("modalTitle");
const modalBody = document.getElementById("modalBody");
const btnFavori = document.getElementById("btnFavori");

// 2. Données des séances et exercices (dictionnaire clé -> contenu)
const donneesSeances = {
    pectoraux: "Séance Pecs : 4x10 Développé Couché, 3x15 Pompes, 3x12 Écarté haltères.",
    epaules: "Séance Épaules : 4x12 Élévations Latérales, 4x10 Développé militaire.",
    biceps: "Séance Biceps : 4x12 Curl Biceps, 3x10 Curl marteau.",
    triceps: "Séance Triceps : 4x10 Dips, 3x12 Extension poulie.",
    abdomen: "Séance Core & Abs : 4x20 Crunch Abdominal, 3x45s Gainage planche.",
    dorsaux: "Séance Dos : 4x8 Tractions, 4x10 Rowing barre.",
    quadriceps: "Séance Leg Day : 4x10 Squat, 3x12 Fentes avant."
};

const detailsExercices = {
    developpe_couche: "Muscle : Pectoraux. Allongé sur un banc, descends la barre jusqu'à la poitrine puis pousse.",
    elevation_laterale: "Muscle : Épaules. Lève les haltères sur les côtés jusqu'à hauteur d'épaules.",
    pompes: "Muscle : Pectoraux & Triceps. Garde le dos bien droit et fléchis les bras.",
    curl_biceps: "Muscle : Biceps. Garde les coudes collés au corps et remonte les haltères.",
    dips: "Muscle : Triceps & Pecs. Descends entre les barres parallèles puis pousse vers le haut.",
    crunch: "Muscle : Abdomen. Enroule le haut du buste sans tirer sur la nuque.",
    squat: "Muscle : Quadriceps. Pousse les fesses en arrière et garde les talons au sol.",
    tractions: "Muscle : Dorsaux. Tire ton menton au-dessus de la barre."
};

// Liste pour mémoriser les favoris (et élément actuellement affiché dans la pop-up)
const favoris = [];
const elementActuel = "";

// 3. Fonctions pour ouvrir et fermer la pop-up
function ouvrirPopup(titre, description) {
    elementActuel = titre;
    modalTitle.textContent = titre;
    modalBody.innerHTML = "<p>" + description + "</p>";

    // Met à jour l'apparence du bouton favori selon si l'élément est déjà enregistré
    if (favoris.includes(elementActuel)) {
        btnFavori.textContent = "★ Retirer des favoris";
    } else {
        btnFavori.textContent = "☆ Ajouter aux favoris";
    }

    popupModal.classList.remove("hidden"); // Affiche la pop-up
}

function fermerPopup() {
    popupModal.classList.add("hidden"); // Cache la pop-up
}

// 4. Événements (réactions aux actions de l'utilisateur)

// Quand on change de muscle dans le menu déroulant
muscleSelect.addEventListener("change", function () {
    if (muscleSelect.value !== "") {
        const texteMuscle = muscleSelect.options[muscleSelect.selectedIndex].text;
        selectedMuscleName.textContent = texteMuscle.toUpperCase();
    }
});

// Quand on clique sur le bouton "VOIR SÉANCES"
btnVoirSeances.addEventListener("click", function () {
    const muscleChoisi = muscleSelect.value || "abdomen"; // "abdomen" par défaut
    const nomAffiche = selectedMuscleName.textContent;
    const programme = donneesSeances[muscleChoisi] || "Aucune séance trouvée.";

    ouvrirPopup("Séances - " + nomAffiche, programme);
});

// Quand on choisit une séance précise dans le menu "Séances"
seanceSelect.addEventListener("change", function () {
    if (seanceSelect.value !== "") {
        const nomSeance = seanceSelect.options[seanceSelect.selectedIndex].text;
        ouvrirPopup("Séance : " + nomSeance, "Durée estimée : 45 min • Niveau : Intermédiaire.");
    }
});

// Quand on choisit un exercice dans le menu "Exercices"
exerciceSelect.addEventListener("change", function () {
    const codeExo = exerciceSelect.value;
    if (codeExo !== "") {
        const nomExo = exerciceSelect.options[exerciceSelect.selectedIndex].text;
        const desc = detailsExercices[codeExo] || "Description à venir.";
        ouvrirPopup(nomExo, desc);
    }
});

// Quand on appuie sur "Entrée" dans la barre de recherche
searchInput.addEventListener("keypress", function (event) {
    if (event.key === "Enter" && searchInput.value.trim() !== "") {
        ouvrirPopup("Recherche", "Résultats pour : " + searchInput.value);
    }
});

// Quand on clique sur le bouton Favori dans la pop-up
btnFavori.addEventListener("click", function () {
    if (favoris.includes(elementActuel)) {
        // On le retire des favoris
        favoris = favoris.filter(item => item !== elementActuel);
        btnFavori.textContent = "☆ Ajouter aux favoris";
    } else {
        // On l'ajoute aux favoris
        favoris.push(elementActuel);
        btnFavori.textContent = "★ Retirer des favoris";
    }
    console.log("Favoris actuels :", favoris);
});

// Fermeture de la pop-up avec la croix ou en cliquant sur le fond sombre
closeModalBtn.addEventListener("click", fermerPopup);
popupModal.addEventListener("click", function (event) {
    if (event.target === popupModal) {
        fermerPopup();
    }
});