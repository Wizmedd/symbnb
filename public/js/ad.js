document.addEventListener("DOMContentLoaded", function () {
    console.log('Chargement du DOM OK ');


    document.getElementById("add-image").onclick = function () {
        console.log('Evt: j\'ai cliqué sur ajouter une image');
        // je récupère le numéro des futurs champs qu'on va creer

        const index = document.getElementById('ad_images').getElementsByClassName('form-group').length;
        // const index = + document.getElementById('widgets-counter').value;
        console.log("nb occurences div.form-group : " + index);

        const ad = document.getElementById('ad_images');
        let tmpl = ad.getAttribute('data-prototype');
        // console.log(attribut);
        let newTmpl = tmpl.replace(/__name__/g, index);
        // console.log(newTmpl);

        var element = document.getElementById('ad_images');
        // on converti la chaine string en html et l'insère dans la div
        element.insertAdjacentHTML('beforeend', newTmpl);
        console.log("on a ajouté une image");
        document.getElementById("widgets-counter").setAttribute('value', index + 1);

        // supprimer une image

        handleDeleteButtons();

        updateCounter();

    };

    function updateCounter() {
        const count = document.getElementById('ad_images').getElementsByClassName('form-group').length;
        document.getElementById("widgets-counter").setAttribute('value', count);
        console.log('changement du count:' + count);
    };
    function handleDeleteButtons() {
        var matches = document.querySelectorAll("button[data-action=delete]");
        for (i = 0; i < matches.length; i++) {
            matches[i].addEventListener('click', function () {
                console.log('Evt: j\'ai cliqué sur supprimer l image ' + i);
                // this.style.width = "500px";
                const target = this.dataset.target;
                // console.log(target);

                var elem = document.getElementById(target);
                if (elem !== null) {
                    elem.remove();
                }


            });
        }
    }
    // pour l'édit d'annonce avec des images qui appaissent déjà
    handleDeleteButtons();

});