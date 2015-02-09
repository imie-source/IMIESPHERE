
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

function clic(fonc, par1, par2){
	fonc(par2);
	back.push(par1)
	history();
	path.push(par2);
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
            			ctn +=					"<td><div class='btn' onclick=\"clic(afficheMsg, '"+topLib+"', '"+topId+"');\">" + topLib + "</div></td>";
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
						
						var msgLib = tabMsg[0];
						var msgId = tabMsg[1];
						var msgUser = tabMsg[2];
						var msgDate = tabMsg[3];

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
				setTimeout(function() { $("#loader").hide(); }, TIME);
				setTimeout(function() { $("#corpsACC").show(); }, TIME);
				setTimeout(function() { $("#titre").show(); }, TIME);
				$("#corpsACC").html(ctn);
				$("#titre").html(back[3]);
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
	console.log(back);
	console.log(path);
	setTimeout(function() { $("#history").show(); }, TIME);
	$("#history").html(ctn);
}

