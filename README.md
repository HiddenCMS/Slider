# HiddenCMS Slider

Module et widget Composer pour HiddenCMS ^0.7.3, PHP >=8.1.

## Administration

- Plusieurs sliders independants, chacun avec ses propres slides.
- Images choisies ou televersees par la modale du module Files, avec navigation dans les dossiers.
- Titre, description courte, texte alternatif et bouton facultatifs par slide.
- Ordre par glisser-deposer avec le bouton de tri natif des menus ; sauvegarde automatique et restauration de l'ordre precedent en cas d'erreur.
- Listes avec apercus d'images, informations et badges d'etat ; activation du slider ou de chaque slide.
- Animations : glissement, fondu, cube et retournement, gerees par Swiper 12.0.2 (MIT).
- Pause entre les images, duree de transition et hauteur configurables par slider.
- Apercu depuis la liste des slides.

## Widget

Activer le module Sliders et le widget Slider dans Themes & Addons. Dans le live editor, ajouter un widget Slider puis choisir le diaporama.

Le widget affiche uniquement le contenu, sans carte. L'option Pleine largeur de l'ecran permet de sortir de la largeur de la colonne ; utiliser de preference une ligne dediee pour eviter de recouvrir d'autres colonnes. Les helpers CSS habituels restent applicables sur le contenu.

Les images remplissent la zone par recadrage (object-fit: cover). La hauteur est adaptee sur mobile. Navigation tactile, fleches, pagination et bouton lecture/pause pour plusieurs slides. Un slider d'une seule image n'affiche pas de controles. Les animations et la lecture automatique sont desactivees au chargement si le visiteur prefere un mouvement reduit. Une prise de focus sur le contenu arrete le defilement.

Les sliders inactifs, vides ou dont les images ont ete supprimees n'affichent rien. Un lien de bouton doit etre http(s) ou commencer par / ; texte et URL sont requis ensemble. Les descriptions sont du texte, pas du HTML.

## Installation

Si le paquet n'est pas reference sur Packagist, ajouter le depot VCS https://github.com/HiddenCMS/Slider a Composer.

```sh
composer require hiddencms/slider:^0.1
php tools/addons.php sync
```

Les migrations creent slider_sets et slider_slides, sans contenu fictif. La purge du paquet supprime ces tables ; les fichiers de la mediatheque restent intacts. Les droits se gerent via la permission manage_sliders du module.

## Tests

```sh
php tests/settings.php
php tests/database.php /chemin/du/site-de-test
```

Le test SQL annule ses changements par transaction. Pour les essais visuels, tests/render.php genere un fichier HTML temporaire a partir d'un slider de test existant ; supprimer ce fichier apres verification. Les assets Swiper sont embarques sans CDN. Pour les regenerer, installer les dependances npm puis copier swiper-bundle.min.js, swiper-bundle.min.css et LICENSE vers les dossiers js/css du widget.

Licence du module : GPL-3.0-only. Licence Swiper embarquee dans widgets/slider/js/SWIPER-LICENSE. Les vues restent personnalisables via les overrides de themes HiddenCMS.
