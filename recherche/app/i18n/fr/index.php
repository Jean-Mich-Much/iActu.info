<?php

/******************************************************************************/
/* Each entry of that file can be associated with a comment to indicate its   */
/* state. When there is no comment, it means the entry is fully translated.   */
/* The recognized comments are (comment matching is case-insensitive):        */
/*   + TODO: the entry has never been translated.                             */
/*   + DIRTY: the entry has been translated but needs to be updated.          */
/*   + IGNORE: the entry does not need to be translated.                      */
/* When a comment is not recognized, it is discarded.                         */
/******************************************************************************/

return array(
	'about' => array(
		'_' => 'À propos',
		'agpl3' => '<a href="https://www.gnu.org/licenses/agpl-3.0.html">AGPL 3</a>',	// IGNORE
		'bugs_reports' => 'Rapports de bugs',
		'credits' => 'Crédits',
		'credits_content' => 'Des éléments de design sont issus du <a href="http://twitter.github.io/bootstrap/">projet Bootstrap</a> bien que FreshRSS n’utilise pas ce framework. Les <a href="https://gitlab.gnome.org/Archive/gnome-icon-theme-symbolic">icônes</a> sont issues du <a href="https://www.gnome.org/">projet GNOME</a>. La police <em>Open Sans</em> utilisée a été créée par <a href="https://fonts.google.com/specimen/Open+Sans">Steve Matteson</a>. FreshRSS repose sur <a href="https://framagit.org/marienfressinaud/MINZ">Minz</a>, un framework PHP.',
		'documentation' => 'Documentation',	// IGNORE
		'freshrss_description' => 'FreshRSS est un agrégateur de flux RSS à auto-héberger. Il se veut léger et facile à prendre en main tout en étant un outil puissant et paramétrable.',
		'github' => '<a href="https://github.com/FreshRSS/FreshRSS/issues">sur Github</a>',
		'license' => 'Licence',
		'project_website' => 'Site du projet',
		'title' => 'À propos',
		'version' => 'Version',	// IGNORE
	),
	'feed' => array(
		'empty' => 'Il n’y a aucun article à afficher.',
		'rss_of' => 'Flux RSS de %s',
		'title' => 'Flux principal',
		'title_fav' => 'Favoris',
		'title_global' => 'Vue globale',
	),
	'log' => array(
		'_' => 'Logs',	// IGNORE
		'clear' => 'Effacer les logs',
		'empty' => 'Les logs sont vides.',
		'title' => 'Logs',	// IGNORE
	),
	'menu' => array(
		'about' => 'À propos de FreshRSS',
		'before_one_day' => 'Antérieurs à 1 jour',
		'before_one_week' => 'Antérieurs à 1 semaine',
		'bookmark_query' => 'Enregistrer la recherche courante',
		'favorites' => 'Articles favoris (%s)',
		'global_view' => 'Vue globale',
		'important' => 'Flux importants',	// DIRTY
		'main_stream' => '1.0 📋 Tous les sites',
		'mark_all_read' => 'Tout marquer comme lu',
		'mark_cat_read' => 'Marquer la catégorie comme lue',
		'mark_feed_read' => 'Marquer le flux comme lu',
		'mark_selection_unread' => 'Marquer la sélection comme non-lue',
		'newer_first' => 'Plus récents en premier',
		'non-starred' => 'Afficher les non-favoris',
		'normal_view' => 'Vue normale',
		'older_first' => 'Plus anciens en premier',
		'queries' => 'Filtres utilisateurs',
		'read' => 'Afficher les lus',
		'reader_view' => 'Vue lecture',
		'rss_view' => 'Flux RSS',
		'search_short' => 'Rechercher',
		'starred' => 'Afficher les favoris',
		'stats' => 'Statistiques',
		'subscription' => 'Gestion des abonnements',
		'tags' => 'Mes étiquettes',
		'unread' => 'Afficher les non-lus',
	),
	'share' => 'Partager',
	'tag' => array(
		'related' => 'Tags de l’article',
	),
	'tos' => array(
		'title' => 'Conditions Générales d’Utilisation',
	),
);
