
/*$(document).ajaxStart(function() {
  //$("#loader").show();
  //$("#loader").css('background', 'url(../html/loader.gif) no-repeat');
  //$('#corpsACC').html('<span>Loading...</span>')
  $('#corpsACC').css('background', 'red');
  setTimeout(function() { $("#corpsACC").hide(); }, 5000);
  console.log("load");
});

$(document).ajaxStop(function() {
  //$("#loader").hide();
  //$('#corpsACC').css('background', 'red').delay(500000);
  //$('#corpsACC').css('background', 'none');
});*/

var back = ['Themes'];
var path = ['0'];
var TIME = 500;
var page = 1;

function clic(fonc, par1, par2){
	fonc(par2);
	back.push(par1)
	history();
	path.push(par2);
	page ++;
	if(page==3){
		$("#corpsACC").css("overflow", "scroll");
	}
	//console.log(path);
}

function afficheTheme() {
	$("#loader").html('<img src="../html/loader.gif">');
	$("#loader").show();
	$("#corpsACC").hide();
	$("#titre").hide();
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
						ctn += "<div id='sq"+i+"' onclick=\"clic(afficheCat, '"+themeLib+"', '"+themeId+"');\">";
						ctn += "<div id='sq"+i+"Text'>" +themeLib + "</div></div>";					
						//thm += "<p>" + themeLib + "</p>";
					}
										
				}
				
				// Je mets à jour le contenu du div d'id "messages"				
				setTimeout(function() { $("#loader").hide(); }, TIME);
				setTimeout(function() { $("#corpsACC").show(); }, TIME);
				setTimeout(function() { $("#titre").show(); }, TIME);
				$("#corpsACC").html(ctn);
				$("#titre").html('THEMES');
				//page = 1;
				console.log(page);
				//path.push(themeLib);
				//$("#theme").html(thm);
		   }
		 
	);
}

//onclick=\"afficheCat('" + themeId + "');\"

function afficheCat(id_theme) {
	$("#loader").html('<img src="../html/loader.gif">');
	$("#loader").show();
	$("#corpsACC").hide();
	$("#titre").hide();
	//console.log(path);
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
						ctn += "<div id='sq" + i + "' onclick=\"clic(afficheTopic, '"+catLib+"', '"+catId+"');\">";
						ctn += "<div id='sq"+i+"Text'>" + catLib + "</div></div>";
					}
										
				}
				// Je mets à jour le contenu du div d'id "messages"
				setTimeout(function() { $("#loader").hide(); }, TIME);
				setTimeout(function() { $("#corpsACC").show(); }, TIME);
				setTimeout(function() { $("#titre").show(); }, TIME);
				$("#corpsACC").html(ctn);
				$("#titre").html(back[1]);
				console.log(page);
				//page = 2;
				// Afficher la barre des themes..
		   }
	);
}


function afficheTopic(id_categorie) {
	$("#loader").html('<img src="../html/loader.gif">');
	$("#loader").show();
	$("#corpsACC").hide();
	$("#titre").hide();

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
						
						// Nom du topic, champ 1
						var topLib = tabTop[0];
						// Id du topic, champ 2
						var topId = tabTop[1];
						// Id de l'utilisateur, champ 3
						var topUserID = tabTop[2];
						// Date du topic, champ 4
						var topDate = tabTop[3];
						// Messages, champ 5
						var topMsg = tabTop[4];

						if(topMsg=="")
							topMsg=0;
						
						// J'affiche les topics avec leur nom, la date, l'utilisateur.						
						ctn += "<table>";
       					ctn +=			"<thead>";
          				ctn +=				"<tr>";
            			ctn +=					"<th><div class='btn' onclick=\"afficheMsg('" + topId + "');\">" + topLib + "</div></th>";
        				ctn +=				"</tr>";
        				ctn +=			"</thead>";
        				ctn +=			"<tbody>";
          				ctn +=				"<tr class='light'>";
            			ctn +=					"<td>" + topMsg + " messages dans ce topic.</td>";
          				ctn +=				"</tr>";
        				ctn +=				"<tr class='dark'>";
            			ctn +=				"<td>" + topUserID + " a publié le " + topDate + "</td>";
       					ctn +=			    "</tr>";
		      			ctn +=			"</tbody>";
      					ctn += "</table>";
					}
										
				}
				// Je mets à jour le contenu du div d'id "messages"
				setTimeout(function() { $("#loader").hide(); }, TIME);
				setTimeout(function() { $("#corpsACC").show(); }, TIME);
				setTimeout(function() { $("#titre").show(); }, TIME);
				$("#corpsACC").html(ctn);
				$("#titre").html(back[2]);
				console.log(page);
				//page = 3;
				//Scroll topics gauche
				//$("#corpsACC").css("overflow", "scroll");

		   }
	);
}

