$('.delete').on('click', function() {

	var articleId = $(this).attr('href').substring(22);

	if (!confirm('Voulez-vous vraiment supprimer l\'article d\'id ' + articleId + ' ?')) {

		return false;

	}

	return true;

});