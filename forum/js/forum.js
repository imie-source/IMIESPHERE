

function afficheTheme() {
	// Utilisation de la méthode get de jQuery
	$.get( "/forum/php/index.php", 
		   { action: "listeTheme" },
	       function( data ) { // Fonction de callback en cas de succès
				// Je mets en forme le contenu reçu
				var ctn = "";
				var tabThemes = data.split("\n");
				for(var i = 0; i < tabThemes.length; i++) {
					tabThemes[i] = tabThemes[i].trim();
					if (tabThemes[i] != "") {
						var tabTheme = tabThemes[i].split(";");
						var themeLib = tabTheme[0];
						var themeId = tabTheme[1];
						console.log(themeId);
						ctn += "<button class='btn' onclick=\"afficheCat('" + themeId + "');\">";
						ctn += themeLib + "</button>";
					}
										
				}
				// Je mets à jour le contenu du div d'id "messages"
				$("#content").html(ctn);
		   }
	);
}

//onclick=\"afficheCat('" + themeId + "');\"

function afficheCat(id_theme) {
	// Utilisation de la méthode get de jQuery
	$.get( "/forum/php/index.php", 
		   { action: "listeCat", 
			id_theme: id_theme },
	       function( data ) { // Fonction de callback en cas de succès
				// Je mets en forme le contenu reçu
				var ctn = "";
				var tabCats = data.split("\n");
				for(var i = 0; i < tabCats.length; i++) {
					tabCats[i] = tabCats[i].trim();
					if (tabCats[i] != "") {
						var tabCat = tabCats[i].split(";");
						var catLib = tabCat[0];
						var catId = tabCat[1];
						//console.log("test");
						ctn += "<button class = 'btn'>" + catLib + "</button>";
					}
										
				}
				// Je mets à jour le contenu du div d'id "messages"
				$("#content").html(ctn);
		   }
	);
}

