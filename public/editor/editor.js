window.addEventListener('load', function() {
    var editor = ContentTools.EditorApp.get();
    editor.init('*[data-editable]', 'data-name');

    // Define our request for the Polish translation file
    var xhr = new XMLHttpRequest();
    xhr.addEventListener('readystatechange', function (ev) {
        var translations;
        if (ev.target.readyState === 4) {
            // Convert the JSON data to a native Object
            translations = JSON.parse(ev.target.responseText);

            // Add the translations for the Polish language
            ContentEdit.addTranslations('pl', translations);

            // Set Polish as the editors current language
            ContentEdit.LANGUAGE = 'pl';
        }
    });

    // Load the language
    xhr.open('GET', '/REBEL.admin/public/editor/translations/pl.json', true);
    xhr.send(null);

    editor.addEventListener('saved', function (ev) {
        var name, payload, regions, xhr;

        // Check that something changed
        regions = ev.detail().regions;
        if (Object.keys(regions).length === 0) {
            return;
        }

        // Set the editor as busy while we save our changes
        this.busy(true);

        // Collect the contents of each region into a FormData instance
        payload = new FormData();
        for (name in regions) {
            if (regions.hasOwnProperty(name)) {
                payload.append(name, regions[name]);
            }
        }

        xhr = new XMLHttpRequest();
        xhr.addEventListener('readystatechange', function (ev) {
            // Check if the request is finished
            if (ev.target.readyState === 4) {
                editor.busy(false);
                if (ev.target.status === '200') {
                    // Save was successful, notify the user with a flash
                    new ContentTools.FlashUI('ok');
                } else {
                    // Save failed, notify the user with a flash
                    new ContentTools.FlashUI('no');
                }
            }
        });

        xhr.open('POST', '/save-my-page');
        xhr.send(payload);
    });
});