/* 
 *+--------------------------------------------------------------+
 *| • FONCTIONS JAVASCRIPT (PROJET WEB)                          |
 *+--------------------------------------------------------------+
 */

/*- Fonction Konami Code -*/
jQuery(function(){
    var kKeys = [];
    function Kpress(e){
        kKeys.push(e.keyCode);
        if (kKeys.toString().indexOf("38,38,40,40,37,39,37,39,66,65") >= 0) {
            jQuery(this).unbind('keydown', Kpress);
            kExec();
        }
    }
    jQuery(document).keydown(Kpress);
});
function kExec(){
   alert("TELEPORTATIOOOON !");
   window.location = "../html/accueil2.html";
}

/*- Fonction de gestion d'onglet de la page projet -*/
function change_onglet(name) {
    document.getElementById('onglet_'+anc_onglet).className = 'onglet';
    document.getElementById('onglet_'+name).className = 'onglet';
    document.getElementById('contenu_onglet_'+anc_onglet).style.display = 'none';
    document.getElementById('contenu_onglet_'+name).style.display = 'block';
    anc_onglet = name;
}

var anc_onglet = 'itstart';
change_onglet(anc_onglet);
