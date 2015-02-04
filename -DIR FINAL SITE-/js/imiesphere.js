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
   window.location = "../html/acctest2.html";
}