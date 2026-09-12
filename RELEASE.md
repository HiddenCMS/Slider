# HiddenCMS Slider 0.1.0

Premiere release stable du module et du widget Slider.

- Plusieurs sliders, avec titre, description et bouton facultatifs sur chaque slide.
- Selection d'images via la mediatheque du core.
- Animations glissement, fondu, cube et retournement avec Swiper embarque.
- Lecture automatique, vitesse, hauteur et option pleine largeur configurables.
- Listes avec apercus et badges, tri des slides par glisser-deposer et sauvegarde automatique.
- Apercu avec retour aux slides ; prise en charge du mobile et du mouvement reduit.

Compatibilite : HiddenCMS ^0.7.3, PHP >=8.1. Installation via Composer : hiddencms/slider:^0.1. Activer le module et le widget dans Themes & Addons.

La migration cree slider_sets et slider_slides sans contenu fictif. La purge supprime ces tables, pas les fichiers de la mediatheque.

Validation : 12 tests de reglages et 15 tests SQL avec annulation des donnees de test. Administration, selection d'images, animations, affichage mobile et tri persistant verifies localement. Installation Composer et synchronisation des migrations testees sur le site local ; installation neuve complete non retestee.