function afficheMsg(id_topic) {
	$("#loader").html('<img src="../html/loader.gif">');
	$("#loader").show();
	$("#corpsACC").hide();
	$("#titre").hide();
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
						
						// Libelle du message, premier champ du tableau
						var msgLib = tabMsg[0];
						// Id du msg, second champ
						var msgId = tabMsg[1];
						// Utilisateur qui a ecrit le msg, troisieme champ
						var msgUser = tabMsg[2];
						// La date à laquelle le msg a ete ecrit, quatrieme champ
						var msgDate = tabMsg[3];
						// Id du topic auquel le msg appartient, cinquieme champ, pour la fonction "refresh"
						var topId = tabMsg[4];


						// Si la variable i est paire
						if(i%2){
						// On affiche le message avec son contenu, son créateur, sa date	
						ctn += "<table>";
        				ctn +=		"<tbody>";
          				ctn +=			"<tr class='light'>";
            			ctn +=				"<td>" + msgLib;
            			ctn +=					"<div id='use'>" + msgUser + " a publié le " + msgDate + "</div>";
          				ctn +=			"</tr>";
          				ctn +=		"</tbody>";
      					ctn += "</table>";

      					// Si la varible i est impaire, même chose mais d'une couleur differente
      					}else{
      					ctn += "<table>";
        				ctn +=		"<tbody>";
        				ctn +=			"<tr class='dark'>";
            			ctn +=				"<td>" + msgLib;
            			ctn +=					 "<div id='use'>" + msgUser + " a publié le " + msgDate + "</div>";
       					ctn +=			  "</tr>";
		      			ctn +=		"</tbody>";
      					ctn += "</table>";

      					
      					//setInterval("afficheMsg("+ topId +");", 20000);
      					}
					}
										
				}
				// Je mets à jour le contenu du div d'id "messages"
				setTimeout(function() { $("#loader").hide(); }, TIME);
				setTimeout(function() { $("#corpsACC").show(); }, TIME);
				setTimeout(function() { $("#titre").show(); }, TIME);
				$("#corpsACC").html(ctn);
				$("#titre").html(back[3]);
				console.log(page);
				//page = 4;
				// Scrool messages gauche
				//$("#corpsACC").css("overflow", "scroll"); 
		   }
	);
}
/*Fonction du onclick de l'history
**	on supprime (splice) du tableau le numero correspondant a l'id du niveau auquel on veux revenir (par1)
**	on supprime aussi le nom (dans le boutton) correspondant au niveau du retour (par2)
**	dans certaines fonction il est necessaire de fournir un parametre (par3)
*/
function clic2(fonc, par1, par2, par3){
	fonc(par3);
	back.splice(back.length-1, back.length-1);
	path.splice(path.length-1, path.length-1);	
	history();
	page --;
	if(page!=3){
		$("#corpsACC").css("overflow", "auto");
	}
}


function history(){
	
	ctn ="";
	$("#history").hide();
	for (var i=0; i<back.length; i++){
	    switch(i){
	    	case 0: level = 'clic2(afficheTheme, 1, 1)';
	    			break;
	    	case 1: level = 'clic2(afficheCat, 2, 2, path[1])';
	    			break;
	    	case 2: level = 'clic2(afficheTopic, 4, 3, path[2])';
	    			break;
	    	case 3: level = 'clic2(afficheMsg, 4, 4, path[3])';
	    			break;
	    }

	    ctn += "<button class='bh' onclick='"+level+";'>"+back[i]+"</button>";
	}
	//console.log(back);
	//console.log(path);
	setTimeout(function() { $("#history").show(); }, TIME);
	$("#history").html(ctn);
}


