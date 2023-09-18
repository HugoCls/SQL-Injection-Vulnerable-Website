function getCookie(name) {
    var cookieValue = null;
    if (document.cookie && document.cookie !== "") {
        var cookies = document.cookie.split(";");
        for (var i = 0; i < cookies.length; i++) {
            var cookie = cookies[i].trim();
            if (cookie.substring(0, name.length + 1) === name + "=") {
                cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                break;
            }
        }
    }
    return cookieValue;
}

function connect_buttons(bool_connected) {
    var inscriptionLink = document.createElement("a");
    inscriptionLink.href = "inscription.html";
    inscriptionLink.textContent = "Inscription";
    document.getElementById("inscription-button-container").appendChild(inscriptionLink);

    if (bool_connected) {
        // Le jeton d'authentification est valide, marquez l'utilisateur comme connecté
        document.getElementById("status").textContent = "Connecté";

        // Créer le lien de déconnexion
        var logoutLink = document.createElement("a");
        logoutLink.href = "php/logout.php";
        logoutLink.textContent = "Déconnexion";
        document.getElementById("login-button-container").appendChild(logoutLink);
    } else {
        console.log("Disconnected")
        // L'utilisateur n'a pas de cookie d'authentification, traitez-le comme déconnecté
        document.getElementById("status").textContent = "Déconnecté";

        // Créer le lien de connexion
        var loginLink = document.createElement("a");
        loginLink.href = "login.html";
        loginLink.textContent = "Connexion";
        document.getElementById("login-button-container").appendChild(loginLink);
    }
}

function search_bar(bool_connected) {
    // Sélectionner les éléments du DOM
    var searchBar = document.getElementById("search-bar");

    if (bool_connected) {
        // Créer le formulaire de recherche
        var searchForm = document.createElement("form");
        searchForm.id = "search-form";
        searchForm.action = "php/recherche.php";
        searchForm.method = "POST";

        // Créer les éléments du formulaire
        var input = document.createElement("input");
        input.type = "text";
        input.id = "search-input";
        input.placeholder = "Rechercher un livre par titre, auteur, ou mot-clé";

        var button = document.createElement("button");
        button.type = "submit";
        button.textContent = "Rechercher";

        // Ajouter les éléments au formulaire
        searchForm.appendChild(input);
        searchForm.appendChild(button);

        // Ajouter le formulaire à la barre de recherche
        searchBar.appendChild(searchForm);
    } else {
        // L'utilisateur n'est pas connecté, afficher un message
        var message = document.createElement("p");
        message.textContent = "Veuillez vous connecter pour lancer une recherche.";
        searchBar.appendChild(message);
    }
}

// Utilisez cette fonction pour afficher les livres
function display_books(books) {
    var bookImages = document.getElementById("book-images");
    bookImages.innerHTML = ""; // Effacez le contenu précédent

    if (books.length === 0) {
        var noResults = document.createElement("p");
        noResults.textContent = "Aucun livre ne correspond à la recherche.";
        bookImages.appendChild(noResults);
    } else {
        books.forEach(function (book) {
            var bookDiv = document.createElement("div");
            bookDiv.classList.add("book");

            var img = document.createElement("img");
            img.src = book.front_page;
            img.alt = book.name;

            var title = document.createElement("p");
            title.textContent = book.name;

            var author = document.createElement("p");
            author.textContent = "Auteur: " + book.author;

            bookDiv.appendChild(img);
            bookDiv.appendChild(title);
            bookDiv.appendChild(author);

            bookImages.appendChild(bookDiv);
        });
    }
}    

var AUTH = getCookie("AUTH");

// Vérifiez le jeton d'authentification côté serveur
fetch("php/validate_token.php", {
    method: "POST",
    headers: {
        "Content-Type": "application/json",
    },
    body: JSON.stringify({ AUTH: AUTH }),
})
.then(function (response) {
    return response.json();
})
.then(function (data) {
    connect_buttons(data.valid);
    search_bar(data.valid);
    
    
    // Lorsque le formulaire de recherche est soumis
    var searchForm = document.getElementById("search-form");
    if (searchForm || searchForm === "") {
        searchForm.addEventListener("submit", function (e) {
            e.preventDefault(); // Empêche le formulaire de recharger la page
            
            var searchInput = document.getElementById("search-input");
            var searchQuery = searchInput.value.trim();
            
            // Envoyez la requête de recherche à recherche.php
            fetch("php/recherche.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: "search=" + encodeURIComponent(searchQuery),
            })
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                display_books(data); // Affichez les livres résultants
            });
    });}
});
