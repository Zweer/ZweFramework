/*
---

name: ZweBox.Locale

authors:
  - Niccolò Olivieri (flicofloc@gmail.com)

license:
  - MIT-style license

requires: [More/Locale]

provides:
  - localization of ZweBox
...
*/
ZweBox.implement('options', {
    localization: {
        close: Locale.get('ZweBox.close'),
        prev: Locale.get('ZweBox.prev'),
        max: Locale.get('ZweBox.max'),
        next: Locale.get('ZweBox.next'),

        error_loading_image: Locale.get('ZweBox.error_loading_image'),
        error_loading_ajax: Locale.get('ZweBox.error_loading_ajax')
    }
});

Locale.define('en-US', 'ZweBox', {
    close: 'Close',
    prev: 'Previous',
    max: 'Maximize',
    next: 'Next',

    error_loading_image: 'The requested image cannot be loaded. Please try again later',
    error_loading_ajax: 'The requested content cannot be loaded. Please try again later'
});

Locale.define('it-IT', 'ZweBox', {
    close: 'Chiudi',
    prev: 'Precedente',
    max: 'Massimizza',
    next: 'Successiva',

    error_loading_image: 'L\'immagine richiesta non può essere caricata. Si prega di riprovare più tardi',
    error_loading_ajax: 'Il contenuto richiesto non può essere caricato. Si prega di riprovare più tardi'
});