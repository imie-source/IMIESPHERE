

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
						//console.log(themeId);
						ctn += "<div id='sq" + i + "' onclick=\"afficheCat('" + themeId + "');\">";
						ctn += "<div id='sq"+i+"Text'>" +themeLib + "</div></div>";
					
						//thm += "<p>" + themeLib + "</p>";

					}
										
				}
				// Je mets à jour le contenu du div d'id "messages"
				$("#corpsACC").html(ctn);
				//$("#theme").html(thm);
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
						console.log(i);
						ctn += "<div id='sq" + i + "' onclick=\"afficheTopic('" + catId + "');\">";
						ctn += "<div id='sq"+i+"Text'>" + catLib + "</div></div>";
					}
										
				}
				// Je mets à jour le contenu du div d'id "messages"
				$("#corpsACC").html(ctn);
				// Afficher la barre des themes..
		   }
	);
}


function afficheTopic(id_categorie) {
	// Utilisation de la méthode get de jQuery
	$.get( "/forum/php/index.php", 
		   { action: "listeTopic", 
			id_categorie: id_categorie },
	       function( data ) { // Fonction de callback en cas de succès
				// Je mets en forme le contenu reçu
				var ctn = "";
				var tabTops = data.split("\n");
				for(var i = 0; i < tabTops.length; i++) {
					tabTops[i] = tabTops[i].trim();
					if (tabTops[i] != "") {
						var tabTop = tabTops[i].split(";");
						var topLib = tabTop[0];
						var topId = tabTop[1];
						var topUserID = tabTop[2];
						var topDate = tabTop[3];
						
						//console.log("test");
						
						ctn += "<table>";
       					ctn +=			"<thead>";
          				ctn +=				"<tr>";
            			ctn +=					"<th>" + topId + "</th>";
        				ctn +=				"</tr>";
        				ctn +=			"</thead>";
        				ctn +=			"<tbody>";
          				ctn +=				"<tr class='light'>";
            			ctn +=					"<td><div class='btn' onclick=\"afficheMsg('" + topId + "');\">" + topLib + "</div></td>";
          				ctn +=				"</tr>";
        				ctn +=				"<tr class='dark'>";
            			ctn +=				"<td>" + topUserID + " a publié le " + topDate + "</td>";
       					ctn +=			    "</tr>";
		      			ctn +=			"</tbody>";
      					ctn += "</table>";
					}
										
				}
				// Je mets à jour le contenu du div d'id "messages"
				$("#corpsACC").html(ctn);
		   }
	);
}

function afficheMsg(id_topic) {
	// Utilisation de la méthode get de jQuery
	$.get( "/forum/php/index.php", 
		   { action: "listeMsg", 
			id_topic: id_topic },
	       function( data ) { // Fonction de callback en cas de succès
				// Je mets en forme le contenu reçu
				var ctn = "";
				var tabMsgs = data.split("\n");
				for(var i = 0; i < tabMsgs.length; i++) {
					tabMsgs[i] = tabMsgs[i].trim();
					if (tabMsgs[i] != "") {
						var tabMsg = tabMsgs[i].split(";");
						
						var msgLib = tabMsg[0];
						var msgId = tabMsg[1];
						var msgUser = tabMsg[2];
						var msgDate = tabMsg[3];
						
						console.log(msgUser);

						if(i%2){
						ctn += "<table>";
        				ctn +=		"<tbody>";
          				ctn +=			"<tr class='light'>";
            			ctn +=				"<td>" + msgLib;
            			ctn +=					"<div id='use'>" + msgUser + " a publié le " + msgDate + "</div>";
          				ctn +=			"</tr>";
          				ctn +=		"</tbody>";
      					ctn += "</table>";
      					}else{
      					ctn += "<table>";
        				ctn +=		"<tbody>";
        				ctn +=			"<tr class='dark'>";
            			ctn +=				"<td>" + msgLib;
            			ctn +=					 "<div id='use'>" + msgUser + " a publié le " + msgDate + "</div>";
       					ctn +=			  "</tr>";
		      			ctn +=		"</tbody>";
      					ctn += "</table>";
      					}
					}
										
				}
				// Je mets à jour le contenu du div d'id "messages"
				$("#corpsACC").html(ctn);
		   }
	);
}

